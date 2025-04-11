<script setup>
import TopNav from '@/Components/Admin/TopNav.vue';
import AdminFooter from '@/Components/Admin/AdminFooter.vue';
import SideBar from '@/Components/Admin/SideBar.vue';
import DeleteModalConfirmation from "@/Components/Admin/DeleteModalConfirmation.vue";



</script>

<template>
    <div class="wrapper">
        <TopNav />
        <SideBar />
        <main>
            <div class="content-wrapper">
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <slot />
                    </div>
                </div>
            </div>
        </main>
        <AdminFooter />
    </div>
</template>

<script>
export default {
    name: 'AdminLayout',
    watch() {
        document.body.className = "hold-transition sidebar-mini";
    },
    components: {
        DeleteModalConfirmation,
    },
    data() {
        return {
            showModal: false,
            selectedStudentId: null,
            selectedRouteName: null,
        };
    },
};
</script>
<style>
.badge-success {
    color: #0AC074 !important;
    background-color: #CEF2E3 !important;
}

.badge-info {
    color: #0099FB !important;
    background-color: #CCEBFE !important;
}

.badge-danger {
    color: #F62947 !important;
    background-color: #FDD4DA !important;
}

.badge-warning {
    color: #92400e !important;
    background-color: #fef3c7 !important;
}

.table td {
    color: #6b7280 !important;
}

.table th {
    color: #6b7280 !important;
    background-color: #f9fafb !important;
}

.table-action-icon {
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 100%;
    transition: all 0.5s;
}

.table-action-icon:hover {
    border: 2px solid rgb(211, 109, 109);
    border-radius: 100%;
}
</style>