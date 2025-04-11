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

const formData = ref({
    name: '',
    owner_name: '',
    contact: '',
    address: '',
    ref_id: '',
    credit_limit: '',
    email: '',
    telephone_number: '',
});

const handleSubmit = (e) => {
    e.preventDefault();

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }

    axios.post('/shops', formData.value)
        .then(response => {
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.show.shop')
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
    fetchRfs();
    nextTick(() => {

        // const phoneInput = document.getElementById("phone-number");
        // phoneInput.addEventListener("input", (event) => {
        //     event.target.value = event.target.value.replace(/\D/g, "");
        //     if (event.target.value.length > 10) {
        //         event.target.value = event.target.value.slice(0, 10);
        //     }
        // });

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
                <h4 class="mt-2">CREATE SHOP</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item"><a :href="route('admin.show.shop')">Shop</a></li>
                    <li class="breadcrumb-item active">Create Shop</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Shop Details </h3>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Shop Name :</label>
                                        <input type="text" class="form-control" id="shop-name"
                                            placeholder="Enter Shop Name" v-model="formData.name" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Shop Name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="last-name">Owner Name:</label>
                                        <input type="text" class="form-control" id="owner_name"
                                            placeholder="Enter Owner Name" v-model="formData.owner_name" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Owner Name
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="inputEmail4">Email :</label>
                                    <input type="email" class="form-control" id="inputEmail4"
                                        placeholder="Enter Email Address" v-model="formData.email" required>
                                    <div class="invalid-feedback">
                                        Please Provide Enter Valid Email Address
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phone-number">Mobile Number :</label>
                                        <input type="text" class="form-control" id=""
                                            placeholder="Enter Phone Number" v-model="formData.contact" required >
                                        <div class="invalid-feedback">
                                            Please Provide Enter Valid Phone Number
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phone-number">Telephone Number :</label>
                                        <input type="text" class="form-control" id="r"
                                            placeholder="Enter Phone Number" v-model="formData.telephone_number"
                                            required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Valid Phone Number
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="address">Address :</label>
                                        <input type="text" class="form-control" id="address" placeholder="Enter Address"
                                            v-model="formData.address" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Address
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="credit_limit">Select Ref :</label>
                                        <select class="form-select" id="ref" v-model="formData.ref_id">
                                            <option selected disabled value="">Select Ref</option>
                                            <option v-for="ref in refs" :key="ref.id" :value="ref.id">
                                                {{ ref.first_name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="credit_limit">Credit Limit :</label>
                                        <input type="number" class="form-control" id="credit_limit"
                                            placeholder="Enter credit limit" v-model="formData.credit_limit" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Credit Limit
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Create Shop</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
