<script setup>
import { ref, onMounted, watch, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";
import { useToast } from 'vue-toastification';


const roles = ref([]);
const toast = useToast();
const selectedRole = ref('');

// permission checkbox values
const selectedPermissions = ref({
    student: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    lecturer: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    course: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    module: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    subject: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    assignments: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    assignments_marks: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    exams: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    payments: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    events: {
        "create": false, "view": false, "update": false, "delete": false, all: false,
    },
    role: {
        "create": false, "view": false, "delete": false, all: false,
    },
    permission: {
        "view": false, "update": false,
    },
});

// handle checkbox change
const handleCheckboxChange = (category, permission) => {
    if (permission === "all") {
        const allStatus = selectedPermissions.value[category].all;
        Object.keys(selectedPermissions.value[category]).forEach((key) => {
            if (key !== 'all') {
                selectedPermissions.value[category][key] = allStatus;
            }
        });
    } else {
        selectedPermissions.value[category].all = Object.values(selectedPermissions.value[category])
            .slice(0, -1)
            .every(Boolean);
    }
};

const fetchRoles = async () => {
    try {
        const response = await axios.get('/roles');
        roles.value = response.data;
    } catch (error) {
        console.error('Error fetching roles:', error);
    }
};

const isSuperAdmin = computed(() => {
    const role = roles.value.find((r) => r.id === selectedRole.value);
    return role?.name.toLowerCase() === 'super admin';
});

watch(selectedRole, (newRole) => {
    if (newRole) {
        fetchRolePermissions(newRole);
    }
});

const fetchRolePermissions = async (roleId) => {
    try {
        const response = await axios.get(`/roles/${roleId}/permissions`);
        const assignedPermissions = response.data;

        Object.keys(selectedPermissions.value).forEach((role) => {
            Object.keys(selectedPermissions.value[role]).forEach((perm) => {
                selectedPermissions.value[role][perm] = false;
            });
        });

        assignedPermissions.forEach((permission) => {
            const [role, action] = permission.split(' ');
            if (selectedPermissions.value[role] && selectedPermissions.value[role][action] !== undefined) {
                selectedPermissions.value[role][action] = true;
            }
        });
    } catch (error) {
        console.error('Error fetching role permissions:', error);
        toast.error('Failed to fetch role permissions.');
    }
};




const savePermissions = () => {

    if (!selectedRole.value) {
        toast.error('No role selected.');
        return;
    }


    const flatPermissions = Object.entries(selectedPermissions.value)
        .flatMap(([key, perms]) =>
            Object.entries(perms)
                .filter(([permKey, value]) => value && permKey !== "all")
                .map(([permKey]) => `${key} ${permKey}`)
        );

    const payload = {
        permissions: flatPermissions
    };
    const roleId = selectedRole.value;
    axios.put(`/roles/${roleId}/give-permission`, payload)
        .then(response => {
            console.log('Permissions saved:', response);
            toast.success('Permissions successfully assigned to the role!');
        })
        .catch(error => {
            console.error('Error saving permissions:', error);
            toast.error(error.response?.data?.message || 'Failed to assign permissions.');
        });
};

onMounted(() => {
    fetchRoles();
});
</script>
<template>
    <AdminLayout>
        <!-- Page Header -->
        <div class="row mb-2">
            <div class="col-sm-6 mt-2">
                <h1 class="m-0">Role Permissions Management</h1>
            </div>
            <div class="col-sm-6 mt-2">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Permissions Management</li>
                </ol>
            </div>
        </div>

        <!-- Role Selection -->
        <div class="row">
            <div class="col-6">
                <div class="card card-success">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">Select Role</h3>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="">Role Name :</label>
                                <select class="form-control" v-model="selectedRole">
                                    <option value="" disabled selected>Select Role</option>
                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Permissions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">Set Permissions</h3>
                    </div>
                    <div class="card-body ">
                        <table class="table table-borderless ">
                            <thead>
                                <tr>
                                    <th class="h5 fw-bold" scope="col" style="width: 15%;">ID</th>
                                    <th class="h5 fw-bold" scope="col" style="width: 30%;">Task</th>
                                    <th class="h5 fw-bold" scope="col" style="width: 12%;">Create</th>
                                    <th class="h5 fw-bold" scope="col" style="width: 12%;">View</th>
                                    <th class="h5 fw-bold" scope="col" style="width: 12%;">Update</th>
                                    <th class="h5 fw-bold" scope="col" style="width: 12%;">Delete</th>
                                    <th class="h5 fw-bold" scope="col" style="width: 12%;">All</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through each role -->
                                <tr v-for="(permissions, role, index) in selectedPermissions" :key="role">
                                    <th scope="row">{{ index + 1 }}</th>
                                    <th scope="row">{{ role.charAt(0).toUpperCase() + role.slice(1) }}</th>

                                    <!-- Render checkboxes for each permission (Create, View, Update, Delete, All) -->
                                    <td><input type="checkbox" v-model="selectedPermissions[role].create"
                                            :disabled="role === 'permission'"
                                            @change="handleCheckboxChange(role, 'create')" /></td>
                                    <td><input type="checkbox" v-model="selectedPermissions[role].view"
                                            @change="handleCheckboxChange(role, 'view')" /></td>
                                    <td><input type="checkbox" v-model="selectedPermissions[role].update"
                                            :disabled="role === 'role'"
                                            @change="handleCheckboxChange(role, 'update')" /></td>
                                    <td><input type="checkbox" v-model="selectedPermissions[role].delete"
                                            :disabled="role === 'permission'"
                                            @change="handleCheckboxChange(role, 'delete')" /></td>
                                    <td><input type="checkbox" v-model="selectedPermissions[role].all"
                                            :disabled="role === 'permission'"
                                            @change="handleCheckboxChange(role, 'all')" /></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Save Button -->
                        <div class="row justify-content-end">
                            <button type="submit" class="btn btn-primary justify-content-end align-items-end"
                                style="width: 100px;" @click.prevent="savePermissions" :disabled="isSuperAdmin">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
