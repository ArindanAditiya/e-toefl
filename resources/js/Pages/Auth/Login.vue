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
                                        <center><img v-bind:src="'/storage/upload_files/img/' + logo" />
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
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Login</button>
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
                // send data to server
                Inertia.post('/login', {
                    // data
                    email: form.email,
                    password: form.password,
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
