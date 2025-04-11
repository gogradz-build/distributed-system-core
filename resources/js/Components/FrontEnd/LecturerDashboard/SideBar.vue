<script>
import { onMounted, ref } from 'vue';

export default {
  name: 'ProfilePicturePreview',
  methods: {
    isActiveRoute(routeNames) {
      console.log(routeNames);
      const currentRoute = this.$page.component;
      if (Array.isArray(routeNames)) {
        return routeNames.includes(currentRoute);
      }
      return currentRoute === routeNames;
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
                <img src="/images/studentDashboard/profile_avatar.jpg" class="picture-src" id="wizardPicturePreview"
                  title="">
                <input type="file" id="wizard-picture" class="">
              </div>
            </div>
          </div>
        </div>
        <div class="row d-flex justify-content-center align-items-center mt-3">
          <div class="col-12 text-center">
            <h5>J.A.I.H.Kumara</h5>
            <p>kumara531@gmail.com</p>
            <p>NHR4561000</p>
          </div>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a :href="route('lecturer.dashboard')" class="nav-link front-nav-link"
              :class="{ activeNav: isActiveRoute(['FrontEnd/LecturerDashboard/Dashboard']) }"
              @click="setActive('dashboard')">
              <i class="fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a :href="route('lecturer.course.show')" class="nav-link front-nav-link"
              :class="{ activeNav: isActiveRoute(['FrontEnd/LecturerDashboard/CourseShow', 'FrontEnd/LecturerDashboard/SubjectsShow', 'FrontEnd/LecturerDashboard/AddNote']) }"
              @click="setActive('payment')">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Subject Note
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a :href="route('lecturer.course.assignment.courses')" class="nav-link front-nav-link"
              :class="{ activeNav: isActiveRoute(['FrontEnd/LecturerDashboard/Assignment/ShowCourses', 'FrontEnd/LecturerDashboard/Assignment/AssignmentSubject', 'FrontEnd/LecturerDashboard/Assignment/AssignmentPanel']) }"
              @click="setActive('payment')">
              <i class="nav-icon fas fa-solid fa-book-open"></i>
              <p>
                Assignments
              </p>
            </a>
          </li>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link  side-bar-nav-link" :class="{ activeNav: isActiveRoute([]) }"
            @click="setActive('Exam')">
              <i class="fas fa-solid fa-graduation-cap"></i>
              <p>
               Exam 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a :href="route('lecturer.add.exam.result')" class="nav-link" :class="{ activeNav: isActiveRoute(['FrontEnd/LecturerDashboard/Exam/AddExamResult']) }"
                  @click="setActive('exam-result')">
                  <p>Exam Result</p>
                </a>
              </li>
              <li class="nav-item">
                <a :href="route('admin.subject')" class="nav-link" :class="{ active: activeItem == 'subject' }"
                  @click="setActive('subject')">
                  <p>Report</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a :href="route('lecturer.profile')" class="nav-link front-nav-link" :class="{ activeNav: isActiveRoute(['FrontEnd/LecturerDashboard/profile/Profile']) }"
              @click="setActive('lecturer-profile')">
              <i class="nav-icon fas fa-solid fa-life-ring"></i>
              <p>
                My Profile
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a :href="route('logout')" method="post" class="nav-link front-nav-link"
              :class="{ active: activeItem == 'payment' }" @click="setActive('payment')">
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

[class*=sidebar-dark-] .nav-sidebar>.nav-item.menu-open>.nav-link, [class*=sidebar-dark-] .nav-sidebar>.nav-item:hover>.nav-link, [class*=sidebar-dark-] .nav-sidebar>.nav-item>.nav-link:focus {
    background-color: rgb(255 0 0 / 10%);
    color: #fff;
}
</style>
