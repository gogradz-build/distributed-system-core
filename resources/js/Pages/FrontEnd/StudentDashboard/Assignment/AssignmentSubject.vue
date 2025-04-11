<script setup>
import StudentDashboardLayout from '@/Layouts/StudentDashboardLayout.vue';
import { onMounted } from 'vue';

defineProps({
    subjects: Array
});

</script>
<template>
    <StudentDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Subjects</h1><span>Course Name</span>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="route('lecturer.dashboard')">Home</a></li>
                    <li class="breadcrumb-item"><a :href="route('lecturer.course.show')">Courses</a></li>
                    <li class="breadcrumb-item">Subject</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">My Subject</h2>
            </div>
        </div>
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <ul class="list-group list-group-flush" v-for="subject in subjects" :key="subject_id">
                    <a
                        :href="route('student.course.subjects.assignment', { subject_id: subject.subject_id, schedule_id: subject.schedule_id })">
                        <li class="list-group-item">{{ subject.subject_code }} {{ subject.subject_name }} - ({{
                            subject.module_name }})</li>
                    </a>
                </ul>
            </div>

        </div>
    </StudentDashboardLayout>
</template>
