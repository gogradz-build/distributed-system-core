<script setup>
import StudentDashboardLayout from '@/Layouts/StudentDashboardLayout.vue';
import { onMounted } from 'vue';
onMounted(() => {
    const barCanvas = document.getElementById('barChart').getContext('2d');
    const pieCanvas = document.getElementById('pieChart');

    if (barCanvas) {
        new Chart(barCanvas, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Students',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    data: [65, 59, 80, 81, 56, 55, 40]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    if (pieCanvas) {
        new Chart(pieCanvas, {
            type: 'pie',
            data: {
                labels: ['AAT', 'CIMA', 'NQHRM', "NQIEL"],
                datasets: [{
                    data: [300, 50, 100, 250],
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#f39c12'],
                    hoverBackgroundColor: ['#f56954', '#00a65a', '#f39c12', '#f39c12']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});
</script>
<template>
    <StudentDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Student Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Student Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
     
        <div class="row">
            <div class="col-7">
                <div class="card card-row card-default">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">
                            ASsignment Notifications
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h5 class="card-title">Assignment NHR456 (Advance Software Engineering)</h5>
                                <div class="card-tools">
                                    <a href="#" class="btn btn-tool btn-link">#2</a>
                                    <a href="#" class="btn btn-tool">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>
                                    The purpose of this assignment is to apply advanced software engineering principles
                                    to design and develop a modular e-commerce application. This will demonstrate your
                                    ability to integrate modular architecture, software design patterns, and
                                    collaborative development practices.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="card card-row card-default">
                    <div class="card-header " style="background-color: #808080;">
                        <h3 class="card-title" style="color: white;">
                            Announcements
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h5 class="card-title">Exam Timetable release</h5>
                                <div class="card-tools">
                                    <a href="#" class="btn btn-tool btn-link">#2</a>
                                    <a href="#" class="btn btn-tool">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>
                                    We are pleased to inform you that the official exam timetable for the upcoming
                                    semester examinations has been released.
                                    You can view and download the timetable from:
                                    [[Insert Link or Portal Name]]
                                </p>
                            </div>
                        </div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="card-title">New Academic Programs (Vice Chancellor's)</h5>
                                <div class="card-tools">
                                    <a href="#" class="btn btn-tool btn-link">#2</a>
                                    <a href="#" class="btn btn-tool">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>

                                    Starting next semester, we will be introducing new courses in Artificial
                                    Intelligence, Cybersecurity, and Environmental Studies to meet the growing demand in
                                    these fields.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </StudentDashboardLayout>
</template>