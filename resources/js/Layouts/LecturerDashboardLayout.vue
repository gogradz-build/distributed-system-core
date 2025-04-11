<script setup>

import { onMounted, ref, watch } from 'vue';
import TopNav from '@/Components/Admin/TopNav.vue';
import AdminFooter from '@/Components/Admin/AdminFooter.vue';
import SideBar from '@/Components/FrontEnd/LecturerDashboard/SideBar.vue';
import DeleteModalConfirmation from "@/Components/Admin/DeleteModalConfirmation.vue";


// const name = ref('');
// const email = ref('');
// const register_number = ref('');
// const imagePreview = ref('/images/studentDashboard/profile_avatar.jpg');

// const fetchLecturers = async () => {
//     try {
//         const response = await axios.get(route('lecture.profile'));
//         name.value = response.data.name;
//         email.value = response.data.email;
//         register_number.value = response.data.register_number;
//         console.log('ssssssssssssssssss', name.value)
//     } catch (error) {
//         console.error('Error fetching lecturers:', error);
//     }
// };

// onMounted(() => {
//     fetchLecturers();
// });

</script>

<template>
    <div class="wrapper">
        <TopNav />
        <!-- <SideBar :name="name" :email="email" :register-number="register_number" :imagePreview="imagePreview" /> -->
        <SideBar />

        <main>
            <div class="content-wrapper">
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <slot :showTostMessage="showTostMessage" />
                    </div>
                </div>
            </div>
        </main>
        <AdminFooter />
    </div>
</template>

<script>
export default {
    name: 'LecturerDashboardLayout',
    watch() {
        document.body.className = "hold-transition sidebar-mini";
    }
};
</script>
