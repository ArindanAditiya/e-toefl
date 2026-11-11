<template>
    <Head>
        <title>{{ appName }} - Kategori</title>
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
                            <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <h4 class="mb-0 text-uppercase text-center">Pilih Kategori Sesuai Minatmu.....</h4>
            <hr/>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" v-for="category in categories">
                    <div class="card border-bottom border-3 border-0">
                        <img  v-bind:src="'/storage/upload_files/categories/' + category.thumbnail" class="card-img-top"/>
                        <div class="card-body">
                            <h5 class="card-title">{{ category.name }}</h5>
                            <p class="card-text">Pelajari dan pahami sebaik mungkin materi yang tersedia. Raih nilai terbaik di kategori ini.</p>
                            <hr>
                            <div class="text-center">
                                <Link :href="`/user/categories/${category.id}/exams`" class="btn btn-outline-primary">Selengkapnya</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutUser from '../../../../Layouts/Layout.vue';

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
            Link,
            Head,
        },

        // props
        props: {
            categories: Object
        },
    }
</script>
