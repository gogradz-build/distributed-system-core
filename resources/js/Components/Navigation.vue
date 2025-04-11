<!-- resources/js/Components/Navigation.vue -->
<script setup>
import { Link,  usePage  } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);
</script>

<template>
    <nav class="navbar  navbar-expand-lg custom-nav">
            <div class="container-fluid custom-container-fluid ">
                <a class="navbar-brand" href="#"><img src="/images/logo.png" width="40" height="40" alt="Logo">NATIONS HR
                    ACADEMY</a>
                <button class="custom-toggler navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <img src="/images/menu/menu.svg" alt="" width="30px" height="30px" class="">
                </button>
                <div class="custom-collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-btnss nav-item custom-nav-item">
                            <a class="nav-btn  nav-link " aria-current="page" href="#"> HOME </a>
                        </li>
                        <li class="nav-btnss nav-item custom-nav-item">
                            <a class="nav-btn  nav-link " aria-current="page" href="#">PROGRAMS</a>
                        </li>
                        <li class="nav-btnss nav-item custom-nav-item">
                            <a class="nav-btn nav-link" href="#about">ABOUT</a>
                        </li>
                        <li class="nav-btnss nav-item custom-nav-item">
                            <a class="nav-btn nav-link" href="#contact">CONTACT</a>
                        </li>
                        <li class="nav-item btn-login ">
                            <a class="nav-btn nav-link" :href="(route('student.dashboard'))" v-if="isAuthenticated"><button type="button"
                                class="btn btn-outline-light button-dashboard">DASHBOARD</button></a>
                            <a class="nav-btn nav-link" :href="(route('login'))" v-else><button type="button"
                                    class="btn btn-outline-light">LOGIN</button></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
</template>
