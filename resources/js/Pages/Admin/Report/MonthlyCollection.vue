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

const initializeDataTable = () => {
    $('#perchesTable').DataTable({
        responsive: true,
        scrollX: true,
        scrollY: '300px',
        scrollCollapse: true,
        paging: true,
    });
};

const hasSale = computed(() => {
    return reports.value.sales? true : false;
});

const fetchShop = async () => {
    try {
        const response = await axios.get('/refs');
        refs.value = response.data;
        console.error(refs);
        nextTick(() => {

            $('#perchesTable').DataTable({
                responsive: true,
                scrollX: true,
                scrollY: '300px',
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
        toast.warning('Please select a ref');
    }
    else {
        try {
            const params = { paymentStatus, ref_id }
            if (startDate) params.start_date = startDate;
            if (endDate) params.end_date = endDate;

            const response = await axios.get(route('get.sales.collection.by.ref', { paymentStatus, ref_id, params }));
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

const handleReportDownload = () => {
    if (!ref_id.value) {
        toast.warning('Please select a ref');
    } else {
        window.location.href = route('admin.collection.report.by.ref', {
            ref_id: ref_id.value,
            status_id: paymentStatus.value,
            start_date: startDate.value,
            end_date: endDate.value
        });
    }
};

</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-3">SALES COLLECTION BY REF</h4>
                <a href="#" @click.prevent="handleReportDownload"><button type="button"
                        class="btn btn-success mt-4 mb-4"  :disabled="!hasSale"><i class="fas fa-solid fa-print"
                            style="color: #ffffff; margin-right: 5px;"></i>Collection
                        Report</button></a>
            </div>
            <div class="col-sm-6 mt-3">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active"><a href="">Report</a></li>
                    <li class="breadcrumb-item active">Sales Collection by Ref</li>
                </ol>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 ">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6 col-12">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01"><img src="/images/icon/user.png"
                                    alt="" width="20px"></label>
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
                            <label class="input-group-text" for="inputGroupSelect01"><img src="/images/icon/home.png"
                                    alt="" width="20px"></label>
                            <select class="form-select" aria-label="Default select example" v-model="paymentStatus">
                                <option value=1>All</option>
                                <option value=2>Pending</option>
                                <option value=3>Completed</option>
                            </select>
                        </div>
                    </div>
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
                <div class="card ">
                    <div class="card-header">
                        <h6 style="font-weight: 700;" class="">Collection Details</h6>
                    </div>
                    <div class="card-body">
                        <table id="refTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width:">ID</th>
                                    <th style="width:">Invoice ID</th>
                                    <th style="width:">Shop Name</th>
                                    <th style="width:">Paid Amount</th>
                                    <th style="width:">Credit Amount</th>
                                    <th style="width:">Status</th>
                                    <th style="width:">Status Date</th>
                                    <th style="width:">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(payment, index) in reports.sales" :key="ref.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>MH{{ payment.id }}</td>
                                    <td>{{ payment.shop.name }}</td>
                                    <td>{{ payment.received_amount }}</td>
                                    <td>{{ payment.total_price - payment.received_amount }}</td>
                                    <td>
                                        <span class="badge  badge-info" v-if="payment.order_status === '1'">Start to
                                            Print</span>
                                        <span class="badge  badge-warning"
                                            v-else-if="payment.order_status === '2'">Print</span>
                                        <span class="badge badge-success"
                                            v-else-if="payment.order_status === '3'">Delivered</span>
                                    </td>
                                    <td>{{ new Date(payment.status_update_at).toLocaleDateString('en-GB', {
                                        year:
                                            'numeric', month: 'long', day: '2-digit'
                                    }).replace(' ', '-').replace(',', '')
                                    }}
                                    </td>
                                    <td>{{ new Date(payment.created_at).toLocaleDateString('en-GB', {
                                        year:
                                            'numeric', month: 'long', day: '2-digit'
                                    }).replace(' ', '-').replace(',', '')
                                    }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
