<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { useToast } from 'vue-toastification';
import { onMounted, nextTick, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import AdminSalesStatusReturnUpdate from '@/Components/Admin/AdminSalesStatus&ReturnUpdate.vue';
import { defineProps } from 'vue';

const props = defineProps({sale_updated_message: String});
const toast = useToast();

if(props.sale_update_message){
    console.log(props.sale_updated_message);
}
// Delete modal state
const isModalOpen = ref(false);
const modalTitle = ref('');
const deleteID = ref(null);
const saleId = ref(null);
const statusId = ref(0);
const close = ref(false);
function triggerModal(sale_id, title) {
    deleteID.value = sale_id;
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
    axios.delete(`/sales/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                toast.warning(response.data.message);
                fetchRfs()
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
const print = () => {

}
// end of delete module toast
onMounted(() => {
    fetSales().then(() => {
        initializeDataTable();
    });

    fetchRfs();

    nextTick(() => {
        const successMessage = localStorage.getItem('successMessage');
        if (successMessage) {
            toast.success(successMessage);
            localStorage.removeItem('successMessage');
        }

    });

});

const shops = ref([]);

const fetchRfs = async () => {
    try {
        const response = await axios.get('/shops');
        shops.value = response.data;
        console.log(response);

    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}
const sales = ref([]);

const fetSales = async () => {
    try {
        const response = await axios.get('/sales');
        sales.value = response.data;
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}

const salesStatusReturnUpdate = async (id, status_id) => {
    // Get the modal element
    close.value = false;
    statusId.value = status_id;
    saleId.value = id;
    const modalElement = document.getElementById("update-sales-and-sales-return");
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

const modelClose = ()=>{
    close.value = true;
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
                <h4 class=" mt-2">SALES</h4>
                <a :href="route('admin.create.Sales')"><button type="button" class="btn btn-primary mt-4 mb-4 mr-2"><i
                            class="fas fa-solid fa-plus" style="color: #ffffff; margin-right: 5px;"></i> Create New
                        Sales</button></a>
                <!-- <button type="button" class="btn btn-success mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Sales Record</button> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Sales</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header card-header-background">
                        <h3 class="card-title">Sales Details</h3>
                    </div>
                    <div class="card-body">
                        <!-- Add ID to the table -->
                        <table id="shopTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">#</th>
                                    <th style="width: 8%;">Sale ID</th>
                                    <th style="width: 20%;">Shop Name</th>
                                    <th style="width: 20%;">Ref Name</th>
                                    <th style="width: 10%;">Oder Status</th>
                                    <th style="width: 10%;">Sub Total (RS)</th>
                                    <th style="width: 10%;">Payment (RS)</th>
                                    <th style="width: 10%;">Created Date</th>
                                    <th style="width: 20%;">Print</th>
                                    <th style="width: 100%;">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(sale, index ) in sales" :key="sale.sale_id">
                                    <td> {{ 1 + index }}</td>
                                    <td> {{ sale.sale_id }}</td>
                                    <td>{{ sale.shop.name }}</td>
                                    <td>{{ sale.ref_name }}</td>
                                    <td>
                                        <span v-if="+sale.order_status === 1" class="badge badge-info">Start To Print {{ sale.status_update_at }}</span>
                                        <span v-if="+sale.order_status === 2" class="badge badge-warning">Print {{ sale.status_update_at }}</span>
                                        <span v-if="+sale.order_status === 3" class="badge badge-danger">Delivered {{ sale.status_update_at }}</span>
                                        <span v-if="+sale.order_status === 4" class="badge badge-success">Completed {{ sale.status_update_at }}</span>
                                    </td>
                                    <td>{{ sale.total_price }}</td>
                                    <td>{{ sale.received_amount }}</td>
                                    <td>{{ sale.created_at }}</td>
                                    <td><a :href="route('sale.print', { sale: JSON.stringify(sale) })" target="_blank">
                                            <img src="/images/icon/print.png" width="30px" height="30px" alt=""
                                                @click="print" class="print-icon"></a>
                                    </td>

                                    <td class="d-flex">
                                        <img src="/images/icon/repeat.png" width="25px" height="25px"
                                            style="margin-right: 10px;" alt=""
                                            @click="salesStatusReturnUpdate(sale.sale_id, sale.order_status)"
                                            class="salesStatusReturnUpdate">
                                        <a :href="route('admin.create.sales.return', sale.sale_id)">
                                            <img src="/images/icon/return_icon.png" width="25px" height="25px"
                                                style="margin-right: 10px;" alt="" class="return-icon"></a>
                                        <a :href="route('sales.edit', sale.sale_id)"><img src="/images/icon/edit.png"
                                                width="30px" height="30px" style="margin-right: 10px;" alt="" class="edit-icon"></a>
                                        <a @click="triggerModal(sale.sale_id, 'Delete Sale')" style="cursor: pointer;">
                                            <img src="/images/icon/trash-bin.png" alt="" width="25px" height="25px" class="table-action-icon">
                                        </a>
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
    <!-- Modal -->
    <div class="modal fade" id="update-sales-and-sales-return" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">UPDATE SALES STATUS & SALES RETURN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="modelClose"></button>
                </div>
                <div class="modal-body">
                    <AdminSalesStatusReturnUpdate :sale_id="saleId" :fetchSales="fetSales" :statusId="statusId" :close="close"/>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.salesStatusReturnUpdate, .return-icon, .edit-icon, .print-icon {
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 100%;
    transition: all 0.5s;
}
.salesStatusReturnUpdate:hover ,.return-icon:hover, .edit-icon:hover , .print-icon:hover{
  border: 2px solid rgb(211, 109, 109);
  border-radius: 100%;
}

</style>