<script setup>
import TopNav from '@/Components/Admin/TopNav.vue';
import AdminFooter from '@/Components/Admin/AdminFooter.vue';
import SideBar from '@/Components/FrontEnd/StudentDashboard/SideBar.vue';
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
            <slot :showTostMessage="showTostMessage"/>
          </div>
        </div>
      </div>
    </main>
    <AdminFooter />
  </div>
</template>

<script>
export default {
  name: 'StudentDashboardLayout',
  watch() {
    document.body.className = "hold-transition sidebar-mini";
  }
};
</script>