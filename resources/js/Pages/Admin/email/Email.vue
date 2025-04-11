<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, ref } from 'vue';
import { useToast } from 'vue-toastification';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import axios from 'axios';

const toast = useToast();
const formRef = ref(null);

const formData = ref({
    mail_mailer: 'smtp',
    mail_host: '',
    mail_port: '', 
    mail_user_name: '',
    mail_password:'',
    mail_encryption: '',
    mail_from_address: '',
    mail_from_name: '',
});

//Message to show Successful submission
const successMessage = ref("");

const submitForm = async()=>{
    try {
        const response = await axios.post('/admin/email/email-credentials', formData.value);
        successMessage.value = response.data.message;
        toast.success(successMessage.value);
    } catch (error) {
        if (error.response && error.response.status === 422) {
          console.error("Validation errors:", error.response.data.errors);
        } else {
          console.error("Error submitting form:", error);
        }
    }
}

</script>


<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Email Credential</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Email</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <form class="needs-validation" ref="formRef" @submit.prevent="submitForm" novalidate>
                <div class="col-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Email Credential</h3>
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
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first-name">Mail Mailer</label>
                                        <input type="text" class="form-control" id="mail_mailer" placeholder="Mail Mailer"
                                            v-model="formData.mail_mailer" required disabled>
                                        <div class="invalid-feedback">
                                            Please provide a Mail Mailer
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last-name">Mail Host</label>
                                        <input type="text" class="form-control" id="mail_host" placeholder="Mail Host"
                                            v-model="formData.mail_host" required>
                                        <div class="invalid-feedback">
                                            Please provide a Mail Host
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last-name">Mail Port</label>
                                        <input type="text" class="form-control" id="mail_port"
                                            placeholder="Mail Port" v-model="formData.mail_port" required>
                                        <div class="invalid-feedback">
                                            Please provide a Mail Port
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="mail_user_name">Mail User Name</label>
                                    <input type="text" class="form-control" id="mail_user_name" placeholder="Mail User Name"
                                        v-model="formData.mail_user_name" required>
                                    <div class="invalid-feedback">
                                        Please provide the Mail User Name
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="mail_password">Mail Password</label>
                                        <input type="password" class="form-control" id="mail_password"
                                            placeholder="Mail Password"
                                            v-model="formData.mail_password" required >
                                        <div class="invalid-feedback">
                                            Please provide a Mail Password
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="mail_encryption">Mail Encryption</label>
                                    <input type="text" class="form-control" id="mail_encryption"
                                        placeholder="Enter Mail Encryption" v-model="formData.mail_encryption"
                                        required>
                                    <div class="invalid-feedback">
                                        Please provide a Mail Encryption 
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="mail_from_address">Mail From Address</label>
                                        <input type="email" class="form-control" id="mail_from_address" placeholder="Mail From Address"
                                            v-model="formData.mail_from_address" required>
                                        <div class="invalid-feedback">
                                            Please provide the Mail From Address
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="mail_from_name">Mail From Name</label>
                                        <input type="email" class="form-control" id="mail_from_name" placeholder="Mail From Name"
                                            v-model="formData.mail_from_name" required>
                                        <div class="invalid-feedback">
                                            Please provide the Mail From Name
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
