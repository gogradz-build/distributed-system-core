<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useToast } from 'vue-toastification';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const toast = useToast();
const formRef = ref(null);

const props = defineProps({
    supplier: Array
});

const formData = ref({
    first_name: props.supplier.first_name,
    last_name: props.supplier.last_name,
    phone_number: props.supplier.phone_number,
    phone_number: props.supplier.phone_number,
    address: props.supplier.address,
    register_number: props.supplier.register_number,
    email: props.supplier.email,
    nic_number: props.supplier.nic_number,

});

const handleSubmit = (e) => {
    e.preventDefault();

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }

    axios.put(`/suppliers/${props.supplier.id}`, formData.value)
        .then(response => {
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.showSupplier')
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
    nextTick(() => {

        const phoneInput = document.getElementById("phone-number");
        phoneInput.addEventListener("input", (event) => {
            event.target.value = event.target.value.replace(/\D/g, "");
            if (event.target.value.length > 10) {
                event.target.value = event.target.value.slice(0, 10);
            }
        });

        const nicInput = document.getElementById("nic-number");
        nicInput.addEventListener("input", (event) => {
            const value = event.target.value;
            const sanitizedValue = value.replace(/[^0-9VvXx]/g, "");
            event.target.value = sanitizedValue;
            const isValid = /^\d{9}[VvXx]$|^\d{12}$/.test(sanitizedValue);
            nicInput.setCustomValidity(isValid ? "" : "Invalid NIC number");
        });
    });
});
</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-3">UPDATE SUPPLIER</h4>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">HOME</a></li>
                    <li class="breadcrumb-item"><a :href="route('admin.showSupplier')">SUPPLIER</a></li>
                    <li class="breadcrumb-item active">UPDATE SUPPLIER</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header  card-header-background">
                        <h3 class="card-title">Supplier Details </h3>
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
                                        <label for="first-name">First Name :</label>
                                        <input type="text" class="form-control" id="first-name"
                                            placeholder="Enter First Name" v-model="formData.first_name" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter First Name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last-name">Last Name :</label>
                                        <input type="text" class="form-control" id="last-name"
                                            placeholder="Enter Last Name" v-model="formData.last_name" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Last Name
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last-name">Registration Number :</label>
                                        <input type="text" class="form-control" id="register-number"
                                            placeholder="Enter Registration Number" v-model="formData.register_number"
                                            required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Registration Number
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Email :</label>
                                    <input type="email" class="form-control" id="inputEmail4"
                                        placeholder="Enter Email Address" v-model="formData.email" required>
                                    <div class="invalid-feedback">
                                        Please Provide Enter Valid Email Address
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="phone-number">Phone Number :</label>
                                        <input type="text" class="form-control" id="phone-number"
                                            placeholder="Enter Phone Number" v-model="formData.phone_number" required
                                            pattern="^(07[0-9])\d{7}$"
                                            title="Please enter a valid 10-digit Sri Lankan mobile number starting with 07">
                                        <div class="invalid-feedback">
                                            Please Provide Enter Valid Phone Number
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nic-number">NIC Number :</label>
                                    <input type="text" class="form-control" id="nic-number"
                                        placeholder="Enter NIC Number" v-model="formData.nic_number"
                                        title="Please enter a valid NIC number (e.g., 123456789V or 199012345678)"
                                        required>
                                    <div class="invalid-feedback">
                                        Please Provide Enter Valid NIC Number (e.g: 123456789V or 199012345678)
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="address">Address :</label>
                                        <input type="text" class="form-control" id="address" placeholder="Enter Address"
                                            v-model="formData.address" required>
                                        <div class="invalid-feedback">
                                            Please Provide Address
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
