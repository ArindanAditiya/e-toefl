<template>
    <Head>
        <title>{{ appName }} - Detail Transaksi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Transaksi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Transaksi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/admin/transactions" class="btn btn-primary mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <form @submit.prevent="submit">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th colspan="3">Rincian Transaksi</th>
                                    </tr>
                                    <tr>
                                        <td width="300px">Nama Lengkap</td>
                                        <td width="20px">:</td>
                                        <td>{{ transaction.user.name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td>{{ transaction.user.email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Provinsi</td>
                                        <td>:</td>
                                        <td>{{ transaction.user.student.province.name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kota/Kab</td>
                                        <td>:</td>
                                        <td>{{ transaction.user.student.city.name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kecamatan</td>
                                        <td>:</td>
                                        <td>{{ transaction.user.student.district.name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Desa/Kelurahan</td>
                                        <td>:</td>
                                        <td>{{ transaction.user.student.village.name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Desa/Kelurahan</td>
                                        <td>:</td>
                                        <td>{{ transaction.user.student.address }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Telepon (Whatsapp)</td>
                                        <td>:</td>
                                        <td>{{ transaction.user.student.phone_number }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Detail Transaksi</th>
                                    </tr>
                                    <tr>
                                        <td>No Faktur</td>
                                        <td>:</td>
                                        <td>{{ transaction.code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Faktur</td>
                                        <td>:</td>
                                        <td>{{ transaction.created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kategori Peminatan</td>
                                        <td>:</td>
                                        <td><span class="badge bg-primary">{{ transaction.exam.category.name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Judul Ujian</td>
                                        <td>:</td>
                                        <td>{{ transaction.exam.title }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Pembayaran</td>
                                        <td>:</td>
                                        <td>{{ formatPrice(transaction.total_purchases) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Maksimal Waktu Pembayaran</td>
                                        <td>:</td>
                                        <td>{{ transaction.maximum_payment_time }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Transaksi</td>
                                        <td>:</td>
                                        <td>{{ transaction.transaction_status }}</td>
                                    </tr>
                                    <tr v-if="transaction.transaction_status == 'done'">
                                        <td>Token</td>
                                        <td>:</td>
                                        <td>{{ transaction.voucher_token }}</td>
                                    </tr>
                                    <tr v-if="transaction.transaction_status == 'pending' || transaction.transaction_status == 'paid'">
                                        <th colspan="3">Aksi</th>
                                    </tr>
                                    <tr v-if="transaction.transaction_status == 'pending' || transaction.transaction_status == 'paid'">
                                        <td>Ubah Status Transaksi</td>
                                        <td>:</td>
                                        <td>
                                            <select class="form-control" v-model="form.transaction_status">
                                                <option value="">[ Pilih Statu Transaksi ]</option>
                                                <option value="pending">Pending</option>
                                                <option value="paid">Lunas</option>
                                                <option value="done">Selesai</option>
                                                <option value="failed">Gagal</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr v-if="transaction.transaction_status == 'pending' || transaction.transaction_status == 'paid'">
                                        <td colspan="3">
                                            <button type="submit" class="btn btn-primary px-5">Submit</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
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

        // props
        props: {
            transaction: Object
        },
        // initialization composition API
        setup(props) {
            const form = reactive({
                transaction_status: props.transaction.transaction_status,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.put(`/admin/transactions/${props.transaction.id}`, {
                    // data
                    transaction_status: form.transaction_status,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Transaksi Berhasil Diupdate!.',
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
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed().replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        }
    }
</script>
