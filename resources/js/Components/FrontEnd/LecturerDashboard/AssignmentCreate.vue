<script>
import { onMounted, ref, nextTick } from 'vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

export default {
    name: 'AssignmentCreate',
    components: {
        DeleteModel
    },
    props: {
        schedule_id: {
            type: String,
            required: true
        },
        subject_id: {
            type: String,
            required: true
        }
    },
    setup(props) {
        const toast = useToast();
        const title = ref('');
        const deadlineDate = ref('');
        const deadlineTime = ref('');
        const assignmentDocument = ref(null);
        const isModalOpen = ref(false);
        const modalTitle = ref('');
        const deleteID = ref(null);
        const assignments = ref([]);


        const formRef = ref(null);

        const fetchAssignments = () => {
            axios.get(route(`subject.assignments`, { subject_id: props.subject_id, schedule_id: props.schedule_id }))
                .then(response => {
                    assignments.value = response.data;
                })
                .catch(error => {
                    console.error(error);
                    toast.error('Failed to fetch assignments');
                });
        };


        const handleFileUpload = (event) => {
            assignmentDocument.value = event.target.files[0];
        };


        const handleSubmit = (e) => {
            e.preventDefault();

            if (!formRef.value.checkValidity()) {
                formRef.value.classList.add('was-validated');
                return;
            }

            const formData = new FormData();
            formData.append('title', title.value);
            formData.append('deadlineDate', deadlineDate.value);
            formData.append('deadlineTime', deadlineTime.value);

            if (assignmentDocument.value) {
                formData.append('document', assignmentDocument.value);
            }


            axios.post(route('assignments.add.store', { subject_id: props.subject_id, schedule_id: props.schedule_id }), formData)
                .then(response => {
                    if (response?.data?.message) {
                        toast.success('Assignment created successfully!');
                        title.value = '';
                        deadlineDate.value = '';
                        deadlineTime.value = '';
                        assignmentDocument.value = null;
                        fetchAssignments();
                    } else if (response?.data?.errors) {

                        toast.error(response.data.error);
                    }
                })
                .catch((error) => {
                    if (error.response?.data?.errors) {
                        const errorMessages = Object.values(error.response.data.errors || {}).flat();
                        errorMessages.forEach(message => toast.error(message));
                    } else if (error.response?.data?.message) {
                        toast.error(error.response.data.message);
                    } else {
                        toast.error('An unexpected error occurred.');
                    }
                    console.error(error);
                });
        };

        const triggerModal = (id, title) => {

            deleteID.value = id;
            modalTitle.value = title;
            isModalOpen.value = true;
            console.log('Triggering modal:', isModalOpen.value);
        };

        const confirmDelete = () => {
            console.log('1')
            if (deleteID.value) {
                deleteAssignment(deleteID.value);
                isModalOpen.value = false;
            }
        };

        const deleteAssignment = (deleteId) => {
            console.log(deleteId);
            axios.delete(`/assignments/${deleteId}`)
                .then(response => {
                    if (response?.data?.message) {
                        fetchAssignments();
                        toast.warning('Assignment deleted successfully');
                    } else if (response?.data?.error) {
                        toast.error(response.data.error);
                    }
                })
                .catch((error) => {
                    if (error.response?.data?.errors) {
                        const errorMessages = Object.values(error.response.data.errors || {}).flat();
                        errorMessages.forEach(message => toast.error(message));
                    } else if (error.response?.data?.message) {
                        toast.error(error.response.data.message);
                    } else {
                        toast.error('An unexpected error occurred.');
                    }
                    console.error(error);
                });
        };

        onMounted(() => {
            fetchAssignments()
            nextTick(() => {
                $('#NoteTable').DataTable({
                    responsive: true,
                    scrollX: true,
                    scrollY: '300px',
                    scrollCollapse: true,
                    paging: true,
                    ordering: true,
                    columnDefs: [{ orderable: false, targets: [4] }],
                    order: [[1, 'asc']],
                });
            });
        });

        return {
            formRef,
            title,
            deadlineDate,
            deadlineTime,
            assignmentDocument,
            isModalOpen,
            modalTitle,
            deleteID,
            assignments,
            handleSubmit,
            handleFileUpload,
            triggerModal,
            confirmDelete,
            deleteAssignment,
        };
    },
};

</script>
<template>

    <div class="row mt-4">
        <form class="needs-validation" ref="formRef" @submit.prevent="handleSubmit" novalidate>
            <div class="row mt-4">
                <!-- Assignment Name -->
                <div class="col-12 col-lg-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Assignment Name</label>
                        <input type="text" class="form-control" id="title" placeholder="Assignment Name" v-model="title"
                            required />
                        <div class="invalid-feedback">Please provide an assignment name.</div>
                    </div>
                </div>

                <!-- Assignment Deadline Date -->
                <div class="col-12 col-lg-6">
                    <div class="mb-3">
                        <label for="deadlineDate" class="form-label">Assignment Deadline Date</label>
                        <input type="date" class="form-control" id="deadlineDate" v-model="deadlineDate" required />
                        <div class="invalid-feedback">Please provide a deadline date.</div>
                    </div>
                </div>

                <!-- Assignment Deadline Time -->
                <div class="col-12 col-lg-6">
                    <div class="mb-3">
                        <label for="deadlineTime" class="form-label">Assignment Deadline Time</label>
                        <input type="time" class="form-control" id="deadlineTime" v-model="deadlineTime" required />
                        <div class="invalid-feedback">Please provide a deadline time.</div>
                    </div>
                </div>

                <!-- Assignment Document -->
                <div class="col-12 col-lg-6">
                    <div class="mb-3">
                        <label for="assignmentDocument" class="form-label">Assignment Document</label>
                        <input class="form-control" type="file" id="formFileMultiple" accept="application/pdf"
                            @change="handleFileUpload" multiple>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-12 d-flex justify-content-end align-items-center">
                    <button type="submit" class="btn btn-primary assignment-btn">Create</button>
                </div>
            </div>
        </form>
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
                                <th style="width: 5%">#</th>
                                <th style="width: 20%">subject Name</th>
                                <th style="width:">Course Name</th>
                                <th style="width:">Batch</th>
                                <th style="width:">Assignment Name</th>
                                <th style="width:">Created At</th>
                                <th style="width:">Deadline Date</th>
                                <th style="width:">Deadline Time</th>
                                <th style="width:">Document</th>
                                <th>Status</th>
                                <th style="width:">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(assignment, index) in assignments" :key="assignment.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ assignment.subject_name }}</td>
                                <td>{{ assignment.course_name }}</td>
                                <td>{{ assignment.batch_name }}</td>
                                <td>{{ assignment.assignment_title }}</td>
                                <td>{{ new Date(assignment.created_at).toLocaleDateString() }}</td>
                                <td>{{ assignment.deadline_date }}</td>
                                <td>{{ assignment.deadline_time }}</td>
                                <td>
                                    <a :href="'/storage/' + assignment.document_url" target="_blank"
                                        v-if="assignment.document_url">
                                        Download Document
                                    </a>
                                    <span v-else>No document available</span>
                                </td>
                                <td><span class="badge bg-warning text-dark">Collect Student Marks</span></td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <a @click="triggerModal(assignment.id, 'Delete Assignment')"
                                        style="cursor: pointer;">
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
</template>

<style>
.nav-pills .nav-link.active {
    color: #fff;
    background-color: transparent !important;
}

.side-bar-nav-link {
    box-shadow: none !important;
}

.nav-treeview>.nav-item>.nav-link.active {
    color: #fff !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24);
}
</style>
