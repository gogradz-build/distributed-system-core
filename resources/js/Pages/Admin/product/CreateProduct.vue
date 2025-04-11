<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, computed, ref, watch } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { Link, usePage } from '@inertiajs/vue3';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';



const page = usePage()
const toast = useToast();
const formRef = ref(null);
const errors = ref({});
// Form state
const formData = ref({
    code: '',
    name: '',
    cost: '',
    price: '',
    image: '',
    category_id: '',
    brand_id: '',
    supplier_id: '',
    warehouse_id: '',
    variation_id: '',
    order_status: '',
    quantity: '',
    purchase_code: '',
    paid_amount: '',
    payment_status: '',
    purchase_show: 0,
    variation_values: {}

});

const selectedValues = ref({});

watch(selectedValues, (newValues) => {

    formData.value.variation_values = selectedValues;
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        formData.value.image = file;
    }
};
const handleSubmit = (e) => {
    e.preventDefault();

    if (formData.value.purchase_show == 1) {
        if (validationForm()) {
            if (!formRef.value.checkValidity()) {
                formRef.value.classList.add('was-validated');
                return;
            }
        }
    }
    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }

    axios.post('/products', formData.value)
        .then(response => {
            console.log(response);
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.products');
            } else if (response?.data?.error) {
                toast.error(response.data.error);
            }
        })
        .catch(error => {
            if (error.response?.data?.errors) {
                console.log(error.response?.data?.errors);
                const errorMessages = Object.values(error.response.data.errors || {}).flat();
                errorMessages.forEach(message => {
                    toast.error(message);
                });
            } else if (error.response?.data?.message) {
                toast.error(error.response.data.message);
            } else {
                toast.error('An unexpected error occurred.');
            }
            console.log(error);
        });
};
const validationForm = () => {
    errors.value = {};
    if (!formData.value.supplier_id) errors.value.supplier_id = 'Supplier is required';
    if (!formData.value.warehouse_id) errors.value.warehouse_id = 'Warehouse is required';
    if (!formData.value.payment_status) errors.value.payment_status = 'Payment Type is required';
    if (!formData.value.order_status) errors.value.order_status = 'Order status is required';
    if (!formData.value.quantity)errors.value.quantity = 'Quantity is required';
    if (!formData.value.paid_amount) errors.value.paid_amount = 'Paid amount is required';
    console.log( errors.value);
    return Object.keys(errors).length === 0;

}
onMounted(() => {
    fetchCategories();
    fetchSuppliers();
    fetchWarehouse();
    fetchVariations();
    fetchBrands();

    // ----------multiplefile-upload---------
    $("#image").fileinput({
        'theme': 'fa',
        'uploadUrl': '#',
        showRemove: false,
        showUpload: false,
        showZoom: false,
        showCaption: false,
        browseClass: "btn btn-danger",
        browseLabel: "",
        browseIcon: "<i class='fa fa-plus'></i>",
        overwriteInitial: false,
        initialPreviewAsData: true,
        fileActionSettings: {
            showUpload: false,
            showZoom: false,
            removeIcon: "<i class='fa fa-times'></i>",
        }
    })

    nextTick(() => {

        $('#courseTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollY: '300px',
            scrollCollapse: true,
            paging: false
        });

    });
});

watch(() => formData.value.purchase_show, (newVal) => {
    console.log(newVal);
    const formElement = document.getElementById('purchase_form');
    if (newVal == 1) {
        formElement.style.display = 'flex '; // Show the form
    } else {
        formElement.style.display = 'none '; // Hide the form
    }
});

watch(() => formData.value.variation_id, (newVal) => {
    selectedValues.value = {};
    fetchVariationData(newVal);
    const formElement = document.getElementById('variation_form');
    if (newVal) {
        formElement.style.display = 'block'; // Show the form
    } else {
        formElement.style.display = 'none'; // Hide the form
    }
});

watch(() => formData.variation_values, (newVal) => {
    console.log("Updated variation values:", newVal);
});

const variationValues = ref([]);

const fetchVariationData = async (newVal) => {

    try {
        const response = await axios.get(route('get.variation.type.data.value', { variation_id: newVal }));
        variationValues.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

// fetch data

const categories = ref([]);
const suppliers = ref([]);
const warehouses = ref([]);
const variations = ref([]);
const brands = ref([]);

const fetchBrands = () => fetchData('/brands', brands);
const fetchCategories = () => fetchData('/categories', categories);
const fetchSuppliers = () => fetchData('/suppliers', suppliers);
const fetchWarehouse = () => fetchData('/warehouses', warehouses);
const fetchVariations = () => fetchData('/variations', variations);

const fetchData = async (url, refVar) => {
    try {
        const response = await axios.get(url);
        refVar.value = response.data;
    } catch (error) {
        console.error(`Error fetching data from ${url}:`, error);
    }
};

</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2 ">CREATE NEW PRODUCT</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item"><a :href="route('admin.products')">Product</a></li>
                    <li class="breadcrumb-item active"> Create Product</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Create New Product</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" ref="formRef" @submit.prevent="handleSubmit" novalidate>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="code">Product Code :</label>
                                            <input type="text" class="form-control" id="code"
                                                placeholder="Enter Product Code" v-model="formData.code" required>
                                            <div class="invalid-feedback">
                                                Please Provide Enter Product Code
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name">Product Name :</label>
                                            <input type="text" class="form-control" id="name"
                                                placeholder="Enter Product Name" v-model="formData.name" required>
                                            <div class="invalid-feedback">
                                                Please Provide Enter Product Name
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-">
                                        <div class="form-group">
                                            <label for="name">Product Category :</label>
                                            <select class="form-select" aria-label="Default select example"
                                                v-model="formData.category_id" required>
                                                <option value="" disabled>Select Category</option>
                                                <option v-for="category in categories" :key="category.id"
                                                    :value="category.id">
                                                    {{ category.name }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please Provide Select Product Category
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name">Product Brand :</label>
                                            <select class=" form-select " aria-label="Default select example"
                                                v-model="formData.brand_id" required>
                                                <option value="" disabled>Select Brand</option>
                                                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                                    {{ brand.name }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please Provide Select Product Brand
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name">Purchase Status :</label>
                                            <select class="form-select" aria-label="Default select example"
                                                v-model="formData.purchase_show" required>
                                                <option value=0>Select Purchase Status</option>
                                                <option value=2 selected>Not Purchase</option>
                                                <option value=1>Create Purchase</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please Provide Select Purchase Status
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  purchase mt-4" id="purchase_form">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="name">Supplier :</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    v-model="formData.supplier_id">
                                                    <option value="" disabled>Select Supplier</option>
                                                    <option v-for="supplier in suppliers" :key="supplier.id"
                                                        :value="supplier.id">
                                                        <span>{{ supplier.first_name }} &nbsp;{{ supplier.last_name
                                                            }}</span>
                                                    </option>
                                                </select>
                                                <div v-if="errors.supplier_id" class="invalid-feedback">
                                                    {{ errors.supplier_id }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="name">Warehouse :</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    v-model="formData.warehouse_id" >
                                                    <option value="" disabled>Select Warehouse</option>
                                                    <option v-for="warehouse in warehouses" :key="warehouse.id"
                                                        :value="warehouse.id " >
                                                        {{ warehouse.name }}
                                                    </option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please Provide Select Warehouse
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="quantity">Purchase Code :</label>
                                                <input type="text" class="form-control" id="purchase_code"
                                                    placeholder="Enter Product purchase code"
                                                    v-model="formData.purchase_code">
                                                <div class="invalid-feedback">
                                                    Please Provide Enter Product Quantity
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="name">Payment Type :</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    v-model="formData.payment_status">
                                                    <option value="" disabled>Select Payment Type</option>
                                                    <option value=1>Cash</option>
                                                    <option value=3>Credit</option>
                                                </select>
                                                <div v-if="errors.payment_status" class="invalid-feedback">
                                                    {{ errors.payment_status }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="name">Order Status :</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    v-model="formData.order_status">
                                                    <option value="" disabled>Select Order Status</option>
                                                    <option value=1>Pending</option>
                                                    <option value=2>Delivered</option>
                                                </select>
                                                <div v-if="errors.order_status" class="invalid-feedback">
                                                    {{ errors.order_status }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="quantity">Product Quantity :</label>
                                                <input type="number" class="form-control" id="quantity"
                                                    placeholder="Enter Product Quantity" v-model="formData.quantity">
                                                    <div v-if="errors.quantity" class="invalid-feedback">
                                                        {{ errors.quantity }}
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="amount">Paid Amount</label>
                                                <input type="number" class="form-control" id="amount"
                                                    placeholder="Enter Paid Amount (0-100%)"
                                                    v-model="formData.paid_amount">
                                                    <div v-if="errors.paid_amount" class="invalid-feedback">
                                                        {{ errors.paid_amount }}
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card card-row card-bg-light">
                                        <div class="card-body">
                                            <div class="card card-success card-outline">
                                                <div class="card-header">
                                                    <h5 class="card-title fw-bold">Prices (Rs.)</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="cost_prise">Cost Prise</label>
                                                            <input type="number" class="form-control" id="cost_price"
                                                                placeholder="Enter  Cost Price" v-model="formData.cost"
                                                                required>
                                                            <div class="invalid-feedback">
                                                                Please Provide Enter Cost Price
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="prise">Your Prise</label>
                                                            <input type="number" class="form-control" id="price"
                                                                placeholder="Enter  Price" v-model="formData.price"
                                                                required>
                                                            <div class="invalid-feedback">
                                                                Please Provide Enter Price
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="card card-info card-outline">
                                                <div class="card-header">
                                                    <h5 class="card-title fw-bold">Variation</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <select class="form-select"
                                                                aria-label="Default select example"
                                                                v-model="formData.variation_id" required>
                                                                <option value="" disabled>Select Variation</option>
                                                                <option v-for="variation in variations"
                                                                    :key="variation.variation_id"
                                                                    :value="variation.variation_id">
                                                                    {{ variation.variation_name }}
                                                                </option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Please Provide Select Variation
                                                            </div>
                                                        </div>
                                                        <div id="variation_form" style="display: none;">
                                                            <div v-for="(type, index) in variationValues"
                                                                :key="type.type">
                                                                <p>{{ type.type_name }}</p>
                                                                <select class="form-select"
                                                                    aria-label="Default select example"
                                                                    v-model="selectedValues[type.type_name]" required>
                                                                    <option value="" disabled>Select value</option>
                                                                    <option v-for="(value, index) in type.values"
                                                                        :key="value.value_id" :value="value.value_id">
                                                                        {{ value.value_name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <!-- <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <h5 class="card-title fw-bold">Product Image</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-12">
                                                        <section class="bg-diffrent">
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="verify-sub-box">
                                                                            <div class="file-loading">
                                                                                <input id="image" type="file"
                                                                                    accept=".jpg,.gif,.png"
                                                                                    @change="handleFileChange">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
