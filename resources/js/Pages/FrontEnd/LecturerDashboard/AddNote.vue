<script setup>
import LecturerDashboardLayout from '@/Layouts/LecturerDashboardLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const formRef = ref(null);
const toast = useToast();
const errors = ref({});
const document_url = ref([]);
const title = ref('');
// const formRef = ref(null);

const props = defineProps({
    schedule_id: String,
    subject_id: String
});

// delete module toast
const isModalOpen = ref(false);
const modalTitle = ref('');
const deleteID = ref(null);

function triggerModal(id, url, title) {
    deleteID.value = id;
    modalTitle.value = 'Delete Note';
    isModalOpen.value = true;
}

// Handle deletion after confirmation
function confirmDelete() {
    if (deleteID.value) {
        deleteFunction(deleteID.value);
        isModalOpen.value = false; // Close the modal
    }
}

const deleteFunction = (deleteId) => {
    axios.delete(`/notes/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                fetchNotes();
                toast.warning('Note deleted successfully');
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
            console.error(error);
        });
};

// end of delete module toast

const handleFileChange = (event) => {
    document_url.value = event.target.files;
};


const handleSubmit = (e) => {
    e.preventDefault();

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }

    const formData = new FormData();

    formData.append('title', title.value);

    for (let i = 0; i < document_url.value.length; i++) {
        formData.append('files[]', document_url.value[i]);
    }

    axios.post(route('notes.add.store', { subject_id: props.subject_id, schedule_id: props.schedule_id }), formData)
        .then(response => {
            console.log(response)
            if (response?.data?.message) {
                toast.success('Note created successfully');
                title.value = '';
                document_url.value = [];
                fetchNotes();
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

const notes = ref([]);

const fetchNotes = async () => {
    try {
        const response = await axios.get('/notes');
        notes.value = response.data;
        console.log(response)
    } catch (error) {
        console.error('Error fetching notes:', error);
    }
};


onMounted(() => {
    fetchNotes();
    console.log("ok");
    $('#NoteTable').DataTable();
});


</script>

<template>
    <LecturerDashboardLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Notes</h1><span>(NHRS456-Human Resource Introductions)</span>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="route('lecturer.dashboard')">Home</a></li>
                    <li class="breadcrumb-item"><a :href="route('lecturer.course.show')">Courses</a></li>
                    <li class="breadcrumb-item"><a
                            :href="route('lecturer.course.subjects', { id: schedule_id })">Subject</a></li>
                    <li class="breadcrumb-item active">Note Create</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Add Note</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" ref="formRef" @submit.prevent="handleSubmit" novalidate>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="subject-title">Topic Name</label>
                                        <input type="text" class="form-control" id="title"
                                            placeholder="Enter subject title" v-model="title" required>
                                        <div class="invalid-feedback">
                                            Please provide a topic name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="formFileMultiple" class="form-label">Upload Your Note (pdf)</label>
                                        <input class="form-control" type="file" id="formFileMultiple"
                                            accept="application/pdf" @change="handleFileChange" multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
                                    <th style="width: 20%">#</th>
                                    <th style="width: 20%">Topic Name</th>
                                    <th style="width: 40%">Document</th>
                                    <th style="width: 20%">Created At</th>
                                    <th style="width: 20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(note, index) in notes" :key="note.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ note.title }}</td>
                                    <td>
                                        <a :href="'/storage/' + note.document_url" target="_blank"
                                            v-if="note.document_url">
                                            Download Document
                                        </a>
                                        <span v-else>No document available</span>
                                    </td>
                                    <td>{{ new Date(note.created_at).toLocaleDateString() }}</td>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <a @click="triggerModal(note.id, 'Delete Note')" style="cursor: pointer;">
                                            <i class="fas fa-solid fa-trash mr-4" style="color: #FF0000;"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ReusableModal Component -->
        <DeleteModel :isModalOpen="isModalOpen" :modalTitle="modalTitle" @close="isModalOpen = false"
            @confirm-delete="confirmDelete" />
    </LecturerDashboardLayout>
</template>
