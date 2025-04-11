<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { ref, onMounted, nextTick, watch } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const toast = useToast();
const isEditing = ref(false);
const editID = ref(null);
const formData = ref({
    name: '',
});
const formRef = ref(null);

// Delete modal state
const isModalOpen = ref(false);
const modalTitle = ref('');
const deleteID = ref(null);

const categories = ref([]);
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
    axios.delete(`/categories/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                toast.warning(response.data.message);
                categories.value = categories.value.filter(category => category.id !== deleteId);
                if ($.fn.DataTable.isDataTable('#categoryTable')) {
                    $('#categoryTable').DataTable().destroy();
                }
                nextTick(() => {
                    initializeDataTable();
                });
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

const handleEdit = (category) => {
    formData.value.name = category.name;
    editID.value = category.id;
    isEditing.value = true;
};

const handleSubmit = (e) => {
    e.preventDefault();
    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }

    if (isEditing.value) {
        axios.put(`/categories/${editID.value}`, formData.value)
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
        axios.post('/categories', formData.value)
            .then(response => {
                if (response?.data?.message) {
                    toast.success(response.data.message);
                    formData.value.name = '';
                    if ($.fn.DataTable.isDataTable('#categoryTable')) {
                        $('#categoryTable').DataTable().destroy();
                        fetchCategories().then(() => {
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
    }
};

const resetForm = () => {
    formData.value.name = '';
    isEditing.value = false;
    editID.value = null;
    formRef.value.classList.remove('was-validated');
};

const refreshTable = () => {
    if ($.fn.DataTable.isDataTable('#categoryTable')) {
        $('#categoryTable').DataTable().destroy();
        fetchCategories().then(() => {
            initializeDataTable();
        });
    }
};

onMounted(() => {
    fetchCategories().then(() => {
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

const fetchCategories = async () => {
    try {
        const response = await axios.get('/categories');
        categories.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#categoryTable')) {
            $('#categoryTable').DataTable().destroy();
        }
        $('#categoryTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollX: '200px',
            scrollCollapse: false,
            paging: true,
        });

        const successMessage = localStorage.getItem('successMessage');
        if (successMessage) {
            toast.success(successMessage);
            localStorage.removeItem('successMessage');
        }
    });
}

watch(categories, () => {
    initializeDataTable();
});

</script>

<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">CATEGORY</h4>
                <!-- <button type="button" class="btn btn-success  mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Category Report</button> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Category</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12 ">
                <div class="card ">
                    <div class="card-header ">
                        <h6 class="card-title" style="font-weight: 700;">Create New Category</h6>
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
                                        <label for="category-name">Category Name :</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Category Name" v-model="formData.name" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Category Name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="category-name"></label>
                                    <button type="submit " class="btn btn-primary category-btn"
                                        style="margin-top: 32px;">Save</button>
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
                    <!-- <div class="card-header table-header">
                           <h3 class="card-title">Categories</h3>
                    </div> -->

                    <div class="card-body">
                        <h6 style="font-weight: 700;" class="">CATEGORY TABLE</h6>
                        <hr>
                        <table id="categoryTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="100px">ID</th>
                                    <th width="85%">Category Name</th>
                                    <!-- <th style="text-align: center; width: 20%">Status</th> -->
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(category, index) in categories" :key="category.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ category.name }}</td>
                                    <td>
                                        <a href="#" @click.prevent="handleEdit(category)">
                                            <img src="/images/icon/edit.png" alt="" width="25px" height="25px" class="mr-2 table-action-icon"></a>
                                        <a @click="triggerModal(category.id, 'Delete Category')"
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
