<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, computed, ref, watch } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { usePage } from '@inertiajs/vue3';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const page = usePage()
const toast = useToast();
const searchShop = ref('');
const paymentStatus = ref(1);
const shop = ref('');
const payments = ref('');
const searched_shops = ref([]);
const isShopSelected = ref(false);

onMounted(() => {
    nextTick(() => {
        initializeDataTable();
    });
});

const initializeDataTable = () => {
    $('#perchesTable').DataTable({
        responsive: true,
        scrollX: true,
        scrollY: '',
        scrollCollapse: true,
        paging: true,
    });
};
watch(searchShop, () => {
    searchingShop(searchShop.value);
});

const searchingShop = async (searchKey) => {
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

function addShopId(id, shopName) {
    shop.value = id;
    searchShop.value = shopName;
    searched_shops.value = [];
}

watch(
    [paymentStatus, shop],
    ([newPaymentStatus, newShopId]) => {
        fetchDataTable(newPaymentStatus, newShopId);
    }
);

const fetchDataTable = async (paymentStatus, searchShop) => {

    if (!searchShop) {
        toast.warning('Please select a shop');
    }
    else {
        try {
            const response = await axios.get(route('get.sales.payment.data', { paymentStatus, searchShop }));
            payments.value = response.data;
            console.log(response);
            nextTick(() => {
                $('#perchesTable').DataTable().clear().rows.add(response.data).draw();
            });
        } catch (error) {
            console.error('Error fetching data table:', error);
        }
    }

};
</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">SALES PAYMENTS</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <!-- <li class="breadcrumb-item active"><a :href="route('admin.perches')">Payments</a></li> -->
                    <li class="breadcrumb-item active">Sales Payments</li>
                </ol>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 ">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-3 col-12">
                        <input class="form-control" type="text" placeholder="Search Shop..."
                            aria-label="default input example" v-model="searchShop">
                        <div v-if="searchShop && searched_shops.length > 0" class="search-result" id="shopResult">
                            <ol class="shop-list">
                                <li v-for="searched_shop in searched_shops" :key="searched_shop.id"
                                    class="product-item" style="cursor: pointer"
                                    @click="addShopId(searched_shop.id, searched_shop.name)">
                                    {{ searched_shop.name }} - {{ searched_shop.address }}
                                </li>
                            </ol>
                        </div>
                        <div v-else-if="searched_shop && searched_shops.length === 0" class="search-result">
                            <p>No shops found...</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01"><img
                                    src="/images/icon/sales_payments.svg" alt="" width="20px"></label>
                            <select class="form-select" aria-label="Default select example" v-model="paymentStatus">
                                <option value=1>All</option>
                                <option value=2>Pending</option>
                                <option value=3>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <!-- <p>{{ payments.name }}</p> -->
                <div class="card card-primary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Sales Payment Details</h3>
                    </div>
                    <div class="card-body">
                        <table id="refTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width:">ID</th>
                                    <th style="width:">Invoice ID</th>
                                    <th style="width:">Created At</th>
                                    <th style="width:">Paid Amount</th>
                                    <th style="width:">Credit Amount</th>
                                    <th style="width:">Status</th>
                                    <th style="text-align: center; width: 8%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(payment, index) in payments.sales" :key="ref.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>MH{{ payment.id }}</td>
                                    <td>{{ new Date(payment.created_at).toLocaleDateString() }}</td>
                                    <td>{{ payment.received_amount }}</td>
                                    <td>{{ payment.total_price - payment.received_amount }}</td>
                                    <td>
                                        <span class="badge badge-info" v-if="payment.order_status === '1'">Start to
                                            Print</span>
                                        <span class="badge  badge-warning"
                                            v-else-if="payment.order_status === '2'">Print</span>
                                        <span class="badge  badge-danger"
                                            v-else-if="payment.order_status === '3'">Delivered</span>
                                        <span class="badge  badge-success"
                                            v-else-if="payment.order_status === '4'">Completed</span>
                                    </td>

                                    <td class=" justify-content-center align-items-center">
                                        <!-- <a :href="route('shops', payment.id)"><i
                                                class="fas fa-regular fa-eye mr-4" style="color: #0a7c46;"></i></a> -->
                                        <a :href="route('get.shop.payment.data', payment.id)"><img src="/images/icon/edit.png" alt="" width="25px" height="25px" class="mr-2 table-action-icon"></a>
                                        <!-- <a @click="triggerModal(ref.id, 'Delete Ref')" style="cursor: pointer;">
                                            <i class="fas fa-solid fa-trash mr-4" style="color: #FF0000;"></i>
                                        </a> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
