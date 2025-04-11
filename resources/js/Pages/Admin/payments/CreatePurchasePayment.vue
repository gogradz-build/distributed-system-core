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
onMounted(() => {
    fetchPurchase().then(() => {
        initializeDataTable();
    });;
    nextTick(() => {
        const successMessage = localStorage.getItem('successMessage');
        if (successMessage) {
            toast.success(successMessage);
            localStorage.removeItem('successMessage');
        }

    });

});

const purchases = ref([]);

const fetchPurchase = async () => {
    try {
        const response = await axios.get('/purchases');
        purchases.value = response.data;
        console.log(response);
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}
function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#purchasesTable')) {
            $('#purchasesTable').DataTable().destroy();
        }
        $('#purchasesTable').DataTable({
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
                <h4 class="mt-2">PURCHASE PAYMENTS</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Purchase Payment</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">

                    <div class="card-body">
                        <h6 style="font-weight: 700;" class="">PURCHASE PAYMENT TABLE</h6>
                        <hr>
                        <!-- Add ID to the table -->
                        <table id="purchasesTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 20%;">Purchase Code</th>
                                    <th style="width: 25%;">Supplier Name</th>
                                    <!-- <th style="width: 20%;">Warehouse Name</th> -->
                                    <th style="width: 10%;">Payment Type</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 15%;">Paid Amount</th>
                                    <th style="width: 15%;">Total Amount</th>
                                    <th style="width: 10%;">Action</th>
                                    <!-- <th style="text-align: center; width: 8%;">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(purchase, index) in purchases" :key="purchases.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ purchase.purchase_code ?? "N/A" }}</td>
                                    <td>{{ purchase.supplier.first_name }} {{ purchase.supplier.last_name }}</td>
                                    <td>
                                        <span v-if="purchase.payment_status === 1"
                                            class="badge badge-success">Cash</span>
                                        <span v-if="purchase.payment_status === 2" class="badge badge-info">partially
                                            paid</span>
                                        <span v-if="purchase.payment_status === 3"
                                            class="badge badge-warning">Credit</span>
                                    </td>
                                    <td>
                                        <span v-if="purchase.payment_status === 1"
                                            class="badge badge-info">Confirmed</span>
                                        <span v-if="purchase.payment_status === 2"
                                            class="badge badge-warning">Pending</span>
                                        <span v-if="purchase.payment_status === 3"
                                            class="badge badge-success">Delivered</span>
                                    </td>
                                    <!-- <td>{{ purchase.warehouse.name }}</td> -->
                                    <td>{{ purchase.received_amount }}</td>
                                    <td>{{ purchase.total_price }}</td>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <a :href="route('make.purchase.payment', purchase.id)"> <img
                                                src="/images/icon/edit.png" alt="" width="25px" height="25px"
                                                class="mr-2 table-action-icon"></a>
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
