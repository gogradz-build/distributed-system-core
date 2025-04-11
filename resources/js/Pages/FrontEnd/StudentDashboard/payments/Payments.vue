<script setup>
import StudentDashboardLayout from '@/Layouts/StudentDashboardLayout.vue';
import { onMounted, nextTick, ref } from 'vue';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
onMounted(() => {
    nextTick(() => {
        $('#courseTable').DataTable();

    });


});

</script>
<template>
    <StudentDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">My Payments</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">My Payments</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Courses Details</h3>
                    </div>
                    <div class="card-body">
                        <table id="courseTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Course Code</th>
                                    <th style="width: 20%">Course Name</th>
                                    <th style="width: 10%">Date</th>
                                    <th style="width: 10%">Amount (Rs)</th>
                                    <th style="width: 10%">Balance (Rs)</th>
                                    <th style="width: 10%">Full Amount (Rs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>NHRC-456</td>
                                    <td>HRM</td>
                                    <td>12/10/2024</td>
                                    <td>20,000</td>
                                    <td><span class="badge bg-warning text-dark">5,0000</span></td>
                                    <td><span class="badge bg-danger">60,000</span></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card card-row card-default">
                    <div class="card-header " style="background-color: #808080;">
                        <h3 class="card-title " style="color: white;">
                            Upcoming Payment Reminder for Your Courses
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h5 class="card-title">HRM course payment</h5>
                            </div>
                            <div class="card-body">
                                <p>
                                    This is a friendly reminder that your next payment of 20,000 Rs for the
                                    HRM course is due on 12/20/2024. Please ensure that the payment is completed on
                                    time to continue enjoying uninterrupted access to your course materials and
                                    resources.
                                </p>
                            </div>
                        </div>
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h5 class="card-title">ICT Course Payment</h5>
                            </div>
                            <div class="card-body">
                                <p>
                                    This is a friendly reminder that your next payment of 20,000 Rs for the
                                    ICT course is due on 12/30/2024. Please ensure that the payment is completed on
                                    time to continue enjoying uninterrupted access to your course materials and
                                    resources.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StudentDashboardLayout>
</template>