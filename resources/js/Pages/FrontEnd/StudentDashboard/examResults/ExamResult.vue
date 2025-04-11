<script setup>
import StudentDashboardLayout from '@/Layouts/StudentDashboardLayout.vue';
import { onMounted } from 'vue';


</script>
<template>
    <StudentDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Exam Results</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Result</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row ml-3 mt-4">
            <div class="row">
                <div class="col-lg-4">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Select Your Course</option>
                        <option value="1">NHR456</option>
                        <option value="2">NHR457</option>
                        <option value="3">NHR48</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Select Semester</option>
                        <option value="1">01</option>
                        <option value="2">02</option>
                        <option value="3">03</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-primary">
                        Search
                    </button>
                    <button class="btn btn-warning ml-2">
                        <i class="fas fa-solid fa-print assignment-print"
                        style="color: #000000; margin-right: 5px;"></i>
                    </button>
                </div>
                
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-secondary">
                    <!-- <div class="card-header">
                                            <h3 class="card-title">Batch Details</h3>
                                        </div> -->
                    <div class="card-body">
                        <table id="NoteTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Semester</th>
                                    <th style="width: 10%">Subject Code</th>
                                    <th style="width: 30%">Subject Title</th>
                                    <th style="width: 20%">Grade</th>
                                    <th style="width: 20%">Credit</th>
                                    <th style="width: 20%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>NQBHRM1000</td>
                                    <td>Introduction to Management</td>
                                    <td>2</td>
                                    <td>A-</td>
                                    <td>
                                        <span class="badge bg-success">Pass</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>NQBHRM1101</td>
                                    <td>Organizational Behavior</td>
                                    <td>2</td>
                                    <td>B-</td>
                                    <td>
                                        <span class="badge bg-success">Pass</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>NQBHRM 1102</td>
                                    <td>Introduction of Human
                                        Resource management</td>
                                    <td>2</td>
                                    <td>A+</td>
                                    <td>
                                        <span class="badge bg-success">Pass</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>NQHRM 1103</td>
                                    <td>Human Resource Planning</td>
                                    <td>2</td>
                                    <td>F-</td>
                                    <td>
                                        <span class="badge bg-danger">Fail</span>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </StudentDashboardLayout>
</template>