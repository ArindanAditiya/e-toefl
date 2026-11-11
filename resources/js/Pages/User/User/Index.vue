<template>
    <Head>
        <title>{{ appName }} - Profile User</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Profile</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>{{ user.name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ user.email }}</td>
                                </tr>
                                <tr>
                                    <th>Status Akun</th>
                                    <td><span class="badge bg-primary">{{ user.is_active == 1 ? 'Aktif' : 'Non-Aktif' }}</span></td>
                                </tr>
                                <tr v-if="user.student">
                                    <th>Jenis Kelamin</th>
                                    <td>{{ user.student.gender == "M" ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr v-if="user.student">
                                    <th>Nomor Hp (WA)</th>
                                    <td>{{ user.student.phone_number }}</td>
                                </tr>
                                <tr v-if="user.student && user.student.province">
                                    <th>Provinsi</th>
                                    <td>{{ user.student.province.name }}</td>
                                </tr>
                                <tr v-if="user.student && user.student.city">
                                    <th>Kota/Kab</th>
                                    <td>{{ user.student.city.name }}</td>
                                </tr>
                                <tr v-if="user.student && user.student.district">
                                    <th>Kecamatan</th>
                                    <td>{{ user.student.district.name }}</td>
                                </tr>
                                <tr v-if="user.student && user.student.village">
                                    <th>Desa/Kel</th>
                                    <td>{{ user.student.village.name }}</td>
                                </tr>
                                <tr v-if="user.student">
                                    <th>Status Member</th>
                                    <td>{{ user.student.is_member == 0 ? 'Non-member' : 'Member' }}</td>
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
    import LayoutAdmin from '../../../Layouts/Layout.vue';

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

        //props
        props: {
            user: Object,
        },
    }
</script>
