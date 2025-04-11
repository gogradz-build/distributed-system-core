<script setup>
import LecturerDashboardLayout from '@/Layouts/LecturerDashboardLayout.vue';
import { onMounted } from 'vue';
import AssignmentCreate from '@/Components/FrontEnd/LecturerDashboard/AssignmentCreate.vue';
import AssignmentMarks from '@/Components/FrontEnd/LecturerDashboard/AssignmentMarks.vue';
import AssignmentReport from '@/Components/FrontEnd/LecturerDashboard/AssignmentReport.vue';
import { ref } from 'vue';

const showCreate = ref(true);
const showMarks = ref(false);
const showReport = ref(false);

const props = defineProps({
    schedule_id: String,
    subject_id: String,
    subject_name: String,
});

const showComponents = (componentName) => {
    if ((componentName == 'create')) {
        showMarks.value = false;
        showReport.value = false;
        showCreate.value = true;

    }
    if ((componentName == 'marks')) {
        showCreate.value = false;
        showReport.value = false;
        showMarks.value = true;
    }
    if ((componentName == 'report')) {
        showCreate.value = false;
        showMarks.value = false;
        showReport.value = true;
    }
};

</script>

<template>
    <LecturerDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Subjects</h1><span>{{ subject_name }}</span>
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
                <h2 class="text-center">Assignment Panel</h2>
            </div>
        </div>
        <div class="row">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link " :class="{ active: showCreate }" aria-current="page" href="#"
                        @click="showComponents('create')">Create</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: showMarks }" href="#"
                        @click="showComponents('marks')">Marks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: showReport }" href="#"
                        @click="showComponents('report')">Report</a>
                </li>

            </ul>
        </div>
        <div v-if="showCreate">
            <AssignmentCreate :schedule_id="schedule_id" :subject_id="subject_id"></AssignmentCreate>
        </div>
        <div v-if="showMarks">
            <AssignmentMarks :schedule_id="schedule_id" :subject_id="subject_id"></AssignmentMarks>
        </div>
        <div v-if="showReport">
            <AssignmentReport :schedule_id="schedule_id" :subject_id="subject_id"></AssignmentReport>
        </div>
    </LecturerDashboardLayout>
</template>
