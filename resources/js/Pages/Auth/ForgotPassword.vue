<template>
    <Head>
        <title>{{ appName }} - Forgot Password</title>
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
                                        <h3 class="">Reset Password</h3>
                                        <p>Sudah memiliki akun ? <Link href="/login">Login disini</Link></p>
                                        <p class="text-muted">Masukkan alamat email aktif yang terdaftar pada akun e-TOEFL Anda untuk menerima kata sandi baru.</p>
                                        <div class="mb-3 mt-0">
                                            <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                                                <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                                            </div>

                                            <div v-if="$page.props.session.success" class="alert alert-success border-0">
                                                <i class="fa fa-exclamation-triangle"></i>  {{ $page.props.session.success }}
                                            </div>
                                        </div>
                                        <div class="mb-3 mt-0">
                                            <label class="form-label">Alamat Email Terdaftar</label>
                                            <input type="email" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" placeholder="Contoh: santri@mahatbi.or.id" required>
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>

                                        <!-- Cloudflare Turnstile Widget -->
                                        <div class="mb-3 d-flex flex-column align-items-center">
                                            <div id="turnstile-container" class="cf-turnstile" :data-sitekey="$page.props.turnstile?.site_key || '0x4AAAAAAGOEQBX1qQLAmZSs'" data-theme="light"></div>
                                            <input type="hidden" name="cf-turnstile-response" :value="turnstileToken">
                                            <div v-if="errors.turnstile" class="text-danger small mt-1">
                                                {{ errors.turnstile }}
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary" style="background-color: #007A1E; border-color: #007A1E; font-weight: 700;">Kirim Kata Sandi Baru</button>
                                        </div>

                                        <div class="text-center">
                                            <br>
                                            <p class="text-muted">
                                                Kendala akses akun? <a :href="`${formatWaLink(dynamicWa)}?text=${encodeURI('Hallo, Admin e-TOEFL Ma\'had Aly Tebuireng, saya ingin bantuan pemulihan akses akun saya:\n\nNama:\nEmail:\n\nTerima kasih.')}`" target="_blank">Hubungi Admin di WhatsApp</a>
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

    //import reactive, ref, onMounted
    import { reactive, ref, onMounted } from 'vue';

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
            session: Object,
            user: Object,
        },

        setup(props) {
            const form = reactive({
                email: '',
            });

            const turnstileToken = ref('');

            onMounted(() => {
                const renderTurnstile = () => {
                    if (window.turnstile) {
                        const el = document.getElementById('turnstile-container');
                        if (el && el.childElementCount === 0) {
                            try {
                                window.turnstile.render('#turnstile-container', {
                                    sitekey: props.session?.turnstile_site_key || '0x4AAAAAAGOEQBX1qQLAmZSs',
                                    theme: 'light',
                                    callback: (token) => {
                                        turnstileToken.value = token;
                                    },
                                    'expired-callback': () => {
                                        turnstileToken.value = '';
                                    }
                                });
                            } catch (e) {
                                console.warn('Turnstile render notice:', e);
                            }
                        }
                    } else {
                        setTimeout(renderTurnstile, 300);
                    }
                };
                renderTurnstile();
            });

            // submit method
            const submit = () => {
                const turnstileInput = document.querySelector('[name="cf-turnstile-response"]');
                const token = turnstileToken.value || (turnstileInput ? turnstileInput.value : '');

                // send data to server
                Inertia.post('/user/forgot-password', {
                    // data
                    email: form.email,
                    'cf-turnstile-response': token,
                    turnstile_token: token,
                });
            }

            // return form state and submit method
            return {
                form,
                turnstileToken,
                submit,
            }
        }
    }
</script>
