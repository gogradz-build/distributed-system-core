<script>
import { onMounted, ref, nextTick } from 'vue';
import $ from 'jquery';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

export default {
    name: 'AssignmentCreate',
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
        const assignments = ref([]);
        const selectedAssignmentId = ref(null);
        const submissions = ref([]);

        const assignmentSubmissions = (assignment) => {
            axios.get(route('subject.assignments.submissions', { assignment_id: assignment.id }))

                .then(response => {
                    submissions.value = response.data;
                    console.log(response.data)
                })
                .catch(error => {
                    console.error(error)
                })
        }

        const fetchAssignments = () => {
            axios.get(route(`subject.assignments`, { subject_id: props.subject_id, schedule_id: props.schedule_id }))
                .then(response => {
                    assignments.value = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        };


        const saveMarks = (submission) => {


            console.log('Assignment ID:', submission.id);
            console.log('Marks:', submission.marks);

          const formData = new FormData();
          formData.append('submission_id', submission.id);
          formData.append('mark', submission.marks);

            axios.post(route('assignment.submission.marks'), formData)
                .then(response => {
                    if (response?.data?.massage) {
                        toast.success('Mark saved')
                    } else if (response?.data?.error) {
                        toast.error(response.data.error)
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
                $('#marksTable').DataTable({
                    responsive: true,
                    scrollX: true,
                    scrollY: '300px',
                    scrollCollapse: true,
                    paging: true,
                    ordering: true,
                    columnDefs: [
                        { orderable: false, targets: [4] },
                    ],
                    order: [[1, 'asc']],
                });
            });
        });
        return {
            assignments,
            selectedAssignmentId,
            assignmentSubmissions,
            submissions,
            saveMarks,

        }
    },

};

</script>
<template>

    <div class="row mt-4">
        <form>
            <div class="row mt-4">
                <div class="col-12 col-lg-6">
                    <div class="mb-3">
                        <label for="assignment" class="form-label">Select Assignment</label>
                        <select id="assignment" class="form-select" v-model="selectedAssignmentId"
                            @change="assignmentSubmissions(assignments.find(a => a.id === selectedAssignmentId))">
                            <option value="" disabled selected>Select Assignment</option>
                            <option v-for="assignment in assignments" :key="assignment.id" :value="assignment.id">
                                {{ assignment.assignment_title }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-12 d-flex justify-content-start align-items-center">
                  <button type="button" class="btn btn-danger mt-3"><i class="fas fa-solid fa-print"
                    style="color: #ffffff; margin-right: 5px;"></i>All Records</button>
                </div>
                <!-- <div class="col-12 d-flex justify-content-end align-items-center">
                    <button type="submit" class="btn btn-primary assignment-btn ">Create</button>
                </div> -->
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
                                <th style="width: 20%">Assignment Name</th>
                                <th style="width: ">Submit on</th>
                                <th style="width: ">Document</th>
                                <th style="width: ">Marks</th>
                                <th>Save Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(submission, index) in submissions" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ submission.register_number }}</td>
                                <td>{{ submission.batch_name }}</td>
                                <td>{{ submission.assignment_name }}</td>
                                <td>
                                    <span
                                        v-if="new Date(submission.submit_time) <= new Date(submission.assignment_deadline)"
                                        class="badge bg-success text-dark">
                                        {{ submission.submit_time }}
                                    </span>
                                    <span v-else class="badge bg-danger text-dark">
                                        {{ submission.submit_time }}
                                    </span>
                                </td>
                                <td>
                                    <a :href="'/storage/' + submission.submit_url" target="_blank"
                                        v-if="submission.submit_url">
                                        Download Document
                                    </a>
                                    <span v-else>No document available</span>
                                </td>
                                <td>
                                    <input type="number" class="form-control" v-model="submission.marks"
                                        placeholder="Assignment Marks" />
                                </td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <button class="btn btn-warning" @click="saveMarks(submission)">Save</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
