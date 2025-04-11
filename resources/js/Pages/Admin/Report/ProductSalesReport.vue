<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, computed, ref, watch } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { usePage } from '@inertiajs/vue3';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const page = usePage()
const toast = useToast();
const startDate = ref('');
const endDate = ref('');
const refs = ref('');
const reports = ref('');

onMounted(() => {
    fetchDataTable();

    nextTick(() => {

        initializeDataTable();

    });

});
const hasReport = computed(() => {
    return reports.value.length > 0;
});

const initializeDataTable = () => {
    $('#perchesTable').DataTable({
        responsive: true,
        scrollX: true,
        scrollY: '300px',
        scrollCollapse: true,
        paging: true,
    });
};

watch([startDate, endDate], ([newRefId, newStartDate, newEndDate]) => {
    fetchDataTable(newRefId, newStartDate, newEndDate);
});

const fetchDataTable = async (startDate, endDate) => {
    try {
        const params = {}
        if (startDate) params.start_date = startDate;
        if (endDate) params.end_date = endDate;

        const response = await axios.get(route('admin.product.report', { params }));
        reports.value = response.data;
        console.log(response);
        nextTick(() => {
            $('#perchesTable').DataTable().clear().rows.add(response.data).draw();
        });
    } catch (error) {
        console.error('Error fetching data table:', error);
    }

};
</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-3">PRODUCTS SALES REPORT</h4>
                <a :href="route('admin.sales.product.report', { start_date: startDate, end_date: endDate })"><button
                        type="button" class="btn btn-success mt-4 mb-4" :disabled="!hasReport"><i class="fas fa-solid fa-print"
                            style="color: #ffffff; margin-right: 5px;"></i>Sales
                        Report</button></a>
            </div>
            <div class="col-sm-6 mt-3">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active"><a href="">Report</a></li>
                    <li class="breadcrumb-item active">Products Sales Report</li>
                </ol>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 ">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="startDate"><i class="fas fa-calendar"> Start
                                    Date</i></label>
                            <input type="date" class="form-control" id="startDate" v-model="startDate">
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="endDate"><i class="fas fa-calendar"> End
                                    Date</i></label>
                            <input type="date" class="form-control" id="endDate" v-model="endDate">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <h6 class="card-title"  style="font-weight: 700;">Product Details</h6>
                    </div>
                    <div class="card-body">
                        <table id="refTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width:">ID</th>
                                    <th style="width:">Product Code</th>
                                    <th style="width:">Product Name</th>
                                    <th style="width:">Sold Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in reports" :key="index">
                                    <td>{{ 1 + index }}</td>
                                    <td>MH{{ product.code }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.quantity_sold }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
