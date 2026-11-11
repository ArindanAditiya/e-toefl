<template>
    <Head>
        <title>{{ appName}} - Data Ujian</title>
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
                            <li class="breadcrumb-item active" aria-current="page">Data Ujian</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <form @submit.prevent="handleSearch">
                            <div class="position-relative">
                                <input type="text" v-model="search" class="form-control ps-5 radius-20" placeholder="Cari Berdasarkan Judul...."> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                            </div>
                        </form>
                        <div class="ms-auto">
                            <Link href="/admin/exams/create" class="btn btn-primary mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i> Tambah Ujian</Link>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Judul Soal</th>
                                    <th>Judul Ujian</th>
                                    <th>Durasi</th>
                                    <th>Harga</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="center" colspan="8" v-if="!exams.data.length">Data Ujian kosong</td>
                                </tr>
                                <tr v-for="(exam, index) in exams.data" :key="index">
                                    <td>{{ ++index + (exams.current_page - 1) * exams.per_page }}</td>
                                    <td><span class="badge bg-primary">{{ exam.category.name }}</span></td>
                                    <td>{{ exam.question_title.name }}</td>
                                    <td>{{ exam.title }}</td>
                                    <td>{{ exam.duration }} Menit</td>
                                    <td>{{ formatPrice(exam.price) }}</td>
                                    <td>
                                        <span class="badge bg-danger" v-if="exam.grade_finished_count == 0">{{ exam.grade_finished_count }}</span>
                                        <span class="badge bg-primary" v-else>{{ exam.grade_finished_count }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/admin/exams/${exam.id}`" class="ms-1"><i class='bx bx-grid-alt'></i></Link>
                                            <Link :href="`/admin/exams/${exam.id}/edit`" class="ms-1"><i class='bx bxs-edit'></i></Link>
                                            <a href="#" @click.prevent="destroy(exam.id)" class="ms-1"><i class="bx bxs-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>

                    <Pagination :links="exams.links" align="end" />
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import ref from vue
    import {
        ref
    } from 'vue';

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
            Pagination
        },

        // props
        props: {
            exams: Object
        },

        // initialization composition API
        setup() {
            // define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/exams', {
                    //send params "search" with value from state "search"
                    search: search.value,
                })
            }

            const destroy = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/exams/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Ujian Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                search,
                handleSearch,
                destroy
            }
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed().replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        }
    }
</script>
