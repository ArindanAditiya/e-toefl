<template>
    <Head>
        <title>{{ appName }} - Login</title>
    </Head>

    <div class="authentication-reset-password d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-lg-12 border-end">
                            <div class="card-body">
                                <form @submit.prevent="submit" class="row g-3">
                                    <div class="p-5">
                                        <center><img v-bind:src="'/storage/upload_files/img/' + logo + '?v=2'" style="max-height: 80px; width: auto; max-width: 320px; object-fit: contain;" alt="Logo e-TOEFL Ma'had Aly Tebuireng" />
                                        <h4 class="mt-5 font-weight-bold">Login</h4></center>
                                        <p class="text-muted">Silakan login dengan email dan password yang sudah anda daftarkan.</p>
                                        <div class="mb-3 mt-0">
                                            <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                                                <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                                            </div>

                                            <div v-if="$page.props.session.success" class="alert alert-success border-0">
                                                <i class="fa fa-exclamation-triangle"></i> <div v-html="$page.props.session.success"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 mt-0">
                                            <label class="form-label">Email</label>
                                            <input type="email" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" placeholder="Email">
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" placeholder="Password">
                                            <div v-if="errors.password" class="invalid-feedback">
                                                {{ errors.password }}
                                            </div>
                                        </div>

                                        <!-- Cloudflare Turnstile Widget -->
                                        <div class="mb-3 d-flex flex-column align-items-center">
                                            <div class="cf-turnstile" :data-sitekey="$page.props.turnstile?.site_key || '1x00000000000000000000AA'" data-theme="light"></div>
                                            <div v-if="errors.turnstile" class="text-danger small mt-1">
                                                {{ errors.turnstile }}
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary" style="background-color: #007A1E; border-color: #007A1E; font-weight: 700;">Login</button>
                                        </div>

                                        <!-- Google OAuth 1-Click Login -->
                                        <div class="my-3 text-center">
                                            <div class="d-flex align-items-center my-3">
                                                <hr class="flex-grow-1">
                                                <span class="mx-3 text-muted small fw-bold">ATAU</span>
                                                <hr class="flex-grow-1">
                                            </div>
                                            <a href="/auth/google" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-2" style="border-color: #CBD5E1; background-color: #FFFFFF; font-weight: 600; color: #1E293B; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-radius: 6px;">
                                                <svg width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                                </svg>
                                                Lanjutkan dengan Google
                                            </a>
                                        </div>

                                        <div class="text-center">
                                            <br>
                                            <p class="text-muted">
                                                Belum punya akun ? <Link href="/register">Daftar disini</Link><br><br>
                                                Lupa Password ? <Link href="/user/forgot-password">Klik Disini</Link><br><br>
                                            </p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout
    import LayoutAuth from '../../Layouts/Auth.vue';

    // import Head form Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    // import link
    import { Link } from '@inertiajs/inertia-vue3';

    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAuth,

        components: {
            Head,
            Link
        },

        //props
        props: {
            errors: Object,
            session: Object
        },

        data: {
        logo: 'logo.png'
        },


        setup() {
            const form = reactive({
                email: '',
                password: ''
            });

            // submit method
            const submit = () => {
                const turnstileInput = document.querySelector('[name="cf-turnstile-response"]');
                const turnstileToken = turnstileInput ? turnstileInput.value : '';

                // send data to server
                Inertia.post('/login', {
                    // data
                    email: form.email,
                    password: form.password,
                    'cf-turnstile-response': turnstileToken,
                    turnstile_token: turnstileToken,
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
            }
        }
    }
</script>
