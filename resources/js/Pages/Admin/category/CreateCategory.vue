<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const formRef = ref(null);
const toast = useToast();

// Form state
const formData = ref({
    category_code: '',
    category_name: '',
});

const errors = ref({});

const handleSubmit = (e) => {
    e.preventDefault();

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }

    axios.post('/subjects', formData.value)
        .then(response => {
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.subject');
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
</script>

<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Category</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item"><a :href="route('admin.category')">Category</a></li>
                    <li class="breadcrumb-item active">Create Category</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Create New Category</h3>
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
                                        <label for="category-name">Category Name :</label>
                                        <input type="text" class="form-control" id="category_name"
                                            placeholder="Enter Category Name" v-model="formData.category_name" required>
                                        <div class="invalid-feedback">
                                            Please provide a category name
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
