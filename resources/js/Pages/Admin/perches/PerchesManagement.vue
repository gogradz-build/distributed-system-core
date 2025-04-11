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

// Delete modal state
const isModalOpen = ref(false);
const modalTitle = ref('');
const deleteID = ref(null);

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
    axios.delete(`/purchases/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                toast.warning(response.data.message);
                fetchPurchase()
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

// end of delete module toast

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
        if ($.fn.DataTable.isDataTable('#purchaseTable')) {
            $('#purchaseTable').DataTable().destroy();
        }
        $('#purchaseTable').DataTable({
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
                <h4 class="mt-2">PURCHASE</h4>
                <a :href="route('admin.create.perches')"><button type="button"
                        class="btn btn-primary mt-4 mb-4 mr-2"><i class="fas fa-solid fa-plus"
                            style="color: #ffffff; margin-right: 5px;"></i> Add New
                        Purchase</button></a>
                <!-- <button type="button" class="btn btn-success mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Purchase Record</button> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Purchase</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">

                    <div class="card-body">
                        <h6 style="font-weight: 700;" class="">PURCHASE TABLE</h6>
                        <hr>
                        <!-- Add ID to the table -->
                        <table id="purchaseTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 20%;">Purchase Code</th>
                                    <th style="width: 20%;">Supplier Name</th>
                                    <th style="width: 20%;">Warehouse Name</th>
                                    <th style="width: 10%;">Paid Amount</th>
                                    <th style="width: 15%;">Payment Status</th>
                                    <!-- <th style="width: 10%;">Order Status</th> -->
                                    <th style="width: 25%;">Create At</th>
                                    <th style="width: 10%;">Action</th>
                                    <!-- <th>Status</th> -->
                                    <!-- <th style="text-align: center; width: 8%;">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(purchase, index) in purchases" :key="purchases.id">
                                    <td>{{ purchase.id }}</td>
                                    <td>{{ purchase.purchase_code ?? "N/A"}}</td>
                                    <td>{{ purchase.supplier.first_name }} {{ purchase.supplier.last_name }}</td>
                                    <td>{{ purchase.warehouse.name }}</td>
                                    <td>{{ purchase.received_amount }}</td>
                                    <!-- <td>
                                        <span v-if="purchase.payment_status === 1"
                                            class="badge bg-info">Confirmed</span>
                                        <span v-if="purchase.payment_status === 2"
                                            class="badge bg-warning">Pending</span>
                                        <span v-if="purchase.payment_status === 3"
                                            class="badge bg-success">Delivered</span>
                                    </td> -->
                                    <td>
                                        <span v-if="purchase.payment_status === 1" class="badge badge-success">Cash</span>
                                        <span v-if="purchase.payment_status === 2" class="badge badge-info">partially
                                            paid</span>
                                        <span v-if="purchase.payment_status === 3"
                                            class="badge badge-warning">Credit</span>
                                    </td>

                                    <!-- <td>
                                        <span class="badge badge-pill badge-success"
                                            v-if="staffMember.is_active === 1">Active</span>
                                        <span class="badge badge-pill badge-success" v-else>Inactive</span>
                                    </td> -->
                                    <td>{{ new Date(purchase.created_at).toLocaleDateString() }}</td>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <a :href="route('admin.create.perches.return', purchase.id)">
                                            <img src="/images/icon/return_icon.png" width="24px" height="24px"
                                                style="margin-right: 10px;" alt="" class="table-action-icon"></a>
                                        <a :href="route('purchases.edit', purchase.id)"><img src="/images/icon/edit.png" alt="" width="25px" height="25px" class="mr-2 table-action-icon"></a>
                                        <!-- <a @click="triggerModal(purchase.id, 'Delete Purchase')"
                                            style="cursor: pointer;">
                                            <i class="fas fa-solid fa-trash mr-4" style="color: #FF0000;"></i>
                                        </a> -->
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
