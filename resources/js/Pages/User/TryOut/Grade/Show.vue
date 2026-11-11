<template>
    <Head>
        <title>{{ appName}} - Riwayat Ujian</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Riwayat Ujian</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Riwayat Ujian</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <h5 class="mb-0">Informasi Ujian</h5>
                        </div>
                        <div class="ms-auto">
                            <Link href="/user/grades" class="btn btn-primary btn-sm">Kembali</Link>
                            <a :href="`/user/grades/${grade.id}/certificate`" target="_blank" class="btn btn-danger btn-sm m-1">Cetak Sertifikat</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th width="170px">Kategori</th>
                                    <td width="10px">:</td>
                                    <td><span class="badge bg-primary">{{ grade.category.name }}</span></td>
                                </tr>
                                <tr>
                                    <th>Judul Ujian</th>
                                    <td>:</td>
                                    <td>{{ grade.exam.title }}</td>
                                </tr>
                                <tr>
                                    <th>Durasi</th>
                                    <td>:</td>
                                    <td>{{ grade.duration }} Menit</td>
                                </tr>
                                <tr>
                                    <th>Waktu Mulai</th>
                                    <td>:</td>
                                    <td>{{ formatDateWithTime(grade.start_time) }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Selesai</th>
                                    <td>:</td>
                                    <td>{{ formatDateWithTime(grade.end_time) }}</td>
                                </tr>
                                <tr>
                                    <th>Nilai</th>
                                    <td>:</td>
                                    <td><h5>{{ grade.grade }}</h5></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="card" v-if="grade.exam.question_title.add_value_category == 1 && ( grade.exam.question_title.assessment_type == 1 || grade.exam.question_title.assessment_type == 2)">
                <div class="card-header bg-primary mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <h5 class="mb-0 text-white">Detail Nilai Per Kategori</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori Penilaian</th>
                                    <th>Total Benar</th>
                                    <th>Total Salah</th>
                                    <th>Nilai Konversi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="grade.grade_detail.length" v-for="(gradeDetail, index) in grade.grade_detail" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td>{{ gradeDetail.value_category.name }}</td>
                                    <td>{{ gradeDetail.total_correct }}</td>
                                    <td>{{ gradeDetail.total_wrong }}</td>
                                    <td>{{ gradeDetail.grade }}</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ grade.grade }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <h5 class="mb-0 text-white">Ranking di Ujian ini</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama</th>
                                    <th>Provinsi</th>
                                    <th>Kota</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(rankingExam, index) in rankingExams.data" :key="index">
                                    <td>
                                        <span class="badge bg-danger" v-if="index == 0 || index == 1 || index == 2"> {{ ++index + (rankingExams.current_page - 1) * rankingExams.per_page }}</span>
                                        <span class="badge bg-primary" v-else>{{ ++index + (rankingExams.current_page - 1) * rankingExams.per_page }}</span>
                                    </td>
                                    <td>{{ rankingExam.user.name }}</td>
                                    <td>{{ rankingExam.user.student.province.name }}</td>
                                    <td>{{ rankingExam.user.student.city.name }}</td>
                                    <th>{{ rankingExam.grade_old }}</th>
                                </tr>
                            </tbody>
                        </table>

                        <Pagination :links="rankingExams.links" align="end" />

                    </div>

                </div>
            </div>

            <div class="card" v-if="grade.exam.question_title.show_answer == 1">
                <div class="card-header bg-primary mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <h5 class="mb-0 text-white">Koreksi Kunci Jawaban</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table mb-0">
                        <tbody>
                            <tr v-for="(answer, index) in answers" :key="index" v-if="grade.grade >= grade.exam.question_title.minimum_grade">
                                <td>
                                    <div class="badge bg-primary">Nomor {{ ++index }}</div>
                                    <div v-if="answer.question_answer == answer.answer" class="badge rounded-pill text-success bg-light-success p-2 text-uppercase ms-2"><i class="bx bx-check"></i> Benar</div>
                                    <div v-if="answer.question_answer != answer.answer" class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase ms-2">X Salah</div>
                                    <br><br>
                                    <div v-html="answer.question"></div>
                                    <hr>
                                    <ol type="A">
                                        <li v-html="answer.option_1" :class="answer.answer == 1 ? 'text-danger fw-bold' : answer.question_answer == 1 ? 'text-success fw-bold' : ''"></li>
                                        <li v-html="answer.option_2" :class="answer.answer == 2 ? 'text-danger fw-bold' : answer.question_answer == 2 ? 'text-success fw-bold' : ''"></li>
                                        <li v-html="answer.option_3" :class="answer.answer == 3 ? 'text-danger fw-bold' : answer.question_answer == 3 ? 'text-success fw-bold' : ''"></li>
                                        <li v-html="answer.option_4" :class="answer.answer == 4 ? 'text-danger fw-bold' : answer.question_answer == 4 ? 'text-success fw-bold' : ''"></li>
                                        <li v-html="answer.option_5" :class="answer.answer == 5 ? 'text-danger fw-bold' : answer.question_answer == 5 ? 'text-success fw-bold' : ''"></li>
                                    </ol>
                                </td>
                            </tr>
                            <tr v-else>
                                <th>
                                   Untuk melihat kunci jawaban, nilai anda minimal harus {{ grade.exam.question_title.minimum_grade  }} 
                                </th>
                            </tr>
                        </tbody>
                    </table>
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

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

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
            Pagination
        },

        // props
        props: {
            grade: Object,
            rankingExams: Object,
            chart: Object,
            answers: Object,
        },
    }
</script>
