<template>
    <Head>
        <title>{{ appName }} - Ujian</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Ujian</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Ujian</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <Link :href="`/user/categories/${exam.category_id}/exams`" class="btn btn-primary mt-2 mt-lg-0">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-12">
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                        <div v-html="$page.props.session.error"></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Informasi</h5>
                        </div>
                        <div class="card-body">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <div v-html="exam.description"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">Deskripsi Ujian</h5>
                        </div>
                        <div class="card-body">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th>Email</th>
                                        <td width="2px">:</td>
                                        <td>{{ $page.props.auth.user.email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <td>:</td>
                                        <td>{{ $page.props.auth.user.name }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>:</td>
                                        <td><span class="badge bg-primary">{{ exam.category.name }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Judul Ujian</th>
                                        <td>:</td>
                                        <td>{{ exam.title }}</td>
                                    </tr>
                                    <tr v-if="exam.duration_type == 1">
                                        <th>Durasi</th>
                                        <td>:</td>
                                        <td>{{ exam.duration }} Menit</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="text-center" v-if="!grade">
                                                <Link :href="`/user/exams/${exam.id}/exam-start`" class="btn btn-sm btn-primary">Mulai Kerjakan</Link>
                                            </div>
                                            <div class="text-center" v-else-if="grade && grade.is_finished == 0">
                                                <Link :href="`/user/exams/${exam.id}/exam-start`" class="btn btn-sm btn-warning">Lanjut Mengerjakan</Link>
                                            </div>
                                            <div class="text-center" v-else-if="grade && grade.is_finished == 1 && grade.is_blocked == 0">
                                                <Link :href="`/user/grades/${grade.id}`" class="btn btn-sm btn-success m-1">Lihat Hasil</Link>
                                                <Link :href="`/user/exams/${exam.id}/exam-start?repeat=1`" class="btn btn-sm btn-primary m-1">Ulangi Pengerjaan</Link>
                                            </div>
                                            <div v-else class="text-center">
                                                <Link :href="`/user/grades/${grade.id}`" class="btn btn-sm btn-success m-1">Lihat Hasil</Link>
                                                <button class="btn btn-sm btn-danger m-1">Ujian Di Blokir Silakan Hubungi Admin</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
            exam: Object,
            grade: Object,
        },
    }
</script>
