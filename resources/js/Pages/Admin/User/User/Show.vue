<template>
    <Head>
        <title>{{ appName }} - Data User</title>
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
                            <li class="breadcrumb-item active" aria-current="page">Data User</li>
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
                    <div class="table-responsive">
                        <br>
                        <div v-if="$page.props.session.error" class="alert alert-danger border-0 alert-dismissible fade show">
                            <i class="fa fa-exclamation-triangle"></i>  {{ $page.props.session.error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div v-if="$page.props.session.success" class="alert alert-success border-0 alert-dismissible fade show">
                            <i class="fa fa-exclamation-triangle"></i>  {{ $page.props.session.success }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>:</td>
                                    <td>{{ user.name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>:</td>
                                    <td>{{ user.email }}</td>
                                </tr>
                                <tr>
                                    <th>Created at</th>
                                    <td>:</td>
                                    <td>{{ user.created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Status Akun</th>
                                    <td>:</td>
                                    <td>
                                        <span v-if="user.is_active == 1" class="badge bg-success">Active</span>
                                        <span v-else class="badge bg-danger">Non-active</span>
                                    </td>
                                </tr>
                                <tr v-if="user.is_active == 0 && user.student">
                                    <th>Kirim Verifikasi Akun Via WhatsApp</th>
                                    <td>:</td>
                                    <td><a :href="`https://wa.me/${user.student.phone_number}?text=${encodeURI('hallo, saya admin dari e-TOEFL, akun anda telah terdaftar di platform kami, berikut link verifikasi untuk aktivasi akun anda.\n\nNama: '+ user.name +'\nEmail: '+ user.email +'\n\n '+ verificationLink + '\n\n*jika link tidak bisa diklik, silakan copy dan pastekan link di browser anda.* \n\nterimakasih telah menjadi bagian dari e-TOEFL, semoga kami dapat membantu proses belajar anda.')}`">Klik Disini Untuk Mengirim Verifikasi Via WhatsApp</a></td>
                                </tr>

                                <tr v-if="user.is_active == 0 && user.student">
                                    <th>Kirim Reminder Aktivasi</th>
                                    <td>:</td>
                                    <td><Link :href="`/admin/users/${user.id}/activation-reminder`" class="btn btn-success btn-sm">Kirim Reminder</Link></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        // props
        props: {
            user: Object,
            verificationLink: String,
            errors: Object,
            session: Object
        },
    }
</script>
