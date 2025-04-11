<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { ref, watch, onMounted, nextTick } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const toast = useToast();
const variation_type = ref([]);
const formData = ref({ name: '', values: [], });
const formRef = ref(null);
const variations = ref([]);

// Delete modal state
const isModalOpen = ref(false);
const modalTitle = ref('');
const deleteID = ref(null);

onMounted(() => {
    fetchVariations().then(() => {
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

watch(variations, () => {
    console.log("ok");
});

//initialize table
function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#VariationTable')) {
            $('#VariationTable').DataTable().destroy();
        }

        $('#VariationTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollY: '300px',
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

//fetch variations
const fetchVariations = async () => {
    try {
        const response = await axios.get('/variations');
        variations.value = response.data;
        console.log(response);
    } catch (error) {
        console.error('Error fetching variation type:', error);
    }
}

// Create form submission
const handleSubmit = (e) => {
    e.preventDefault();

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }

    axios.post('/variations', formData.value)
        .then(response => {
            if (response?.data?.message) {
                toast.success(response.data.message);
                formData.value.name = '';
                formData.value.values = [];
                if ($.fn.DataTable.isDataTable('#VariationTable')) {
                    $('#VariationTable').DataTable().destroy();
                    fetchVariations().then(() => {
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
            } else if (error.response?.data?.error) {
                toast.error(error.response.data.error);
            } else {
                toast.error('An unexpected error occurred.');
            }
            console.error(error);
        });
};

//delete variations
const deleteFunction = (deleteId) => {
    axios.delete(`/variations/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                toast.warning(response.data.message);
                variations.value = variations.value.filter(variation => variation.id !== deleteId);
                if ($.fn.DataTable.isDataTable('#VariationTable')) {
                    $('#VariationTable').DataTable().destroy();
                    fetchVariations().then(() => {
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

//add empty field
const addField = () => {
    console.log(formData.value.values);
    formData.value.values.push({ value: '' });
}

//remove field
const removeField = (index) => {
    formData.value.values.splice(index, 1);
    console.log(formData.value.values);
};

//modal trigger
function triggerModal(id, title) {
    deleteID.value = id;
    modalTitle.value = title;
    isModalOpen.value = true;
}

// Handle deletion after confirmation
function confirmDelete() {
    if (deleteID.value) {
        deleteFunction(deleteID.value);
        isModalOpen.value = false;
    }
}

</script>

<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2 class="mt-2">VARIATION</h2>
                <button type="button" class="btn btn-secondary  mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Variation Report</button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Variation</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Create New Variation</h3>
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
                                        <label for="Variation-name">Variation Name :</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Variation Name" v-model="formData.name" required>
                                        <div class="invalid-feedback">
                                            Please Provide Enter Variation Name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="row" v-for="(field, index) in formData.values" :key="index">
                                        <div class="col-10 mt-3">
                                            <input type="text" class="form-control" placeholder="Enter Variation Type"
                                                v-model="field.value" required />
                                            <div class="invalid-feedback">
                                                Please Provide Enter Variation Type
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-danger mt-3"
                                                @click="removeField(index)">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary mt-4 mr-2" @click="addField">Add
                                        Values to Variation Type</button>
                                    <button type="submit" class="btn btn-primary mt-4 category-btn">Save</button>
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
                    <!-- <div class="card-header">
                        <h3 class="card-title">Variations</h3>
                    </div> -->

                    <div class="card-body">
                        <h6 style="font-weight: 700;" class=""> VARIATIONS TABLE</h6>
                        <hr>
                        <table id="VariationTable" class="table table-hover " style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">ID</th>
                                    <th style="width: 40%;">Variation Type</th>
                                    <th style="width: 20%">Values</th>
                                    <th style="text-align: center; width: 30% ">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(variation, index) in variations" :key="variation.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ variation.variation_name }}</td>
                                    <td>{{ variation.variation_types }}</td>
                                    <td class="text-center">
                                        <a href=""><i class="fas fa-regular fa-eye mr-4"
                                                style="color: #0a7c46;"></i></a>
                                        <a href=""><i class="fas fa-solid fa-pen mr-4" style="color: #281cdf;"></i></a>
                                        <a @click="triggerModal(variation.variation_id, 'Delete Variation')"
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
    </AdminLayout>
</template>
