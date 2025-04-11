<script setup>
import StudentDashboardLayout from '@/Layouts/StudentDashboardLayout.vue';
import { onMounted, ref } from 'vue';

const courses = ref([]);

const fetchLecturerCourse = async () => {
    try {
        const response = await axios.get(route('student.courses.assignment'));
        courses.value = response.data
        console.log(response);
    } catch (error) {
        console.error('Error fetching lecturers:', error);
    }
};

onMounted(() => {
    fetchLecturerCourse();
});

</script>
<template>
    <StudentDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">My Courses</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item ">My Courses</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row ml-3 mt-4">
            <div class="col-lg-3" v-for="course in courses" :key="course.code">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" :src="`/storage/${course.image}`" alt="Course image">
                    <div class="card-body">
                        <h5 class="card-title">{{ course.batch_name }}</h5>
                        <h5 class="card-title">{{ course.code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ course.name }}</h5>
                        <p class="card-text" v-html="course.description"></p>
                        <a :href="route('student.course.subjects', { id: course.schedule_id })"
                            class="btn btn-primary mr-3">Subject</a>

                    </div>
                </div>
            </div>
        </div>
    </StudentDashboardLayout>
</template>
