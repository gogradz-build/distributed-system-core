<script setup>
import LecturerDashboardLayout from '@/Layouts/LecturerDashboardLayout.vue';
import { onMounted, nextTick } from 'vue';
import AssignmentCreate from '@/Components/FrontEnd/LecturerDashboard/AssignmentCreate.vue';
import AssignmentMarks from '@/Components/FrontEnd/LecturerDashboard/AssignmentMarks.vue';
import AssignmentReport from '@/Components/FrontEnd/LecturerDashboard/AssignmentReport.vue';
import { ref } from 'vue'; 
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
onMounted(() => {
   
    nextTick(() => {
    });
    $('#marksTable').DataTable();
});
</script>

<template>
    <LecturerDashboardLayout>
        <div class="row mt-4">
        <form>
            <div class="row mt-4">
                <div class="col-12 col-lg-3">
                    <div class="mb-3">
                        <label for="assignment" class="form-label">Select Your Course</label>
                        <select id="assignment" class="form-select">
                            <option>Course 01</option>
                            <option>Course 02</option>
                            <option>Course 03</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="mb-3">
                        <label for="assignment" class="form-label">Select Your Subjects</label>
                        <select id="assignment" class="form-select">
                            <option>Subject 01</option>
                            <option>Subject 02</option>
                            <option>Subject 03</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-secondary">
                <div class="card-body">
                    <table id="marksTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: ">Student ID</th>
                                <th style="">batch</th>
                                <th style="width: 20%">Exam  Name</th>
                                <th style="width: ">Exam Date</th>
                                <th style="width: ">Marks</th>
                                <th>Save Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>HR456</td>
                                <td>17/18</td>
                                <td>Exam 01</td>
                                <td>18/13/2024</td>
                                <td><input type="number" class="form-control" id="assignmentName" placeholder="Assignment Marks"></td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <a href=""><button class="btn btn-warning">Save</button></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </LecturerDashboardLayout>
</template>