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
const sub_total = ref(0);
const search = ref('');
const supplier = ref('');
const warehouse = ref('');
const payment_status = ref('');
const order_status = ref('');
const purchase_code = ref('');
const products = ref('');
const selectedProducts = ref([]);
// Form state
const formData = ref({
    code: '',
    name: '',
    cost: '',
    image: '',
    description: '',
    variation_id: '',
    category_id: '',

});
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        formData.value.image = file;
        console.log('Selected file:', file);
    }
};
const addQuantity = () => {

    quantity.value += 1;
}
const reduceQuantity = () => {
    if (quantity.value > 0) {
        quantity.value -= 1;
    }
}
const savePurchase = async () => {
    const purchaseData = {
        supplier_id: supplier.value,
        warehouse_id: warehouse.value,
        products: selectedProducts.value.map(product => ({
            product_id: product.id,
            quantity: +product.quantity,
            cost: product.cost,
            discount: product.discount ?? 0,
            discounted_price: calculateDiscountedPrice(product),
            paid_amount: calculateDiscountedPrice(product) * product.quantity
        })),
        total_price: totalPrice.value,
        received_amount: sub_total.value,
        payment_status: +payment_status.value,
        purchase_code: purchase_code.value,
        order_status: order_status.value,
    };

    axios.post('/purchases', purchaseData)
        .then(response => {
            console.log(response)
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.perches');
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

const searchProduct = async (searchKey) => {
    try {
        const response = await axios.get(`/admin/search/${searchKey}`);

        products.value = response.data;

        if (Array.isArray(products)) {
            console.log("Products found:", products);
        } else {
            console.log("Single product found:", products);
        }
    } catch (error) {
        if (error.response && error.response.status === 404) {
            console.log(error.response.data.message);
        } else {
            console.error("An error occurred:", error);
        }
    }

}

onMounted(() => {
    fetchWarehouse();
    fetchSuppliers();
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

watch(search, () => {
    searchProduct(search.value);
});

const suppliers = ref([]);
const fetchSuppliers = async () => {
    try {
        const response = await axios.get('/suppliers');
        suppliers.value = response.data;
        console.log(response);

    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

const warehouses = ref([]);
const fetchWarehouse = async () => {
    try {
        const response = await axios.get('/warehouses');
        warehouses.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

// Add product to the table
const addProductToTable = (product) => {
    // Check if the product is already added
    const existingProduct = selectedProducts.value.find(
        (p) => p.id === product.id
    );

    if (!existingProduct) {
        // Add the selected product
        selectedProducts.value.push({ ...product, quantity: 1 });
    } else {
        // Increment quantity if already exists
        existingProduct.quantity += 1;
    }
    search.value= "";
    document.getElementById("search_result").style.display = "none";
};

// Remove product from the table
const removeProductFromTable = (productId) => {
    selectedProducts.value = selectedProducts.value.filter(
        (product) => product.id !== productId
    );
};

// Adjust quantity
const adjustQuantity = (productId, adjustment) => {
    const product = selectedProducts.value.find((p) => p.id === productId);
    if (product) {
        product.quantity = Math.max(1, product.quantity + adjustment); // Ensure minimum quantity of 1
    }
};

// Validate discount
const validateDiscount = (product) => {
    if (product.discount < 0) {
        product.discount = 0;
    } else if (product.discount > 100) {
        product.discount = 100;
    }
};

// Calculate discounted price, defaulting to 0 if discount is null or falsy
const calculateDiscountedPrice = (product) => {
    const discount = product.discount ?? 0;
    const discountAmount = (product.cost * discount) / 100;
    return product.cost - discountAmount;
};


// Compute total price dynamically
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
                <h4 class="mt-2">CREATE PURCHASE</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active"><a :href="route('admin.perches')">Purchase</a></li>
                    <li class="breadcrumb-item active">Create Purchase</li>
                </ol>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 ">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><img src="/images/icon/calendar.svg" alt=""
                                    width="20px"></span>
                            <input type="date" class="form-control" placeholder="Username" aria-label="Username"
                                aria-describedby="basic-addon1" id="dateInput" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01"><img src="/images/icon/purchase supplier.svg"
                                    alt="" width="20px"></label>
                            <select class="form-select" id="inputGroupSelect01" v-model="supplier">
                                <option value="" disabled>Select Supplier</option>
                                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                    <span>{{ supplier.first_name }} &nbsp;{{ supplier.last_name
                                        }}</span>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01"><img src="/images/icon/sale create warehouse.svg"
                                    alt="" width="20px"></label>
                            <select class="form-select" id="inputGroupSelect01" v-model="warehouse">
                                <option value="" disabled>Select Warehouse</option>
                                <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">
                                    {{ warehouse.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="container result_container">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupSelect01"><img src="/images/icon/search.svg"
                        alt="" width="20px"></label>
                    <input class="form-control" type="text" placeholder="Search Products..."
                    aria-label="default input example" v-model="search" id="search-input">
                </div>
                <div v-if="search && products.length > 0" class="search-result" id="search_result">
                    <ol class="product-list">
                        <li v-for="product in products" :key="product.id" @click="addProductToTable(product)"
                            class="product-item" style="cursor: pointer">
                            {{ product.name }} ({{ product.code }}) - Rs {{ product.cost }}
                        </li>
                    </ol>
                </div>
                <div v-else-if="search && products.length === 0" class="search-result">
                    <p>No products found...</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Purchase Details</h3>
                    </div>
                    <div class="card-body">
                        <table id="perchesTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Product Code</th>
                                    <th style="width: 25%;">Product name</th>
                                    <th style="width: 10%;">Price (Rs)</th>
                                    <th style="width: 10%;">Discount (%)</th>
                                    <th style="width: 10%;">Discount (Rs)</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 10%;">Subtotal (Rs)</th>
                                    <th style="text-align: center; width: 8%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in selectedProducts" :key="product.id">
                                    <td>{{ product.code }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.cost }}</td>
                                    <td>
                                        <input type="number" class="form-control" v-model="product.discount" min="0"
                                            max="100" @input="validateDiscount(product)" placeholder="0" />
                                    </td>
                                    <td>
                                        {{ (product.discount / 100 * (product.quantity * product.cost)).toFixed(2) }}

                                    </td>
                                    <td>
                                        <div class="row d-flex">
                                            <div class="input-group quantity-group">
                                                <button class="btn btn-primary" type="button"
                                                    @click="adjustQuantity(product.id, -1)">
                                                    -
                                                </button>
                                                <input class="form-control" aria-label="Quantity input"
                                                    v-model="product.quantity" min="1" />
                                                <button class="btn btn-primary" type="button"
                                                    @click="adjustQuantity(product.id, 1)">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ (calculateDiscountedPrice(product) * product.quantity).toFixed(2) }}</td>
                                    <td class="justify-content-center align-items-center">
                                        <a @click="removeProductFromTable(product.id)" style="cursor: pointer;">
                                            <img src="/images/icon/trash-bin.png" alt="" width="25px" height="25px" class="table-action-icon">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-end">
            <div class="col-lg-4">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">Summery</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="quantity">Purchase Code :</label>
                                <input type="text" class="form-control" id="purchase_code"
                                    placeholder="Enter Product purchase code" v-model="purchase_code">
                                <div class="invalid-feedback">
                                    Please Provide Enter purchase code
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="quantity">Balance :</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon2">Total: Rs {{ totalPrice.toFixed(2)
                                    }}</span>
                                <input type="number" class="form-control" placeholder="0.00"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                                    v-model="sub_total">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Payment Type</label>
                                <select class="form-select" aria-label="Default select example"
                                    v-model="payment_status">
                                    <option value="" disabled>Select Payment Type</option>
                                    <option value=1>Cash</option>
                                    <option value=3>Credit</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Order Status</label>
                                <select class="form-select" aria-label="Default select example" v-model="order_status">
                                    <option value="" disabled>Select Order Status</option>
                                    <option value=1>Pending</option>
                                    <option value=2>Confirmed</option>
                                    <option value=3>Delivered</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-success" @click="savePurchase">Save Purchase</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
