<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
const toast = useToast();

const props = defineProps({
    data: Array
});

const formData = ref({
    order_status: props.data.order_status,
    payment_type: 1,
    received_amount: props.data.received_amount,
    total_price: props.data.total_price,
    amount: '',
    bank_name: '',
    cheque_number: '',
    expire_date: '',

});

const formErrors = ref({
    bank_name: '',
    cheque_number: '',
    expire_date: '',
});

const showChequeNameField = ref(false);
const formRef = ref(null);

const handleSubmit = (e) => {
    e.preventDefault();

    if (formData.value.payment_type === '2') {
        let isValid = true;

        if (!formData.value.bank_name) {
            formErrors.value.bank_name = 'Please provide a bank name.';
            isValid = false;
        }

        if (!formData.value.cheque_number) {
            formErrors.value.cheque_number = 'Please provide a cheque number.';
            isValid = false;
        }

        if (!formData.value.expire_date) {
            formErrors.value.expire_date = 'Please provide an expiry date.';
            isValid = false;
        }

        if (!isValid) {
            return;
        }
    }

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }
    axios.put(route('shop.payment.products', { id: props.data.id }), formData.value)
        .then(response => {
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.payment.sales');
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

onMounted(() => {
    const formElement = document.getElementById('cheque_form');
    if (formElement) {
        formElement.style.display = 'none';
    }
});

watch(() => formData.value.payment_type, (newVal) => {
    console.log(newVal);
    const formElement = document.getElementById('cheque_form');
    if (newVal == 2) {
        formElement.style.display = 'flex ';
    } else {
        formElement.style.display = 'none ';
    }
});

</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">UPDATE SALES PAYMENTS</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item"><a :href="route('admin.payment.sales')">Sales Payments</a></li>
                    <li class="breadcrumb-item active">Update Sales Payment</li>
                </ol>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="mt-3">NAME : {{ data.shop.name }}</h3>
                <p>Phone Number : {{ data.shop.contact }}</p>
                <p>Address : {{ data.shop.address }}</p>
                <p>credit limit : Rs:- {{ data.shop.credit_limit }}</p>
                <p>credit amount : Rs:- {{ data.shop.credit }} </p>
                <!-- <button type="button" class="btn btn-secondary  mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Warehouse Report</button> -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header  card-header-background">
                        <h3 class="card-title">Create Sale Payment</h3>
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
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name">Order Status :</label>
                                        <select class="form-select" aria-label="Default select example"
                                            v-model="formData.order_status">
                                            <option value=1>Start to Print</option>
                                            <option value=2>Print</option>
                                            <option value=3>Delivered</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name">Payment Type :</label>
                                        <select class="form-select" aria-label="Default select example"
                                            v-model="formData.payment_type">
                                            <option value=1>Cash</option>
                                            <option value=2>Cheque</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row  purchase mt-4" id="cheque_form">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="cheque-name">Bank Name :</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Cheque Name" v-model="formData.bank_name">
                                        <div v-if="formErrors.bank_name" class="invalid-feedback"
                                            style="display: block;">
                                            {{ formErrors.bank_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="cheque-name">Cheque Number :</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Cheque Name" v-model="formData.cheque_number">
                                        <div v-if="formErrors.cheque_number" class="invalid-feedback"
                                            style="display: block;">
                                            {{ formErrors.cheque_number }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="cheque-name">Expire Date :</label>
                                        <input type="date" class="form-control" id="name"
                                            placeholder="Enter Cheque Name" v-model="formData.expire_date">
                                        <div v-if="formErrors.expire_date" class="invalid-feedback"
                                            style="display: block;">
                                            {{ formErrors.expire_date }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="cheque-name">Total Price :</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Cheque Name" v-model="formData.total_price" disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="cheque-name">Received Amount :</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Cheque Name" v-model="formData.received_amount" disabled>
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
                            <div class="col-lg-6">
                                <label for="warehouse-name"></label>
                                <button type="submit " class="btn btn-primary mt-4 category-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
