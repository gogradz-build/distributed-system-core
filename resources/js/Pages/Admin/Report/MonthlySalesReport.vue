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
const ref_id = ref('');
const paymentStatus = ref(1);
const startDate = ref('');
const endDate = ref('');
const refs = ref('');
const reports = ref('');

onMounted(() => {
    fetchShop();

    nextTick(() => {

        initializeDataTable();

    });

});
const hasSales = computed(() => {
    return reports.value && reports.value.sales && reports.value.sales.length > 0;
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

const fetchShop = async () => {
    try {
        const response = await axios.get('/refs');
        refs.value = response.data;
        console.error(refs);
        nextTick(() => {

            $('#perchesTable').DataTable({
                responsive: true,
                scrollX: true,
                scrollY: '',
                scrollCollapse: true,
                paging: true,
            });

        });
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

watch([paymentStatus, ref_id, startDate, endDate], ([newPaymentStatus, newRefId, newStartDate, newEndDate]) => {
    fetchDataTable(newPaymentStatus, newRefId, newStartDate, newEndDate);
});

const fetchDataTable = async (paymentStatus, ref_id, startDate, endDate) => {

    if (!ref_id) {
        toast.warning('Please select a shop');
    }
    else {
        try {
            const params = { paymentStatus, ref_id }
            if (startDate) params.start_date = startDate;
            if (endDate) params.end_date = endDate;

            const response = await axios.get(route('get.sales.payment.by.ref', { paymentStatus, ref_id, params }));
            reports.value = response.data;
            console.log(response);
            nextTick(() => {
                $('#perchesTable').DataTable().clear().rows.add(response.data).draw();
            });
        } catch (error) {
            console.error('Error fetching data table:', error);
        }
    }
};
</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">MONTHLY SALES REPORT</h4>
                <a
                    :href="hasSales ? route('admin.sales.report.by.ref', { ref_id, status_id: paymentStatus, start_date: startDate, end_date: endDate }) : '#'">
                    <button type="button" class="btn btn-success mt-4 mb-4" :disabled="!hasSales">
                        <i class="fas fa-solid fa-print" style="color: #ffffff; margin-right: 5px;"></i>
                        Monthly Sales Report
                    </button>
                </a>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active"> Monthly Sales</li>
                </ol>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 ">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01"><img
                                    src="/images/icon/sale create  ref.svg" alt="" width="20px"></label>
                            <select class="form-select" id="inputGroupSelect01" v-model="ref_id">
                                <option value="" disabled>Select Ref</option>
                                <option v-for="ref in refs" :key="ref.id" :value="ref.id">
                                    {{ ref.first_name }} {{ ref.last_name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01"><img
                                    src="/images/icon/sales_payments.svg" alt="" width="20px"></label>
                            <select class="form-select" aria-label="Default select example" v-model="paymentStatus">
                                <option value=1>Start to Print</option>
                                <option value=2>Print</option>
                                <option value=3>Delivered</option>
                                <option value=4>Completed</option>
                                <option value=5>All</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="startDate"><img src="/images/icon/calendar.svg"> Start
                                Date</img></label>
                            <input type="date" class="form-control" id="startDate" v-model="startDate">
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="endDate"><img src="/images/icon/calendar.svg"> End
                                Date</img></label>
                            <input type="date" class="form-control" id="endDate" v-model="endDate">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <!-- <label for="">Ref Name</label> -->
                <!-- <p> {{ reports.sales }}</p> -->
                <div class="card">
                    <div class="card-header">
                        <h6 style="font-weight: 700;" class="">Monthly Sales Details</h6>
                    </div>
                    <div class="card-body">
                        <table id="refTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width:">#</th>
                                    <th style="width:">Invoice ID</th>
                                    <th style="width:">Shop Name</th>
                                    <th style="width:">Total Amount</th>
                                    <th style="width:">Status</th>
                                    <th style="width:">Status Created At</th>
                                    <th style="width:">Sales Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(payment, index) in reports.sales" :key="ref.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>MH{{ payment.id }}</td>
                                    <td>{{ payment.shop.name }}</td>
                                    <td>{{ payment.total_price }}</td>
                                    <td>
                                        <span class="badge badge-info" v-if="payment.order_status === '1'">Start to
                                            Print</span>
                                        <span class="badge  badge-warning"
                                            v-else-if="payment.order_status === '2'">Print</span>
                                        <span class="badge  badge-danger"
                                            v-else-if="payment.order_status === '3'">Delivered</span>
                                        <span class="badge  badge-success"
                                            v-else-if="payment.order_status === '4'">Delivered</span>
                                    </td>
                                    <td>{{ new Date(payment.status_update_at).toLocaleDateString('en-GB',
                                        { year: 'numeric', month: 'long', day: '2-digit' }).replace(' ',
                                            '-').replace(',',
                                                '') }}</td>
                                    <td>{{ new Date(payment.created_at).toLocaleDateString('en-GB', {
                                        year: 'numeric',
                                        month: 'long', day: '2-digit'
                                    }).replace(' ', '-').replace(',', '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
