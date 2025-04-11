<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const form = useForm({
    email: '',
    password: '',
});

const handleLogin = () => {

    console.log(form);

    form.post(route('admin.login'), {
        onSuccess: () => {
            useToast().success('Login successful');
        },
        onError: (errors) => {
            useToast().error('Login failed. Please check your credentials.');
        },
    });
};
</script>

<template>
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>Login</title>
    </head>

    <body class="login-page" cz-shortcut-listen="true" data-new-gr-c-s-check-loaded="14.1211.0" data-gr-ext-installed=""
        style="min-height: 466px;">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <img src="/images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle "
                        style="opacity: .8" width="100%" height="auto"><br>
                    <!-- <a :href="route('index')" class="h3" target="_blank"><b>MAHAJANA</b> HARDWARE</a> -->
                </div>
                <div class="card-body">
                    <form @submit.prevent="handleLogin">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email" fdprocessedid="3tga5f"
                                v-model="form.email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" fdprocessedid="va3c7l"
                                v-model="form.password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary " fdprocessedid="3v17pu">Sign
                                    In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <h5 class="mt-2 tech-company" >Powered by GoGradz</h5>
        <span id="PING_IFRAME_FORM_DETECTION" style="display: none;"></span>
    </body>

    </html>
</template>
<script>
export default {
    name: 'Login',

};
</script>
