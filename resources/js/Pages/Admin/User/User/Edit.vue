<template>
    <Head>
        <title>{{ appName }} - Edit User</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">User</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/admin/users" class="btn btn-primary mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <input type="hidden" class="form-control" v-model="form.id" :class="{ 'is-invalid': errors.id }">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" placeholder="Nama">
                            <div v-if="errors.name" class="invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" v-model="form.email" :class="{ 'is-invalid': errors.email }" placeholder="Email">
                            <div v-if="errors.email" class="invalid-feedback">
                                {{ errors.email }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">password</label>
                            <input type="password" class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }" placeholder="Password">
                            <div v-if="errors.password" class="invalid-feedback">
                                {{ errors.password }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" placeholder="Konfirmasi Password">
                            <div v-if="errors.password_confirmation" class="invalid-feedback">
                                {{ errors.password_confirmation }}
                            </div>
                        </div>
                        <div class="col-12" style="display: none">
                            <label class="form-label">Level</label>
                            <select v-model="form.level" :class="{ 'is-invalid': errors.level }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
                            <div v-if="errors.level" class="invalid-feedback">
                                {{ errors.level }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status Akun</label>
                            <select v-model="form.is_active" :class="{ 'is-invalid': errors.is_active }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Active</option>
                                <option value="0">Non Active</option>
                            </select>
                            <div v-if="errors.is_active" class="invalid-feedback">
                                {{ errors.is_active }}
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-5">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        //props
        props: {
            errors: Object,
            user: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                id: props.user.id,
                name: props.user.name,
                email: props.user.email,
                password: '',
                password_confirmation: '',
                level: props.user.level,
                is_active: props.user.is_active,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.put(`/admin/users/${props.user.id}`, {
                    // data
                    name: form.name,
                    id: form.id,
                    email: form.email,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                    level: form.level,
                    is_active: form.is_active,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'User Berhasil Disimpan!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
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
