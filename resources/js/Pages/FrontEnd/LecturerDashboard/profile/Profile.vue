<script setup>
import LecturerDashboardLayout from '@/Layouts/LecturerDashboardLayout.vue';
import { onMounted } from 'vue';


</script>
<template>
    <LecturerDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Personal Details</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row ml-3 mt-4">
            <div class="">
                <div class="row">
                    <div class="col-lg-4  d-flex justify-content-start align-items-center info-col">
                        <p class="d-flex align-items-center">Full Name</p>
                    </div>
                    <div class="col-lg-8 info-col">
                        <p>Jayasinghe Arachchige Sadun Rathnayaka</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 d-flex justify-content-start align-items-center ">
                        <p class="d-flex align-items-center">Student No</p>
                    </div>
                    <div class="col-lg-8">
                        <p>NHR-S456</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4  d-flex justify-content-start align-items-center info-col">
                        <p class="d-flex align-items-center">NIC</p>
                    </div>
                    <div class="col-lg-8 info-col">
                        <p>973080121v</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 d-flex justify-content-start align-items-center ">
                        <p class="d-flex align-items-center">Address</p>
                    </div>
                    <div class="col-lg-8">
                        <p>No542, Kiribathgoda</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4  d-flex justify-content-start align-items-center info-col">
                        <p class="d-flex align-items-center ">Employment</p>
                    </div>
                    <div class="col-lg-8 info-col">
                        <p>Student</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4  d-flex justify-content-start align-items-center">
                        <p class="d-flex align-items-center ">Home number</p>
                    </div>
                    <div class="col-lg-8">
                        <p>0114562856</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 d-flex justify-content-start align-items-center info-col">
                        <p class="d-flex align-items-center ">Mobile number</p>
                    </div>
                    <div class="col-lg-8 info-col">
                        <p>0764562856</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4  d-flex justify-content-start align-items-center">
                        <p class="d-flex align-items-center">Email</p>
                    </div>
                    <div class="col-lg-8">
                        <p>sadun456@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </LecturerDashboardLayout>
</template>