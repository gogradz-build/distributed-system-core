<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModel from '@/Layouts/DeleteModel.vue';
import { ref, computed, onMounted, nextTick } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';

const role = ref('');
const toast = useToast();
const roles = ref([]);

// delete module toast
const isModalOpen = ref(false);
const modalTitle = ref('');
const deleteID = ref(null);

function triggerModal(id, url, title) {
    deleteID.value = id;
    modalTitle.value = title;
    isModalOpen.value = true;
}

// Handle deletion after confirmation
function confirmDelete() {
    if (deleteID.value) {
        deleteRole(deleteID.value);
        isModalOpen.value = false; // Close the modal
    }
}
// end of delete module toast

const reversedRoles = computed(() => {
    return roles.value.slice().reverse();
});

const saveRole = () => {
    const payload = {
        role: role.value,
    };

    axios.post('/roles', payload)
        .then(response => {

            const newRole = response.data.role;

            toast.success(response.data.status || "Role created successfully");
            roles.value.push({
                id: newRole.id,
                name: newRole.name
            });

            role.value = '';
        })
        .catch(error => {
            console.error('Error saving role:', error);
            toast.error(error.response?.data?.error || "An error occurred while saving the role.");
        });
};

const deleteRole = (deleteId) => {
    axios.delete(`/roles/${deleteId}`)
        .then(response => {
            if (response?.data?.message) {
                toast.warning('Role deleted successfully');
                fetchRoles();
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


const fetchRoles = async () => {
    try {
        const response = await axios.get('/roles');
        roles.value = response.data;
        nextTick(() => {
            $('#permissionsTable').DataTable();
        });
    } catch (error) {
        console.error('Error fetching roles:', error);
    }
};

const fetchRolePermissions = async () => {
};

onMounted(() => {
    fetchRoles();
});
</script>

<template>
    <AdminLayout>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Role Management</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Role Management</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-6">
                <div class="card card-success">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">Create Role</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="roleName">Role Name :</label>
                                <input type="text" id="roleName" class="form-control" v-model="role" required>
                            </div>
                            <div class="row  justify-content-end">
                                <button type="submit" class="btn btn-primary justify-content-end align-items-end w-25"
                                    @click.prevent="saveRole">Save</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">Roles</h3>
                    </div>
                    <div class="card-body">
                        <!-- Add ID to the table -->
                        <table id="permissionsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">ID</th>
                                    <th style="width: 70%;">Role</th>
                                    <th style="width: 10%; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(role, index) in reversedRoles" :key="role.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ role.name }}</td>
                                    <td style="text-align: center">
                                        <button type="button" class="btn mr-2"  style="cursor: pointer"
                                            :disabled="role.name.toLowerCase() === 'super admin'"
                                            @click="triggerModal(role.id, 'Delete Role')" >
                                            <i class="fas fa-solid fa-trash mr-4" style="color: #FF0000; "></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <DeleteModel :isModalOpen="isModalOpen" :modalTitle="modalTitle" @close="isModalOpen = false"
            @confirm-delete="confirmDelete" />
    </AdminLayout>
</template>
