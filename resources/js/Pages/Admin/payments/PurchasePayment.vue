<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, computed, ref, watch } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { usePage } from '@inertiajs/vue3';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';


const page = usePage()
const toast = useToast();
const formRef = ref(null);
const quantity = ref(0);
const products = ref('');
// Form state

const props = defineProps({
    purchase: {
        type: Object,
        default: () => ({}),
    },
});

const selectedProducts = ref(
    (props.purchase?.products ?? []).map(product => ({
        purchase_item_id: product.id,
        id: product.product_id,
        name: product.name,
        code: product.code,
        cost: product.cost,
        quantity: product.quantity,
        discount: product.discount,
        discounted_price: product.discounted_price || 0,
        subtotal: product.subtotal,
        oldQuantity: product.oldQuantity,
    }))
);

const formData = ref({
    amount: '',
    order_status: props.purchase?.order_status || null,
});

const supplier = ref(props.purchase?.supplier_id || null);
const warehouse = ref(props.purchase?.warehouse_id || null);
const payment_status = ref(props.purchase?.payment_status || null);
const sub_total = ref(props.purchase?.received_amount || null);
const purchase_code = ref(props.purchase?.purchase_code || null);

onMounted(() => {
    const formElement = document.getElementById('cheque_form');
    if (formElement) {
        formElement.style.display = 'none';
    }
    const dateInput = document.getElementById('dateInput');
    const today = new Date().toISOString().split('T')[0];
    dateInput.value = today;

    nextTick(() => {
         $('#perchesTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollY: '',
            scrollCollapse: true,
            paging: true,
        });

    });
});

const handleSubmit = (e) => {
    e.preventDefault();

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }
    axios.put(route('purchase.payment.products', { id: props.purchase.id }), formData.value)
        .then(response => {
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.payment.purchase');
            } else if (response?.data?.error) {
                toast.error(response.data.error);
            }
        })
        .catch(error => {
            if (error.response?.data?.errors) {
                const errorMessages = Object.values(error.response.data.errors || {}).flat();
                errorMessages.forEach(message => {
                    toast.error(message);
                });
            } else if (error.response?.data?.message) {
                toast.error(error.response.data.message);
            } else {
                toast.error('An unexpected error occurred.');
            }
            console.error(error);
        });
};

const removeProductFromTable = (productId) => {
    selectedProducts.value = selectedProducts.value.filter(
        (product) => product.id !== productId
    );
};

const adjustQuantity = (productId, adjustment) => {
    const product = selectedProducts.value.find((p) => p.id === productId);
    if (product) {
        product.quantity = Math.max(0, product.quantity + adjustment);
    }
};

const calculateDiscountedPrice = (product) => {
    const discount = product.discount ?? 0;
    const discountAmount = (product.cost * discount) / 100;
    return product.cost - discountAmount;
};

const totalPrice = computed(() => {
    return selectedProducts.value.reduce((total, product) => {
        const discountedPrice = calculateDiscountedPrice(product);
        return total + discountedPrice * product.quantity;
    }, 0);
});

</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">UPDATE PURCHASE PAYMENT</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active"><a :href="route('admin.payment.purchase')">Purchase Payment</a></li>
                    <li class="breadcrumb-item active">Update Purchase Payment</li>
                </ol>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title"> Purchase Code : {{props.purchase.purchase_code}}</h3>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" ref="formRef" @submit.prevent="handleSubmit" novalidate>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="cheque-name">Total Price :</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter Cheque Name"
                                    v-model="totalPrice" disabled>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="cheque-name">Received Amount :</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter Cheque Name"
                                    v-model="sub_total" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Order Status :</label>
                                <select class="form-select" aria-label="Default select example"
                                    v-model="formData.order_status" required>
                                    <option value="" disabled>Select Order Status</option>
                                    <option value=1>Pending</option>
                                    <option value=2>Confirmed</option>
                                    <option value=3>Delivered</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please Provide Select Order Status
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="cheque-name">Paid Amount :</label>
                                <input type="number" class="form-control" id="name" placeholder="Enter Paid Amount"
                                    v-model="formData.amount" required>
                                <div class="invalid-feedback">
                                    Please Provide Enter Paid Amount
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 d-flex justify-content-end">
                        <button type="submit " class="btn btn-primary mt-4 category-btn">Save</button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Purchase Payment Details</h3>
                    </div>
                    <div class="card-body">
                        <table id="perchesTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">Product Code</th>
                                    <th style="width: 20%;">Product Name</th>
                                    <th>Cost (Rs.)</th>
                                    <th>Discount (%)</th>
                                    <th>Discount (Rs.)</th>
                                    <th style="width: 15%;">Quantity</th>
                                    <th>Subtotal (Rs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in selectedProducts" :key="product.id">
                                    <td>{{ product.code }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.cost }}</td>
                                    <td>
                                        {{ product.discount }}
                                    </td>
                                    <td>
                                        {{ (product.discount / 100 * (product.quantity * product.cost)).toFixed(2) }}

                                    </td>
                                    <td>
                                        <div class="row d-flex">
                                            <div class="input-group quantity-group">{{ product.quantity }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ (calculateDiscountedPrice(product) * product.quantity).toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </AdminLayout>
</template>
