<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { onMounted, nextTick, ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';


const stocks = ref([]);

onMounted(() => {
    fetchStock().then(() => {
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
const hasStocks = computed(() => {
    return stocks.value.length > 0;
});

function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#stockReportTable')) {
            $('#stockReportTable').DataTable().destroy();
        }
        $('#stockReportTable').DataTable({
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

const fetchStock = async () => {
    try {
        const response = await axios.get('/stock');
        stocks.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

</script>
<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="mt-2">STOCK REPORT</h4>
                <a :href="route('admin.stock.report')"><button type="button" class="btn btn-success mt-4 mb-4" :disabled="!hasStocks"><i
                            class="fas fa-solid fa-print" style="color: #ffffff; margin-right: 5px;" ></i>Stock
                        Report</button></a>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Stock Report</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-body">
                        <h6 style="font-weight: 700;" class=""> STOCK REPORT TABLE</h6>
                        <hr>
                        <!-- Add ID to the table -->
                        <table id="stockReportTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">ID</th>
                                    <th style="width: 20%;">Warehouse Name</th>
                                    <th style="width: 17%;">Product Code</th>
                                    <th style="width: 40%;">Product Name</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 10%;">Product Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(stock, index) in stocks" :key="stock.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ stock.warehouse.name }}</td>
                                    <td>{{ stock.product.code }}</td>
                                    <td>{{ stock.product.name }}</td>
                                    <td>{{ stock.quantity }}</td>
                                    <td>{{ stock.product.price }}</td>
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
