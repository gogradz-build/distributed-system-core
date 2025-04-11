<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { useToast } from 'vue-toastification';
import { onMounted, nextTick, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';


const toast = useToast();

// Delete modal state
const isModalOpen = ref(false);
const modalTitle = ref('');
const deleteID = ref(null);

function triggerModal(id, title) {
    deleteID.value = id;
    modalTitle.value = title;
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
    axios.delete(`/refs/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                toast.warning(response.data.message);
                if ($.fn.DataTable.isDataTable('#refTable')) {
                    $('#refTable').DataTable().destroy();
                    fetchRfs().then(() => {
                        initializeDataTable();
                    });
                }
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


onMounted(() => {
    fetchRfs().then(() => {
        initializeDataTable();
    });
    nextTick(() => {
        const successMessage = localStorage.getItem('successMessage');
        if (successMessage) {
            toast.success(successMessage);
            localStorage.removeItem('successMessage');
        }

    });

});

const refs = ref([]);

const fetchRfs = async () => {
    try {
        const response = await axios.get('/refs');
        refs.value = response.data;
        console.log(response);

    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}
function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#refTable')) {
            $('#refTable').DataTable().destroy();
        }
        $('#refTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollY: '',
            scrollCollapse: true,
            paging: true,
        });
        const successMessage = localStorage.getItem('successMessage');
        if (successMessage) {
            toast.success(successMessage);
            localStorage.removeItem('successMessage');
        }
    });
}
</script>


<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">SALES REF</h4>
                <a :href="route('admin.create.ref')"><button type="button" class="btn btn-primary mt-4 mb-4 mr-2"><i
                            class="fas fa-solid fa-plus" style="color: #ffffff; margin-right: 5px;"></i> Add New
                        Sales Ref</button></a>
                <!-- <button type="button" class="btn btn-success mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Ref Record</button> -->
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Sales Ref</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <!-- <div class="card-header ">
                        <h3 class="card-title">Ref Details</h3>
                    </div> -->
                    <div class="card-body">
                        <h6 style="font-weight: 700;" class="">SALES REF TABLE</h6>
                        <hr>
                        <!-- Add ID to the table -->
                        <table id="refTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">ID</th>
                                    <th style="width: 13%;">First Name</th>
                                    <th style="width: 13%;">Last Name</th>
                                    <th style="width: 20%;">Email</th>
                                    <th style="width: 10%;">Phone No</th>
                                    <th style="width: 22%;">Address</th>
                                    <th style="width: 10%;">NIC No</th>
                                    <!-- <th>Status</th> -->
                                    <th style="text-align: center; width: 12%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(ref, index) in refs" :key="ref.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ ref.first_name }}</td>
                                    <td>{{ ref.last_name }}</td>
                                    <td>{{ ref.email }}</td>
                                    <td>{{ ref.phone_number }}</td>
                                    <td>{{ ref.address }}</td>
                                    <td>{{ ref.nic_number }}</td>
                                    <!-- <td>
                                        <span class="badge badge-pill badge-success"
                                            v-if="staffMember.is_active === 1">Active</span>
                                        <span class="badge badge-pill badge-success" v-else>Inactive</span>
                                    </td> -->
                                    <td class="d-flex justify-content-center align-items-center">
                                                <a href=""><img src="/images/icon/edit.png" alt="" width="25px" height="25px" class="mr-2 table-action-icon"></a>
                                        <a @click="triggerModal(ref.id, 'Delete Ref')" style="cursor: pointer;">
                                            <img src="/images/icon/trash-bin.png" alt="" width="25px" height="25px" class="table-action-icon">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <!-- ReusableModal Component -->
        <DeleteModel :isModalOpen="isModalOpen" :modalTitle="modalTitle" @close="isModalOpen = false"
            @confirm-delete="confirmDelete" />
    </AdminLayout>
</template>
