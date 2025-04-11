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
const search = ref('');
const todyDate = ref('');
const products = ref([]);
const sub_total = ref(0);

const props = defineProps({
    sale: {
        type: Object,
        default: () => ({}),
    },
});

const selectedProducts = ref(
    (props.sale?.products ?? []).map(product => ({
        id: product.id,
        name: product.name,
        code: product.code,
        price: product.price,
        sellCount: product.sellCount,
        return_quantity: product.return_quantity,
        old_return_quantity: product.old_return_quantity || 0,
        discount: product.discount,
        discounted_price: product.discounted_price || 0,
        subtotal: product.subtotal,
        oldQuantity: product.oldQuantity,
    }))
);

const shop = ref(props.sale?.shop_id || null);
const ref1 = ref(props.sale?.ref_id || null);
const warehouse = ref(props.sale?.warehouse_id || null);
const order_status = ref(props.sale?.order_status || null);
const reason = ref(props.sale?.reason || null);
const payment_status = ref(props.sale?.payment_status || 3);
const perches_show = ref('');
const errors = ref({});

const createReturn = async () => {

    const creditValue = getCreditLimit(shop.value);
    const adjustedTotalPrice = totalPrice.value - (props.sale?.receives_amount || 0);

    const salesReturnData = {
        warehouse_id: warehouse.value,
        products: selectedProducts.value.map(product => ({
            product_id: product.id,
            price: product.price,
            return_quantity: +product.return_quantity + product.old_return_quantity,
            // old_return_quantity: product.old_return_quantity,
            discount: product.discount ?? 0,
            subtotal: calculateDiscountedPrice(product) * product.quantity
        })),
        total_price: totalPrice.value,
        received_amount: sub_total.value,
        payment_status: +payment_status.value,
        shop_id: shop.value,
        receives_amount: sub_total.value,
        ref_id: ref1.value,
        reason: reason.value
    };

    axios.put(`/saleReturns/${props.sale.id}`, salesReturnData)
        .then(response => {
            console.log(response)
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.sale.return');
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
    if (return_quantity.value >= 0) {
        return_quantity.value -= 1;
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
    searchProduct(search.value, warehouse.value);
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
        selectedProducts.value.push({ ...product, return_quantity: 0 });
    } else {
        // Increment quantity if already exists
        existingProduct.return_quantity += 1;
    }
};

// Remove product from the table
const removeProductFromTable = (productId) => {
    selectedProducts.value = selectedProducts.value.filter(
        (product) => product.id !== productId
    );
};

// Adjust quantity
// const adjustQuantity = (productId, adjustment) => {
//     const product = selectedProducts.value.find((p) => p.id === productId);
//     if (product) {
//         product.return_quantity = Math.max(0, product.return_quantity + adjustment);
//         product.return_quantity = Math.min(product.return_quantity, product.sellCount);
//     }
// };

const adjustQuantity = (productId, adjustment) => {
    const product = selectedProducts.value.find((p) => p.id === productId);
    if (product) {
        product.return_quantity = Math.max(-product.old_return_quantity, product.return_quantity + adjustment);
        product.return_quantity = Math.min(product.return_quantity, product.sellCount - product.old_return_quantity);
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
        return total + discountedPrice * product.return_quantity;
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

</script>

<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">SALE RETURNS</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active"><a :href="route('admin.Sales')">Sales</a></li>
                    <li class="breadcrumb-item active">Sale Returns</li>
                </ol>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 ">
                <div class="row d-flex justify-content-start">
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><img src="/images/icon/calendar.png" alt=""
                                    width="20px"></span>
                            <input type="date" class="form-control" placeholder="Username" aria-label="Username"
                                v-model="todyDate" aria-describedby="basic-addon1" id="dateInput" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01"><img src="/images/icon/sale create warehouse.svg"
                                    alt="" width="20px"></label>
                            <select class="form-select" id="warehouse" v-model="warehouse">
                                <option selected disabled value="0">Select WareHouse</option>
                                <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">
                                    {{ warehouse.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02"><img src="/images/icon/sale create store.svg"
                                    alt="" width="20px"></label>
                            <select class="form-select" id="shop" v-model="shop">
                                <option selected disabled value="0">Select shop</option>
                                <option v-for="shop in shops" :key="shop.id" :value="shop.id">
                                    {{ shop.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02"><img src="/images/icon/sale create  ref.svg"
                                    alt="" width="20px"></label>
                            <select class="form-select" id="ref" v-model="ref1">
                                <option selected disabled value="0">Select Ref</option>
                                <option v-for="ref in refs" :key="ref.id" :value="ref.id">
                                    {{ ref.first_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02"><img src="/images/icon/sales_payments.svg"
                                    alt="" width="20px"></label><input type="number" class="form-control"
                                id="credit_limit" :value="getCreditLimit(shop)" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row " style="margin-top: 40px">
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
                                    <th style="width: 20%;">Product name</th>
                                    <th>Price (Rs)</th>
                                    <th>Discount (%)</th>
                                    <th>Discount (Rs)</th>
                                    <th style="width: 15%;">Quantity</th>
                                    <th style="width: 15%;">Returned</th>
                                    <th style="width: 15%;">Return</th>
                                    <th>Subtotal (Rs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in selectedProducts" :key="product.id">
                                    <td>{{ product.code }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.price }}</td>
                                    <td>
                                        <input type="number" class="form-control" v-model="product.discount" min="0"
                                            max="100" @input="validateDiscount(product)" placeholder="0" />
                                    </td>
                                    <td>
                                        {{ (calculateDiscountedPrice(product) * product.return_quantity).toFixed(2) }}
                                    </td>
                                    <td>
                                        {{ product.sellCount }}
                                    </td>
                                    <td>
                                        {{ product.old_return_quantity }}
                                    </td>
                                    <td>
                                        <div class="row d-flex">
                                            <div class="input-group quantity-group">
                                                <button class="btn btn-primary" type="button"
                                                    @click="adjustQuantity(product.id, -1)">
                                                    -
                                                </button>
                                                <input class="form-control" aria-label="Quantity input"
                                                    v-model="product.return_quantity" />
                                                <button class="btn btn-primary" type="button"
                                                    @click="adjustQuantity(product.id, 1)">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ (product.price * product.return_quantity).toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
        <div class="row d-flex">
            <div class="col-lg-8">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">Reason</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" v-model="reason" rows="3" placeholder="Enter reason"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon2">Total: Rs {{ totalPrice.toFixed(2)
                                }}</span>
                            </div>
                            <label for="payment_status">Payment Type</label>
                            <select class="form-select" v-model="payment_status">
                                <option value="" disabled>Select Payment Status</option>
                                <option value="1">Cash Returned</option>
                                <option value="3">By Credit</option>
                            </select>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <button class="btn btn-success" @click="createReturn">Return Sale</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>
