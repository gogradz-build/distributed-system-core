<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { ref, computed, onMounted, nextTick } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const toast = useToast();
const isEditing = ref(false);
const editID = ref(null);

// Form state and references
const formData = ref({
    name: '',
});
const formRef = ref(null); // Reference to the form

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
    axios.delete(`/warehouses/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                toast.warning(response.data.message);
                if ($.fn.DataTable.isDataTable('#warehouseTable')) {
                    $('#warehouseTable').DataTable().destroy();
                    fetchWarehouse().then(() => {
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

// Handle form submission
const handleSubmit = (e) => {
    e.preventDefault();

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }

    if (isEditing.value) {
        axios.put(`/warehouses/${editID.value}`, formData.value)
            .then(response => {
                if (response?.data?.message) {
                    toast.success(response.data.message);
                    resetForm();
                    refreshTable();
                } else if (response?.data?.error) {
                    toast.error(response.data.error);
                }
            })
            .catch(handleError);
    } else {
        axios.post('/warehouses', formData.value)
            .then(response => {
                if (response?.data?.message) {
                    toast.success(response.data.message);
                    resetForm();
                    refreshTable();
                } else if (response?.data?.error) {
                    toast.error(response.data.error);
                }
            })
            .catch(handleError);
    }
};

const handleEdit = (warehouse) => {
    formData.value.name = warehouse.name;
    editID.value = warehouse.id; 
    isEditing.value = true;
};

onMounted(() => {
    fetchWarehouse().then(() => {
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

const resetForm = () => {
    formData.value.name = '';
    isEditing.value = false;
    editID.value = null;
    formRef.value.classList.remove('was-validated');
};

const refreshTable = () => {
    if ($.fn.DataTable.isDataTable('#warehouseTable')) {
        $('#warehouseTable').DataTable().destroy();
        fetchWarehouse().then(() => {
            initializeDataTable();
        });
    }
};

const warehouses = ref([]);

const fetchWarehouse = async () => {
    try {
        const response = await axios.get('/warehouses');
        warehouses.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}
function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#warehouseTable')) {
            $('#warehouseTable').DataTable().destroy();
        }
        $('#warehouseTable').DataTable({
            responsive: true,
            scrollX: true,
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
                <h4 class="mt-2">WAREHOUSE</h4>
                <!-- <button type="button" class="btn btn-secondary  mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Warehouse Report</button> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Warehouse</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header  card-header-background">
                        <h3 class="card-title">Create New Warehouse</h3>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="warehouse-name">Warehouse Name :</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Warehouse Name" v-model="formData.name" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Warehouse Name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <button type="submit " class="btn btn-primary mt-4 category-btn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">

                    <div class="card-body">
                        <h6 style="font-weight: 700;" class="">WAREHOUSE TABLE</h6>
                        <hr>
                        <table id="warehouseTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="100px">Warehouses ID</th>
                                    <th width="85%">Warehouses Name</th>
                                    <!-- <th style="text-align: center; width: 20%">Status</th> -->
                                    <th width="20%" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(warehouse, index) in warehouses" :key="warehouse.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ warehouse.name }}</td>
                                    <td class="text-center">
                                        <a href="#" @click.prevent="handleEdit(warehouse)"><img src="/images/icon/edit.png" alt="" width="25px" height="25px" class="mr-2 table-action-icon"></a>
                                        <a @click="triggerModal(warehouse.id, 'Delete Warehouse')"
                                            style="cursor: pointer;">
                                            <img src="/images/icon/trash-bin.png" alt="" width="25px" height="25px" class="table-action-icon">
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
    </AdminLayout>
</template>
