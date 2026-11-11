<template>
    <Head>
        <title>{{ appName}} - Dashboard</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Tes Hari Ini</p>
                                    <h4 class="my-1 text-info">{{ totalExamToday }}</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-message-square-edit'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Transaksi Bulan Ini</p>
                                    <h4 class="my-1 text-success">{{ totalTransactionThisMonth }}</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-grid-alt' ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-3">Pengumuman</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Perihal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!announcementSummaries.length">
                                    <td colspan="7" align="center">Data Pengumuman kosong</td>
                                </tr>
                                <tr v-for="(announcementSummary, index) in announcementSummaries" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td>{{ announcementSummary.title }}</td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/user/announcements/${announcementSummary.id}`" class="ms-2"><i class='bx bx-grid-alt'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card radius-10 border-start border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-3">Ringkasan Tes Terakhir</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Judul Ujian</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!gradeSummaries.length">
                                    <td colspan="7" align="center">Tidak ada riwayat ujian soal</td>
                                </tr>
                                <tr v-for="(grade, index) in gradeSummaries" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td><span class="badge bg-primary">{{ grade.category.name }}</span></td>
                                    <td>{{ grade.exam.title }}</td>
                                    <td>{{ formatDateWithTime(grade.start_time) }}</td>
                                    <td>{{ formatDateWithTime(grade.end_time) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-3">Ringkasan Transaksi Terakhir</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Tanggal Transaksi</th>
                                <th>Kategori</th>
                                <th>Judul Ujian</th>
                                <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!transactionSummaries.length">
                                    <td colspan="6" align="center">Tidak ada riwayat traksaksi</td>
                                </tr>
                                <tr v-for="(transactionSummary, index) in transactionSummaries" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td>{{ transactionSummary.code }}</td>
                                    <td>{{ transactionSummary.created_at }}</td>
                                    <td>{{ transactionSummary.exam.category.name }}</td>
                                    <td>{{ transactionSummary.exam.title }}</td>
                                    <td>
                                        <span class="badge bg-warning" v-if="transactionSummary.transaction_status == 'pending'">Pending</span>
                                        <span class="badge bg-primary" v-if="transactionSummary.transaction_status == 'paid'">Lunas</span>
                                        <span class="badge bg-danger" v-if="transactionSummary.transaction_status == 'failed'">Gagal</span>
                                        <span class="badge bg-success" v-if="transactionSummary.transaction_status == 'done'">Selesai</span>
                                    </td>
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
    import LayoutUser from '../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    export default {
        // layout
        layout: LayoutUser,
        // register components
        components: {
            Head,
            Link,
        },
        // props
        props: {
            totalExamToday: Object,
            transactionSummaries: Object,
            gradeSummaries: Object,
            announcementSummaries: Object,
            totalTransactionThisMonth: Object,
        },
    }
</script>
