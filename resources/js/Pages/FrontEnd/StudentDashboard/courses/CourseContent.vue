<script setup>
import StudentDashboardLayout from '@/Layouts/StudentDashboardLayout.vue';
import { onMounted } from 'vue';


defineProps({
    modulesData: {
        type: Array,
        required: true,
    },
    courseName: {
        type: String,
        required: true,
    },
});

</script>
<template>
    <StudentDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Courses Content</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Course Content</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">{{ courseName }}</h2>
            </div>
        </div>
        <div class="row mb-4" v-for="(module, index) in modulesData" :key="module.id">
            <h4>{{ module.module.module_name }}</h4>
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item" v-for="(subject, index) in module.subjects" :key="index">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#subject-1" aria-expanded="false" aria-controls="flush-collapseOne">
                            <h4>{{ subject.subject.subject_name }}</h4>
                        </button>
                    </h2>
                    <div id="subject-1" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                        data-bs-parent="#accordionFlushExample" v-for="(note, index2) in subject.notes" :key="index2">
                        <div class="accordion-body">
                            <h5 style="display: inline; margin-right: 10px;">{{ note.note_title }}</h5>
                            <a :href="'/storage/' + note.note_url" target="_blank" v-if="note.note_url"
                                style="display: inline;">Download Document</a>
                            <span v-else style="display: inline;">No document available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StudentDashboardLayout>
</template>
