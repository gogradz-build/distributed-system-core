<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-6 login-img-col"></div>
                <div class="col-6">
                    <div class="row mt-4">
                        <a :href="route('index')">
                            <div class="col-4">
                                <img src="/images/icon/back_icon.png" alt="" class="back-icon">
                            </div>
                        </a>

                    </div>
                    <div class="row d-flex justify-content-center align-items-center login-form-row">
                        <div class="col-6 ">
                            <div class="row">
                                <div class="col-1 login-academy-title-icon">
                                    <img src="/images/icon/red-trangle.png" alt="">
                                </div>
                                <div class="col-10 login-academy-title">
                                    <p>NHR ACADEMY</p>
                                </div>
                            </div>
                            <div class="row login-title-row">
                                <h3>User Login</h3>
                                <p>Welcome back. Enter your credentials to access your account</p>
                            </div>
                            <form @submit.prevent="submit">

                                <div>
                                    <InputLabel for="email" value="Email" /><br>

                                    <TextInput id="email" type="email" class="mt-1 block w-full login-email-input"
                                        v-model="form.email" required autocomplete="username" />
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="password" value="Password" /><br>

                                    <TextInput id="password" type="password"
                                        class="mt-1 block w-full login-password-input" v-model="form.password" required
                                        autocomplete="current-password" />

                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>

                                <div class="block mt-4">
                                    <label class="flex items-center">
                                        <Checkbox name="remember" v-model:checked="form.remember" />
                                        <span class="ms-2 text-sm text-gray-600">Keep me signed me</span>
                                    </label>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <PrimaryButton class="ms-4 btn btn-primary w-100 m-0 login-btn"
                                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                            Log in
                                        </PrimaryButton>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <Link v-if="canResetPassword" :href="route('password.request')"
                                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Forgot your password?
                                    </Link>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
