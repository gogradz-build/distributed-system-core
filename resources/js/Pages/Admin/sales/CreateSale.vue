<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, computed, ref, watch } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const formRef = ref(null);
const toast = useToast();
const sellCount = ref(0);
const search = ref('');
const searchShop = ref('');
const todyDate = ref('');
const payment_type = ref(3);
const products = ref([]);
const warehouse = ref(0);
const shop = ref(0);
const ref1 = ref(0);
const selectedProducts = ref([]);
const received_amount = ref(0);
const bank_name = ref('');
const expire_date = ref('');
const cheque_number = ref('');
const order_status = ref(1);
const searched_shops = ref([]);
const isRefUser = ref(false);
// Form state
const perches_show = ref('');
const errors = ref({});

const props = defineProps({
    ref_data: Object,
});

const createSale = async () => {

    const creditValue = getCreditLimit(shop.value);

    const purchaseData = {
        warehouse_id: warehouse.value,
        products: selectedProducts.value.map(product => ({
            product_id: product.id,
            quantity: +product.sellCount,
            price: product.price,
            discount: product.discount ?? 0,
            discounted_price: calculateDiscountedPrice(product),
            subtotal: calculateDiscountedPrice(product) * product.quantity
        })),
        total_price: totalPrice.value,
        received_amount: received_amount.value,
        payment_type: +payment_type.value,
        todyDate: todyDate.value,
        shop_id: shop.value,
        ref_id: ref1.value,
        credit: creditValue,
        bank_name: bank_name.value,
        cheque_number: cheque_number.value,
        expire_date: expire_date.value,
        order_status: order_status.value,
    };

    axios.post('/sales', purchaseData)
        .then(response => {
            console.log(response)
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.Sales');
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

const addQuantity = () => {
    quantity.value += 1;
}
const reduceQuantity = () => {
    if (sellCount.value > 0) {
        sellCount.value -= 1;
    }
}
const searchingShop = async (searchKey, warehouse) => {
    if (warehouse.value == 0) {
        toast.warning("Please Select Warehouse");
        document.getElementById('warehouse').style.border = '1px solid red';
    } else {
        document.getElementById('warehouse').style.border = '1px solid black !important';
        try {
            const response = await axios.get(`/admin/search/shop/${searchKey}`);
            searched_shops.value = response.data;
        } catch (error) {
            if (error.response && error.response.status === 404) {
                console.log(error.response.data.message);
            } else {
                console.error("An error occurred:", error);
            }
        }
    }
}
const searchProduct = async (searchKey, warehouse) => {
    if (warehouse.value == 0) {
        toast.warning("Please Select Warehouse");
        document.getElementById('warehouse').style.border = '1px solid red';
    } else {
        document.getElementById('warehouse').style.border = '1px solid black !important';
        try {
            const response = await axios.get(`/admin/search/${searchKey}/${warehouse}`);
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
}
function category() {
    if (warehouse.value == 0) {
        toast.warning("Please Select Warehouse");
        document.getElementById('warehouse').style.border = '1px solid red';
    }
    else {
        document.getElementById('warehouse').style.border = '1px solid red';
        const offcanvasElement = document.getElementById('offcanvasScrolling');
        const offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }

}
onMounted(() => {
    fetchWarehouse();
    fetchShop();
    fetchRfs();
    const today = new Date().toISOString().split('T')[0];
    todyDate.value = today;

    if (props.ref_data) {
        ref1.value = props.ref_data.id;
        isRefUser.value = true;
    }

    nextTick(() => {
        initializeDataTable();

    });
});
function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#perchesTable')) {
            $('#perchesTable').DataTable().destroy();
        }
        $('#perchesTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollCollapse: true,
            paging: true,
        });
        const successMessage = localStorage.getItem('successMessage');
        if (successMessage) {
            toast.success(successMessage);
            localStorage.removeItem('successMessage');
        }
    });
}
watch(search, () => {
    searchProduct(search.value, warehouse.value);
});


watch(payment_type, () => {
    console.log("ss" + payment_type.value);

    if (payment_type.value == 2) {
        document.getElementById('cheque').style.display = 'block';
    }
    else {
        document.getElementById('cheque').style.display = 'none';
    }
});
const warehouses = ref([]);

const fetchWarehouse = async () => {
    try {
        const response = await axios.get('/warehouses');
        warehouses.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

const shops = ref([]);

const fetchShop = async () => {
    try {
        const response = await axios.get('/shops');
        shops.value = response.data;

    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

const getCreditLimit = (refId) => {
    const ref = shops.value.find(r => r.id === refId);
    if (ref) {
        return ref.credit_limit - ref.credit;
    }
    return 0;
};


// Add product to the table
const addProductToTable = (product) => {
    // Check if the product is already added
    const existingProduct = selectedProducts.value.find(
        (p) => p.id === product.id
    );

    if (!existingProduct) {
        // Add the selected product
        selectedProducts.value.push({ ...product, sellCount: 1 });
    } else {
        // Increment quantity if already exists
        existingProduct.sellCount += 1;
    }
    search.value = "";
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
        product.sellCount = Math.max(1, product.sellCount + adjustment); // Ensure minimum quantity of 1
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
    const discountAmount = (product.price * discount) / 100;
    return product.price - discountAmount;
};


// Compute total price dynamically
const totalPrice = computed(() => {
    return selectedProducts.value.reduce((total, product) => {
        const discountedPrice = calculateDiscountedPrice(product);
        return total + discountedPrice * product.sellCount;
    }, 0);
});

const refs = ref([]);

const fetchRfs = async () => {
    try {
        const response = await axios.get('/refs');
        refs.value = response.data;
        console.log(response);

    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

const categories = ref([]);

const fetchCategories = async () => {
    try {
        const response = await axios.get('/categories');
        categories.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

watch(searchShop, () => {
    searchingShop(searchShop.value, warehouse.value);
});

function addShopId(id, shopName) {
    shop.value = id;
    searchShop.value = shopName;
    document.getElementById('shopResult').style.display = 'none';
}
</script>

<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h5 class="mt-2">CREATE SALES</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active"><a :href="route('admin.Sales')">Sales</a></li>
                    <li class="breadcrumb-item active">Create Sales</li>
                </ol>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 ">
                <div class="row d-flex justify-content-start">
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><img src="/images/icon/calendar.svg" alt=""
                                    width="20px"></span>
                            <input type="date" class="form-control" placeholder="Username" aria-label="Username"
                                v-model="todyDate" aria-describedby="basic-addon1" id="dateInput" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01"><img
                                    src="/images/icon/sale create warehouse.svg" alt="" width="20px"></label>
                            <select class="form-select" id="warehouse" v-model="warehouse">
                                <option selected disabled value="0">Select WareHouse</option>
                                <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">
                                    {{ warehouse.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02"><img
                                    src="/images/icon/sale create store.svg" alt="" width="20px"></label>
                            <select class="form-select" id="shop" v-model="shop">
                                <option selected disabled value="0">Select Shop</option>
                                <option v-for="shop in shops" :key="shop.id" :value="shop.id">
                                    {{ shop.name }}
                                </option>
                            </select>
                        </div>
                    </div> -->
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02"><img
                                    src="/images/icon/sale create  ref.svg" alt="" width="20px"></label>
                            <select class="form-select" id="ref" v-model="ref1" :disabled="isRefUser">
                                <option selected disabled value="0">Select Ref</option>
                                <option v-for="ref in refs" :key="ref.id" :value="ref.id">
                                    {{ ref.first_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02"><img
                                    src="/images/icon/sales_payments.svg" alt="" width="20px"></label>
                            <input type="number" class="form-control" id="credit_limit" :value="getCreditLimit(shop)"
                                disabled>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02"><img src="/images/icon/search.svg"
                                    alt="" width="20px"></label>
                            <input class="form-control" type="text" placeholder="Search Shops..."
                                aria-label="default input example" v-model="searchShop">
                        </div>
                        <div v-if="searchShop && searched_shops.length > 0" class="search-result" id="shopResult">
                            <ol class="shop-list">
                                <li v-for="searched_shop in searched_shops" :key="searched_shop.id" class="product-item"
                                    style="cursor: pointer" @click="addShopId(searched_shop.id, searched_shop.name)">
                                    {{ searched_shop.name }} - {{ searched_shop.address }}
                                </li>
                            </ol>
                        </div>
                        <div v-else-if="searched_shop && searched_shops.length === 0" class="search-result">
                            <p>No shops found...</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02"><img src="/images/icon/search.svg"
                                    alt="" width="20px"></label>
                            <input class="form-control" type="text" placeholder="Search Products..."
                                aria-label="default input example" v-model="search">
                        </div>
                        <div v-if="search && products.length > 0" class="search-result" id="search_result">
                            <ol class="product-list">
                                <li v-for="product in products" :key="product.id" @click="addProductToTable(product)"
                                    class="product-item" style="cursor: pointer">
                                    {{ product.name }} ({{ product.code }}) - Rs {{ product.price }}
                                </li>
                            </ol>
                        </div>
                        <div v-else-if="search && products.length === 0" class="search-result">
                            <p>No products found...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-12  mt-4">
                <button type="button" class="btn btn-outline-danger m-2" aria-controls="offcanvasScrolling"
                    @click="category">
                    Category1
                </button>
            </div>
        </div> -->

        <div class="row " style="margin-top: 80px">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Sales Items</h3>
                    </div>
                    <div class="card-body">
                        <!-- Add ID to the table -->
                        <table id="perchesTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Product Code</th>
                                    <th style="width: 25%;">Product Name</th>
                                    <th style="width: 10%;">Price (Rs.)</th>
                                    <th style="width: 10%;">Discount (%)</th>
                                    <th style="width: 10%;">Discount (Rs.)</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 10%;">Subtotal (Rs.)</th>
                                    <th style="text-align: center; width: 8%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in selectedProducts" :key="product.id">
                                    <td>{{ product.code }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>
                                        <input type="number" class="form-control" v-model="product.price" min="0" />
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" v-model="product.discount" min="0"
                                            max="100" @input="validateDiscount(product)" placeholder="0" />
                                    </td>
                                    <td>
                                        {{ (product.price * product.sellCount).toFixed(2)
                                            - (calculateDiscountedPrice(product) * product.sellCount).toFixed(2) }}
                                    </td>
                                    <td>
                                        <div class="row d-flex">
                                            <div class="input-group quantity-group">
                                                <button class="btn btn-primary" type="button"
                                                    @click="adjustQuantity(product.id, -1)">
                                                    -
                                                </button>
                                                <input class="form-control" aria-label="Quantity input"
                                                    v-model="product.sellCount" min="1" />
                                                <button class="btn btn-primary" type="button"
                                                    @click="adjustQuantity(product.id, 1)">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ (calculateDiscountedPrice(product) * product.sellCount).toFixed(2) }}
                                    </td>
                                    <td class="justify-content-center align-items-center">
                                        <a @click="removeProductFromTable(product.id)" style="cursor: pointer;">
                                            <img src="/images/icon/trash-bin.png" alt="" width="25px" height="25px"
                                                class="table-action-icon">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- /.card-body -->
                </div>

            </div>
        </div>
        <div class="row d-flex justify-content-end">
            <!-- <div class="col-lg-4" id="cheque">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">Cheque Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Bank Name :</label>
                                <input type="text" class="form-control" id="bank_name" placeholder="Enter Product Name"
                                    v-model="bank_name">
                                <div class="invalid-feedback">
                                    Please Provide Enter Product Name
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Cheque Number :</label>
                                <input type="text" class="form-control" id="cheque_number"
                                    placeholder="Enter Product Name" v-model="cheque_number">
                                <div class="invalid-feedback">
                                    Please Provide Enter Product Name
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Expire Date :</label>
                                <input type="date" class="form-control" id="expire_date"
                                    placeholder="Enter Product Name" v-model="expire_date">
                                <div class="invalid-feedback">
                                    Please Provide Enter Product Name
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-lg-4">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon2">Total: Rs {{ totalPrice.toFixed(2)
                                    }}</span>
                                <input type="number" class="form-control" placeholder="0.00"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                                    v-model="received_amount">
                            </div>
                            <label for="name">Payment Type</label>
                            <select class="form-select" aria-label="Default select example" v-model="payment_type">
                                <option value="" disabled>Select Payment Type</option>
                                <!-- <option value=1>Cash</option>
                                <option value=2>Cheque</option> -->
                                <option value=3>Credit</option>
                            </select>
                            <label for="name">Order Status</label>
                            <select class="form-select" aria-label="Default select example" v-model="order_status">
                                <option value="" disabled>Select Order Status</option>
                                <option value=1>Start to Print</option>
                                <option value=2>Print</option>
                                <option value=3>Delivered</option>
                                <option value=4>Completed</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end">
                                <button class="btn btn-success mt-3" @click="createSale">Create Sale</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
            id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Category Name</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row">
                    <div class="container result_container">
                        <input class="form-control" type="text" placeholder="Search Products..."
                            aria-label="default input example">
                        <div class="search-result">
                            <p>456 product</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        Product Name (Code)
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
