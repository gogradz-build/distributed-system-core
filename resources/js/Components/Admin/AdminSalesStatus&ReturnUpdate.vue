<template>
    <h6>Sale id {{ sale_id }}</h6>
    <label for="name">Order Status</label>
    <div class="row">
        <div class="col-lg-6">
            <input type="datetime-local" class="form-control" id="input-date" v-model="date_input"></input>
        </div>
        <div class="col-lg-6">
            <select class="form-select" aria-label="Default select example" v-model="order_status" id="status">
                <option value=0 disabled>Select Order Status</option>
                <option value=1>Start to Print</option>
                <option value=2>Print</option>
                <option value=3>Delivered</option>
                <option value=4>Completed</option>
            </select>
        </div>
    </div>
    <div v-if="sale_return_show" class="salesReturn">
        <p v-if="return_content == false" class="text-danger mt-3">Do you have any returns? then please click the yes button. If you do not have any
            changes please click the update button</p>
        <div class="col-12 mt-4" v-if="return_content">
            <div class="row mb-4">
                <div class="col-12 ">
                    <div class="row d-flex justify-content-start">
                        <div class="col-lg-3 col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><img src="/images/icon/calendar.png"
                                        alt="" width="20px"></span>
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
                        <div class="col-lg-3 col-12">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect02"><img
                                        src="/images/icon/sale create store.svg" alt="" width="20px"></label>
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
                                <label class="input-group-text" for="inputGroupSelect02"><img
                                        src="/images/icon/sale create  ref.svg" alt="" width="20px"></label>
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
                                <label class="input-group-text" for="inputGroupSelect02"><img
                                        src="/images/icon/sales_payments.svg" alt="" width="20px"></label><input type="number"
                                    class="form-control" id="credit_limit" :value="getCreditLimit(shop)" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header card-header-background">
                            <h3 class="card-title">Sales Items</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">

                                <table id="modal-perches-table" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 8%;">Product Code</th>
                                        <th style="width: 20%;">Product name</th>
                                        <th>Price </th>
                                        <th>Discount</th>
                                        <th>Discount </th>
                                        <th style="width: 15%;">Quantity</th>
                                        <th style="width: 15%;">Returned</th>
                                        <th style="width: 40%;">Return</th>
                                        <th>Subtotal </th>
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
                                            {{ (calculateDiscountedPrice(product) * product.return_quantity).toFixed(2)
                                            }}
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
                                                        v-model="product.return_quantity" disabled/>
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
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <div class="row d-flex">
                <div class="col-lg-8">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h5 class="card-title fw-bold">Reason</h5>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" v-model="reason" rows="3"
                                placeholder="Enter reason"></textarea>
                        </div>
                    </div>
                </div>
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
        </div>
        <div v-if="return_content == false" class="col-12 d-flex justify-content-end">
            <button class="btn btn-primary mr-3" @click="showReturnContent(true)">Yes</button>
            <button class="btn btn-success" @click="updateStatus">Update</button>
        </div>
    </div>
    <div v-else class="salesReturn">
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-success" @click="updateStatus">Update</button>
            </div>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import { useToast } from 'vue-toastification';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import { nextTick } from 'vue';
import DataTable from 'datatables.net-bs4';
export default {
    name: 'AdminSalesStatus&ReturnUpdate',
    props: {
        sale_id: {
            type: Number,
            required: true,
        },
        fetchSales: {
            type: Function,
            required: true
        },
        statusId: {
            type: Number,
            required: true,
        },
        close: {
            type: Boolean,

        },

    },
    data() {
        return {
            sale: {},
            order_status: this.statusId,
            sale_return_show: false,
            toast: useToast(),
            date_input: "",
            return_content: false,
            formRef: null,
            search: "",
            todyDate: '',
            products: [],
            sub_total: 0,
            warehouses: [],
            shops: [],
            refs: [],
            selectedProducts: [],
            reason: "",
            table: null,
        };
    },
    computed: {
        shop() {
            return this.sale?.shop_id || null;
        },
        ref1() {
            return this.sale?.ref_id || null;
        },
        warehouse() {
            return this.sale?.warehouse_id || null;
        },
        payment_status() {
            return this.sale?.payment_status || 3;
        },
        totalPrice() {
            return this.selectedProducts.reduce((total, product) => {
                const discountedPrice = this.calculateDiscountedPrice(product);
                return total + discountedPrice * product.return_quantity;
            }, 0);
        }
    },
    watch: {
        sale: {
            handler(newSale) {
                this.selectedProducts = (newSale?.products ?? []).map(product => ({
                    id: product.id,
                    name: product.name,
                    code: product.code,
                    price: product.price,
                    sellCount: product.sellCount,
                    return_quantity: product.return_quantity, // Keep it reactive
                    old_return_quantity: product.old_return_quantity,
                    discount: product.discount,
                    discounted_price: product.discounted_price || 0,
                    subtotal: product.subtotal,
                    oldQuantity: product.oldQuantity,
                }));
                this.reason = this.sale?.reason
            },
            deep: true,
        },
        order_status(newValue, old_value) {
            if (newValue === "4") {
                this.sale_return_show = true;
            } else {
                this.sale_return_show = false;
                this.return_content = false;
            }
        },
        close(newValue) {
            if (newValue) {
                this.resetForm();
            }
        },
        statusId(newValue) {
            this.order_status = newValue;
        }
    },
    methods: {
        async updateStatus() {
            try {
                if (this.order_status == "") {
                    document.getElementById('status').setAttribute("style", "border-color: red !important;");
                    this.toast.error("Sale Status is Required");
                }
                else {
                    document.getElementById('status').setAttribute("style", "border-color: green !important;");
                    document.getElementById('input-date').setAttribute("style", "border-color: green !important;");
                    const response = await axios.post(`/admin/sales/${this.sale_id}`, {
                        order_status: this.order_status,
                        date_input: this.date_input,
                    });
                    if (response.status === 200) {
                        const message = response.data.message || 'Order status updated successfully!';
                        this.toast.success(message);
                        this.fetchSales();
                    } else {
                        alert('Error updating order status.');
                    }
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('Something went wrong!');
            }
        },
        async createReturn() {
            const creditValue = this.getCreditLimit(this.shop);
            const adjustedTotalPrice = this.totalPrice - (this.sale?.receives_amount || 0);
            
            const salesReturnData = {
                warehouse_id: warehouse.value,
                products: this.selectedProducts.map(product => ({
                    product_id: product.id,
                    price: product.price,
                    return_quantity: product.return_quantity + product.old_return_quantity,
                    discount: product.discount ?? 0,
                    subtotal: this.calculateDiscountedPrice(product) * product.quantity
                })),
                total_price: this.totalPrice,
                received_amount: this.sub_total,
                payment_status: +this.payment_status,
                shop_id: this.shop,
                receives_amount: this.sub_total,
                ref_id: this.ref1,
                reason: this.reason
            };

            axios.put(`/saleReturns/${this.sale.id}`, salesReturnData)
                .then(response => {
                    this.updateStatus();
                    if (response?.data?.message) {
                        this.toast.warning(response.data.message);
                    } else if (response?.data?.error) {
                        this.toast.error(response.data.error);
                    }
                })
                .catch(error => {
                    if (error.response?.data?.errors) {
                        console.log(error.response?.data?.errors);
                        const errorMessages = Object.values(error.response.data.errors || {}).flat();
                        errorMessages.forEach(message => {
                            this.toast.error(message);
                        });
                    } else if (error.response?.data?.message) {
                        this.toast.error(error.response.data.message);
                    } else {
                        this.toast.error('An unexpected error occurred.');
                    }
                    console.log(error);
                });
        },
        setTodayDate() {
            const now = new Date();
            const offset = 5.5 * 60 * 60 * 1000;
            const sriLankaTime = new Date(now.getTime() + offset).toISOString().slice(0, 16);
            this.date_input = sriLankaTime;
        },
        resetForm() {
            this.setTodayDate();

        },
        async showReturnContent(content) {
            await Promise.all([this.fetchSale(), this.fetchWarehouse(), this.fetchShop(), this.fetchRfs()]);
            this.initDataTable();
            this.return_content = content;
        },
        async fetchWarehouse() {
            try {
                const response = await axios.get('/warehouses');
                this.warehouses = response.data;
            } catch (error) {
                console.error('Error fetching courses:', error);
            }
        },
        async fetchShop() {
            try {
                const response = await axios.get('/shops');
                this.shops = response.data;
            } catch (error) {
                console.error('Error fetching courses:', error);
            }
        },
        async fetchSale() {
            try {
                const response = await axios.get(`/admin/sale/modal-return/${this.sale_id}`);
                this.sale = response.data.sale;
            } catch (error) {
                console.error('Error fetching sale:', error);
            }
        },
        getCreditLimit(refId) {
            console.log("credit limit" + refId);
            const ref = this.shops.find(r => r.id === refId);
            if (ref) {
                return ref.credit_limit - ref.credit;
            }
            return 0;
        },
        adjustQuantity(productId, adjustment) {
            const product = this.selectedProducts.find((p) => p.id === productId);
            if (product) {
                product.return_quantity = Math.max(-product.old_return_quantity, product.return_quantity + adjustment);
                product.return_quantity = Math.min(product.return_quantity, product.sellCount - product.old_return_quantity);
            }
        },
        validateDiscount(product) {
            if (product.discount < 0) {
                product.discount = 0;
            } else if (product.discount > 100) {
                product.discount = 100;
            }
        },
        calculateDiscountedPrice(product) {
            const discount = product.discount ?? 0;
            const discountAmount = (product.price * discount) / 100;
            return product.price - discountAmount;
        },
        async fetchRfs() {
            try {
                const response = await axios.get('/refs');
                this.refs = response.data;
                console.log(response);
            } catch (error) {
                console.error('Error fetching courses:', error);
            }
        },
        initDataTable() {
            $(document).ready(function () {
                let table = $('#modal-perches-table').DataTable();
                console.log("tabel " + table);
            });
            nextTick(() => {
                if ($.fn.DataTable.isDataTable('#modal-perches-table')) {
                    console.log("if");
                    $('#modal-perches-table').DataTable().destroy();
                }
                $('#modal-perches-table').DataTable({
                    responsive: true,
                    scrollX: true,
                    scrollY: '',
                    scrollCollapse: true,
                    paging: true,
                });
            });
        },
    },
    mounted() {
        const now = new Date();
        const offset = 5.5 * 60 * 60 * 1000;
        const sriLankaTime = new Date(now.getTime() + offset).toISOString().slice(0, 16);
        this.todyDate = this.date_input = sriLankaTime;

    },
}
</script>
<style>

</style>