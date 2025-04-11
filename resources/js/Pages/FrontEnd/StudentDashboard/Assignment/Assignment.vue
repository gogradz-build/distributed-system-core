<script setup>
import StudentDashboardLayout from '@/Layouts/StudentDashboardLayout.vue';
import { useToast } from 'vue-toastification';
import { onMounted, ref } from 'vue';

defineProps({
    assignments: Array,
    courseName: String
});

const isSubmitted = ref(false);
const selectedFile = ref(null);
const toast = useToast();

// Function to handle file selection
const handleFileChange = (event) => {
    selectedFile.value = event.target.files;
};

const handleSubmit = (assignmentId) => {

    if (!selectedFile.value) {
        toast.warning('Please select a PDF file to submit');
        return;
    }

    isSubmitted.value = true;

    const formData = new FormData();

    formData.append('assignmentId', assignmentId);

    for (let i = 0; i < selectedFile.value.length; i++) {
        formData.append('files[]', selectedFile.value[i]);
    }

    axios.post(route('assignment.submit'), formData)
        .then(response => {
            console.log(response)
            if (response?.data?.message) {
                toast.success('Note created successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else if (response?.data?.error) {
                toast.error(response.data.error);
            }
        })
        .catch(error => {
            if (error.response?.data?.errors) {
                const errorMessages = Object.values(error.response.data.errors || {}).flat();
                errorMessages.forEach(message => {
                    toast.error(message);
                });
            } else if (error.response?.data?.message) {
                toast.error(error.response.data.message);
            } else {
                toast.error('An unexpected error occurred.');
            }
            console.log(error);
        });
};

const downloadDocument = (url) => {
    if (url) {
        const link = document.createElement('a');
        link.href = '/storage/' + url;
        link.download = url.split('/').pop();
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        alert('No document available');
    }
}

</script>
<template>
    <StudentDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Assignments</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Assignments</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">{{ courseName }}</h2>
            </div>
        </div>
        <div class="row mb-4" v-for="assignment, index in assignments" :key="subject_id">
            <h4>Assignment {{ index + 1 }}</h4>
            <div class="accordion accordion-flush" :id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            :data-bs-target="`#subject-9` + index" aria-expanded="false"
                            aria-controls="flush-collapseThree">
                            Diversity and Inclusion (Submission time on or before {{ assignment.deadline }})
                        </button>
                    </h2>
                    <div :id="`subject-9` + index" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4 d-flex justify-content-end align-items-end">
                                            <button class="btn btn-success"
                                                @click="downloadDocument(assignment.assignment_url)">
                                                <span v-if="assignment.assignment_url">Download Assignment</span>
                                                <span v-else>No document available</span>
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <input class="form-control" type="file" id="formFile"
                                                @change="handleFileChange">
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-primary" @click="handleSubmit(assignment.id)"
                                                :disabled="isSubmitted">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-body">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-center align-items-center">
                                            <span v-if="assignment.submission && assignment.submission.submit_time">
                                                You have uploaded your answers on {{ assignment.submission.submit_time
                                                }}
                                            </span>
                                        </div>
                                        <div class="col-12 d-flex justify-content-center align-items-center mt-3">
                                            <button class="btn btn-warning "
                                                @click="downloadDocument(assignment.submission.submit_url)">
                                                <span v-if="assignment.submission && assignment.assignment_url">Download
                                                    your answers</span>
                                                <span v-else>Not submit</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StudentDashboardLayout>
</template>
