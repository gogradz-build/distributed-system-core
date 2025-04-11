<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { useToast } from 'vue-toastification';
import { onMounted, nextTick, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const saleReturns = ref([]);
const toast = useToast();

const print = () => {

}

onMounted(() => {
    fetSaleReturns().then(() => {
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

const fetSaleReturns = async () => {
    try {
        const response = await axios.get('/saleReturns');
        saleReturns.value = response.data;

    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#shopTable')) {
            $('#shopTable').DataTable().destroy();
        }
        $('#shopTable').DataTable({
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
                <h4 class=" mt-2">SALE RETURNS</h4>
                <!-- <a :href="route('admin.create.Sales')"><button type="button" class="btn btn-secondary mt-4 mb-4 mr-2"><i
                            class="fas fa-solid fa-plus" style="color: #ffffff; margin-right: 5px;"></i> Create New
                        Sales</button></a>
                <button type="button" class="btn btn-secondary mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Sales Returns Record</button> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Sale Returns</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Sale Returns Details</h3>
                    </div>
                    <div class="card-body">
                        <!-- Add ID to the table -->
                        <table id="shopTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">Sale ID</th>
                                    <th style="width: 20%;">Date</th>
                                    <th style="width: 30%;">Shop Name</th>
                                    <th style="width: 20%;">Ref Name</th>
                                    <th style="width: 10%;">Sub Total (RS)</th>
                                    <th style="width: 10%;">Return Price(Rs)</th>
                                    <th style="width: 40%;">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(sale, index ) in saleReturns" :key="sale.sale_id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ sale.created_at }}</td>
                                    <td>{{ sale.shop_name }}</td>
                                    <td>{{ sale.ref_name }}</td>
                                    <td>{{ sale.total_price }}</td>
                                    <td>{{ sale.return_total_price }}</td>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <a :href="route('admin.create.sales.return', sale.sale_id)">              
                                             <img src="/images/icon/edit.png" alt="" width="25px" height="25px" class="mr-2 table-action-icon"></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
