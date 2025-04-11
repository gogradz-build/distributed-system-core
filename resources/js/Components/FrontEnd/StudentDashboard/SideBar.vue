<script>
import { onMounted, ref } from 'vue';

export default {
  name: 'ProfilePicturePreview',
  methods: {
    readURL(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          document.querySelector('#wizardPicturePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    },
    isActiveRoute(routeNames){
      console.log(routeNames);
      const currentRoute = this.$page.component;
      if(Array.isArray(routeNames)){
        return routeNames.includes(currentRoute);
      }
      return currentRoute === routeNames;
    }
  },
  mounted() {
    const fileInput = document.querySelector('#wizard-picture');
    if (fileInput) {
      fileInput.addEventListener('change', (event) => {
        this.readURL(event.target);
      });
    }
  },
};
</script>
<template>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary  student-sidebar">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="/images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text  student-account-title">MY ACCOUNT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel student-user-panel mt-3 pb-3 " style="border: none;">
        <div class="row d-flex justify-content-center align-items-center">
          <div class="container">
            <div class="picture-container">
              <div class="picture">
                <img
                  src="/images/studentDashboard/profile_avatar.jpg"
                  class="picture-src" id="wizardPicturePreview" title="">
                <input type="file" id="wizard-picture" class="">
              </div>
            </div>
          </div>
        </div>
        <div class="row d-flex justify-content-center align-items-center mt-3">
          <div class="col-12 text-center">
            <h5>J.A.I.H.Sadun Rathnayaka</h5>
            <p>sadun456@gmail.com</p>
            <p>NHR-S456</p>
          </div>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item front-nav-link">
            <a :href="route('student.dashboard')" class="nav-link " :class="{ activeNav  : isActiveRoute(['FrontEnd/StudentDashboard/Dashboard']) }"         
              >
              <i class="fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item front-nav-link">
            <a :href="route('student.courses')" class="nav-link" :class="{ activeNav  : isActiveRoute(['FrontEnd/StudentDashboard/courses/AllCourses', 'FrontEnd/StudentDashboard/courses/CourseContent']) }" >
              <i class="fas fa-solid fa-graduation-cap"></i>
              <p>
                Courses
              </p>
            </a>
          </li>
          <li class="nav-item front-nav-link">
            <a :href="route('student.assignment')" class="nav-link" :class="{ activeNav  : isActiveRoute(['FrontEnd/StudentDashboard/Assignment/AssignmentCourses', 'FrontEnd/StudentDashboard/Assignment/AssignmentSubject', 'FrontEnd/StudentDashboard/Assignment/Assignment']) }" >
              <i class="nav-icon fas fa-solid fa-book-open"></i>
              <p>
                Assignments
              </p>
            </a>
          </li>
          <li class="nav-item front-nav-link">
            <a :href="route('student.exam.results')" class="nav-link" :class="{ activeNav  : isActiveRoute(['FrontEnd/StudentDashboard/examResults/ExamResult']) }" >
              <i class="nav-icon fas fa-solid fa-book-open"></i>
              <p>
                Exam Results
              </p>
            </a>
          </li>
          <li class="nav-item front-nav-link">
            <a :href="route('student.payment')" class="nav-link" :class="{ active: activeItem == 'payment' }" >
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Payments
              </p>
            </a>
          </li>
          <li class="nav-item front-nav-link">
            <a href="#" class="nav-link" :class="{ active: activeItem == 'setting' }" @click="setActive('setting')">
              <i class="nav-icon fas fa-solid fa-life-ring"></i>
              <p>
                Setting
              </p>
            </a>
          </li>
          <li class="nav-item front-nav-link">
            <a :href="route('student.profile')" class="nav-link" :class="{ active: activeItem == 'setting' }" @click="setActive('setting')">
              <i class="nav-icon fas fa-solid fa-user"></i>
              <p>
               Profile
              </p>
            </a>
          </li>
          <li class="nav-item front-nav-link">
            <a :href="route('logout')" method="post" class="nav-link" :class="{ active: activeItem == 'payment' }" @click="setActive('payment')">
              <i class="fas fa-solid fa-right-from-bracket"></i>
              <p>
                Log out
              </p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>

<style>
.nav-pills .nav-link.active {
  color: #fff;
  background-color: transparent !important;
}

.side-bar-nav-link {
  box-shadow: none !important;
}

.nav-treeview>.nav-item>.nav-link.active {
  color: #fff !important;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24);
}
</style>
