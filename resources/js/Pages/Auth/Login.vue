<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const backgroundImage = new URL('../../../assets/img/33833704511_94d448186e_k.jpg', import.meta.url).href;

onMounted(() => {
    document.body.style.backgroundImage = `url('${backgroundImage}')`;
    document.body.style.backgroundSize = "cover";
    document.body.style.backgroundPosition = "center";
    document.body.style.backgroundRepeat = "no-repeat";
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
                                            <input v-model="form.email" class="form-control form-control-lg"
                                                type="email" name="email" placeholder="Enter your email" required />
                                            <div v-if="form.errors.email" class="text-danger mt-1">
                                                {{ form.errors.email }}
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input v-model="form.password" class="form-control form-control-lg"
                                                type="password" name="password" placeholder="Enter your password"
                                                required />
                                            <div v-if="form.errors.password" class="text-danger mt-1">
                                                {{ form.errors.password }}
                                            </div>
                                            <small>
                                                <a href="/password/reset">Forgot password?</a>
                                            </small>
                                        </div>

                                        <div class="form-check align-items-center">
                                            <input v-model="form.remember" id="rememberMe" type="checkbox"
                                                class="form-check-input" />
                                            <label class="form-check-label text-small" for="rememberMe">
                                                Remember me
                                            </label>
                                        </div>

                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary"
                                                :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                                                Login
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
