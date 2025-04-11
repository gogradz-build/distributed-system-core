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
    axios.delete(`/shops/${deleteId}`)
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

// end of delete module toast

onMounted(() => {
    fetchRfs().then(() => {
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
function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#shopTable')) {
            $('#shopTable').DataTable().destroy();
        }
        $('#shopTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollCollapse: true,
            paging: true
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
                <h4 class="mt-2">SHOP</h4>
                <a :href="route('admin.create.shop')"><button type="button" class="btn btn-primary mt-4 mb-4 mr-2"><i
                            class="fas fa-solid fa-plus" style="color: #ffffff; margin-right: 5px;"></i> Add New
                        Shop</button></a>
                <!-- <button type="button" class="btn btn-success mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Shop Record</button> -->
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary ">

                    <div class="card-body">
                        <h6 style="font-weight: 700;" class="">SHOPS TABLE</h6>
                        <hr>
                        <!-- Add ID to the table -->
                        <table id="shopTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th >ID</th>
                                    <th :style="{ width: '25%' }">Shop Name</th>
                                    <th >Owner Name</th>
                                    <th >Mobile</th>
                                    <th >Tel No</th>
                                    <th :style="{ width: '20%' }">credit limit (.Rs)</th>
                                    <th :style="{ width: '20%' }">credit (.RS)</th>
                                    <th>Address</th>
                                    <th >Email</th>
                                    <!-- <th>Create At</th> -->
                                    <th style="text-align: center; width: 8%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(shop, index) in shops" :key="shop.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ shop.name }}</td>
                                    <td>{{ shop.owner_name }}</td>
                                    <td>{{ shop.contact ?? 'N/A' }}</td>
                                    <td>{{ shop.telephone_number ?? 'N/A' }}</td>
                                    <td>{{ shop.credit_limit }}</td>
                                    <td>{{ shop.credit ?? 'N/A' }}</td>
                                    <td>{{ shop.address }}</td>
                                    <td>{{ shop.email ?? 'N/A' }}</td>
                                    <!-- <td>{{ new Date(shop.created_at).toLocaleDateString() }}</td> -->
                                    <!-- <td>
                                        <span class="badge badge-pill badge-success"
                                            v-if="staffMember.is_active === 1">Active</span>
                                        <span class="badge badge-pill badge-success" v-else>Inactive</span>
                                    </td> -->

                                    <td class="justify-content-center align-items-center" :style="{ textAlign: 'center', verticalAlign: 'middle' }">
                                        <!-- <a :href="route('staff.changeActiveStatus', staffMember.id)"><i
                                                class="fas fa-regular fa-eye mr-4" style="color: #0a7c46;"></i></a> -->
                                        <a :href="route('shops.edit', shop.id)"><img src="/images/icon/edit.png" alt="" width="25px" height="25px" class="mr-2 table-action-icon"></a>
                                        <a @click="triggerModal(shop.id, 'Delete Shop')" style="cursor: pointer;">
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
        <!-- <div class="modal fade" id="createPayment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">SHOP PAYMENT DETAILS</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container
                        bg-body-tertiary">
                            <div class="row d-flex">
                                <div class="col-12">
                                    <h5 class="text-center mt-2">{Shop Name} Payments</h5>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <form>
                                        <div class="row">
                                            <div class="col-6 ">
                                                <p class="text-bold">SHOP DETAILS</p>
                                                <p>Owner Name :- <span> Kumara</span></p>
                                                <p>Owner Email :- <span> Kumara@gmail.com</span></p>
                                                <p>Contact Number:- <span> 0765823564</span></p>
                                                <p>Address:- <span> No542, Narahenpita</span></p>
                                            </div>
                                            <div class="col-lg-6 d-flex justify-content-end pr-4">
                                                <div class="mb-3">
                                                    <p class="text-bold">REF DETAILS</p>
                                                    <p>Ref Name :- <span>Kumara</span></p>
                                                    <p>ID :- <span>00456</span></p>
                                                    <p>Contact Number :- <span>07645856</span></p>
                                                    <p>Email Address :- <span>kumara@gmail.com</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <div class="mb-3">

                                                    <input type="text" class="form-control"
                                                        id="credit_limit" placeholder="Credit Limit">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <div class="mb-3">

                                                    <input type="number" class="form-control"
                                                        id="payment" placeholder="Payments">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <div class="">
                                                  <button class="btn btn-primary">Save Payments</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- ReusableModal Component -->
        <DeleteModel :isModalOpen="isModalOpen" :modalTitle="modalTitle" @close="isModalOpen = false"
            @confirm-delete="confirmDelete" />
    </AdminLayout>
</template>
<style>

</style>