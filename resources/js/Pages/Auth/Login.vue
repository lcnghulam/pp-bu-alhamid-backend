<script setup>
import axios from 'axios';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

// Background Image
const backgroundImage = new URL('../../../assets/img/33833704511_94d448186e_k.jpg', import.meta.url).href;
document.body.style.backgroundImage = `url('${backgroundImage}')`;
document.body.style.backgroundSize = "cover";
document.body.style.backgroundPosition = "center";
document.body.style.backgroundRepeat = "no-repeat";

// State untuk Form
const email = ref('');
const password = ref('');
const remember = ref(false);
const loading = ref(false);

let notyf = null;

onMounted(() => {
    notyf = window.notyf;
    Object.assign(notyf.options, {
        duration: 5000,
        position: { x: 'right', y: 'top' },
        ripple: true,
        dismissible: true,
    });
});

// Fungsi Submit Login
const submit = async () => {
    loading.value = true;
    try {
        const response = await axios.post(route('login'), {
            email: email.value,
            password: password.value,
            remember: remember.value,
        });

        if (notyf) {
            notyf.success('Berhasil Login!');
        }

        router.visit('/', {
            preserveState: true,
            preserveScroll: true
        });
    } catch (error) {
        if (notyf) {
            notyf.error('Email atau Password Salah!');
        }
        loading.value = false;
    }
};
</script>

<template>

    <Head title="Login" />
    <main class="d-flex w-100 h-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center mt-4">
                            <h1 class="h2 auth-panel">Admin Panel</h1>
                            <p class="lead auth-panel">Login to your account to continue</p>
                        </div>

                        <div class="card auth-panel">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <form @submit.prevent="submit">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input v-model="email" class="form-control form-control-lg" type="email"
                                                placeholder="Enter your email" required />
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input v-model="password" class="form-control form-control-lg"
                                                type="password" placeholder="Enter your password" required />
                                            <small>
                                                <a href="/password/reset">Forgot password?</a>
                                            </small>
                                        </div>

                                        <div class="form-check align-items-center">
                                            <input v-model="remember" id="rememberMe" type="checkbox"
                                                class="form-check-input" />
                                            <label class="form-check-label text-small" for="rememberMe">
                                                Remember me
                                            </label>
                                        </div>

                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary"
                                                :class="{ 'opacity-50': loading }" :disabled="loading">
                                                {{ loading ? "Logging in..." : "Login" }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="text-center auth-panel mb-3">
                            Don't have an account? <a href="/register">Sign up</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
