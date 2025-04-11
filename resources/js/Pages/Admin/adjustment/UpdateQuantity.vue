<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, computed, ref, watch } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { usePage } from '@inertiajs/vue3';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const page = usePage()
const toast = useToast();
const formRef = ref(null);

const props = defineProps({
    product: Array
});

// Form state
const formData = ref({
    product_code: props.product?.code || '',
    name: props.product?.name || '',
    brand_id: props.product?.brand_id || '',
    old_quantity: props.product?.warehouse_stocks[0]?.quantity || '',
    warehouse_stock_id: props.product?.warehouse_stocks[0]?.id || '',
    warehouse_id: props.product?.warehouse_stocks[0]?.warehouse_id || '',
    new_quantity: '',
    remark: '',
});

const selectedValues = ref({});

const handleSubmit = (e) => {
    e.preventDefault();

    if (!formRef.value.checkValidity()) {
        formRef.value.classList.add('was-validated');
        return;
    }
    axios.put(`/adjustments/${props.product.id}`, formData.value)
        .then(response => {
            console.log(response);
            if (response?.data?.message) {
                localStorage.setItem('successMessage', response.data.message);
                window.location.href = route('admin.adjustment');
            } else if (response?.data?.error) {
                toast.error(response.data.error);
            }
        })
        .catch(error => {
            if (error.response?.data?.errors) {
                console.log(error.response?.data?.errors);
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


onMounted(() => {
    fetchCategories();
    fetchSuppliers();
    fetchWarehouse();
    fetchBrands();

    // ----------multiplefile-upload---------
    $("#image").fileinput({
        'theme': 'fa',
        'uploadUrl': '#',
        showRemove: false,
        showUpload: false,
        showZoom: false,
        showCaption: false,
        browseClass: "btn btn-danger",
        browseLabel: "",
        browseIcon: "<i class='fa fa-plus'></i>",
        overwriteInitial: false,
        initialPreviewAsData: true,
        fileActionSettings: {
            showUpload: false,
            showZoom: false,
            removeIcon: "<i class='fa fa-times'></i>",
        }
    })

    nextTick(() => {

        $('#courseTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollY: '',
            scrollCollapse: true,
            paging: false
        });

    });
});


const variationValues = ref([]);

const fetchVariationData = async (newVal) => {

    try {
        const response = await axios.get(route('get.variation.type.data.value', { variation_id: newVal }));
        variationValues.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

// fetch data

const categories = ref([]);
const suppliers = ref([]);
const warehouses = ref([]);
const brands = ref([]);

const fetchBrands = () => fetchData('/brands', brands);
const fetchCategories = () => fetchData('/categories', categories);
const fetchSuppliers = () => fetchData('/suppliers', suppliers);
const fetchWarehouse = () => fetchData('/warehouses', warehouses);

const fetchData = async (url, refVar) => {
    try {
        const response = await axios.get(url);
        refVar.value = response.data;
    } catch (error) {
        console.error(`Error fetching data from ${url}:`, error);
    }
};

</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2 ">UPDATE ADJUSTMENT</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item"><a :href="route('admin.adjustment')">Adjustment</a></li>
                    <li class="breadcrumb-item active">Update Adjustment</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Update Adjustment</h3>

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
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="code">Product Code :</label>
                                                <input type="text" class="form-control" id="code"
                                                    placeholder="Enter Product Code" v-model="formData.product_code"
                                                    disabled>
                                                <div class="invalid-feedback">
                                                    Please Provide Enter Product Code
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-group">
                                                <label for="name">Product Name :</label>
                                                <input type="text" class="form-control" id="name"
                                                    placeholder="Enter Product Name" v-model="formData.name" disabled>
                                                <div class="invalid-feedback">
                                                    Please Provide Enter Product Name
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="code">Product Quantity :</label>
                                                <input type="text" class="form-control" id="code"
                                                    placeholder="Enter Product Code" v-model="formData.old_quantity"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-group">
                                                <label for="name">Product Brand :</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    v-model="formData.brand_id" disabled>
                                                    <option value="" disabled>Select Brand</option>
                                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                                        {{ brand.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="code">New Quantity :</label>
                                                <input type="text" class="form-control" id="code"
                                                    placeholder="Enter Product Code" v-model="formData.new_quantity"
                                                    required>
                                                <div class="invalid-feedback">
                                                    Please Provide Enter New Quantity
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="form-group">
                                                <label for="name">Adjustment Remark :</label>
                                                <input type="text" class="form-control" id="name"
                                                    placeholder="Enter Product Name" v-model="formData.remark" required>
                                                <div class="invalid-feedback">
                                                    Please Provide Enter Adjustment Remark
                                                </div>
                                            </div>
                                        </div>
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
    </AdminLayout>
</template>
