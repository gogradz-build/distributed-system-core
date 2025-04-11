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
    console.log(deleteId);
    axios.delete(`/products/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                toast.warning(response.data.message);
                fetchProducts();
            } else if (response?.data?.warning) {
                toast.warning(response.data.warning);
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
    fetchProducts().then(() => {
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

const products = ref([]);

const fetchProducts = async () => {
    try {
        const response = await axios.get('/products');
        products.value = response.data;
        console.log(response);
    } catch (error) {
        console.error('Error fetching courses:', error);
    }
}
function initializeDataTable() {
    nextTick(() => {
        if ($.fn.DataTable.isDataTable('#productTable')) {
            $('#productTable').DataTable().destroy();
        }
        $('#productTable').DataTable({
            responsive: true,
            scrollX: true,
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
                <h4 class="mt-2">PRODUCTS</h4>
                <a :href="route('admin.create.products')"><button type="button"
                        class="btn btn-primary mt-4 mb-4 mr-2"><i class="fas fa-solid fa-plus"
                            style="color: #ffffff; margin-right: 5px;"></i> Add New
                        Product</button></a>
                <!-- <button type="button" class="btn btn-success mt-4 mb-4"><i class="fas fa-solid fa-print"
                        style="color: #ffffff; margin-right: 5px;"></i>Product Record</button> -->
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a :href="route('admin.dashboard')">Home</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-body">
                        <h6 style="font-weight: 700;" class="">PRODUCT TABLE</h6>
                        <hr>
                        <!-- Add ID to the table -->
                        <table id="productTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">#</th>
                                    <th style="width: 8%;">Product Code</th>
                                    <th style="width: 40%;">Product Name</th>
                                    <th style="width: 10%;">Brand Name</th>
                                    <th style="width: 10%;">Price (Rs.)</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 10%;">Create At</th>
                                    <th style="text-align: center; width: 8%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in products" :key="product.id">
                                    <td>{{ 1 + index }}</td>
                                    <td>{{ product.code }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.brand.name }}</td>
                                    <td>{{ product.price }}</td>
                                    <td>{{ product.warehouse_stocks.length > 0 ? product.warehouse_stocks[0].quantity :
                                        0 }}</td>
                                    <td>{{ new Date(product.created_at).toLocaleDateString() }}</td>
                                    <!-- <td>
                                        <span class="badge badge-pill badge-success"
                                            v-if="staffMember.is_active === 1">Active</span>
                                        <span class="badge badge-pill badge-success" v-else>Inactive</span>
                                    </td> -->
                                    <td class="justify-content-center align-items-center">
                                        <!-- <a :href="route('staff.changeActiveStatus', staffMember.id)"><i
                                                class="fas fa-regular fa-eye mr-4" style="color: #0a7c46;"></i></a> -->
                                        <a :href="route('products.edit', product.id)"><img src="/images/icon/edit.png" alt="" width="25px" height="25px" class="mr-2 table-action-icon"></a>
                                        <a @click="triggerModal(product.id, 'Delete Product')" style="cursor: pointer;">
                                            <img src="/images/icon/trash-bin.png" alt="" width="25px" height="25px" class="table-action-icon">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- ReusableModal Component -->
        <DeleteModel :isModalOpen="isModalOpen" :modalTitle="modalTitle" @close="isModalOpen = false"
            @confirm-delete="confirmDelete" />
    </AdminLayout>
</template>
