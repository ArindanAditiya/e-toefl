<template>
    <Head>
        <title>{{ appName }} - Registrasi</title>
    </Head>

    <div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                <div class="col mx-auto">
                    <div class="my-4 text-center">
                        <img src="/storage/upload_files/img/logo.png?v=2" style="max-height: 80px; width: auto; max-width: 320px; object-fit: contain;" alt="Logo e-TOEFL Ma'had Aly Tebuireng" />
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="border p-4 rounded">
                                <div class="text-center">
                                    <h3 class="">Registrasi</h3>
                                    <p>Sudah memiliki akun ? <Link href="/login">Login disini</Link>
                                    </p>
                                </div>

                                <!-- Google 1-Click Register -->
                                <div class="my-3 text-center">
                                    <a href="/auth/google" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-2" style="border-color: #CBD5E1; background-color: #FFFFFF; font-weight: 600; color: #1E293B; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-radius: 6px;">
                                        <svg width="20" height="20" viewBox="0 0 24 24">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                        </svg>
                                        Daftar Cepat dengan Google
                                    </a>
                                    <div class="d-flex align-items-center my-3">
                                        <hr class="flex-grow-1">
                                        <span class="mx-3 text-muted small fw-bold">ATAU ISI FORMULIR MANUAL</span>
                                        <hr class="flex-grow-1">
                                    </div>
                                </div>

                                <div class="form-body">
                                    <form @submit.prevent="submit" class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" v-model="form.name" :class="{ 'is-invalid': errors.name }" class="form-control" placeholder="Nama Lengkap">
                                            <div v-if="errors.name" class="invalid-feedback">
                                                {{ errors.name }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <select v-model="form.gender" :class="{ 'is-invalid': errors.gender }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option value="M">Laki-laki</option>
                                                <option value="F">Perempuan</option>
                                            </select>
                                            <div v-if="errors.gender" class="invalid-feedback">
                                                {{ errors.gender }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Provinsi</label>
                                            <select  @change="cityData" v-model="form.province_id" :class="{ 'is-invalid': errors.province_id }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option v-for="province in provinces" :value="province.id">
                                                    {{ province.name }}
                                                </option>
                                            </select>
                                            <div v-if="errors.province_id" class="invalid-feedback">
                                                {{ errors.province_id }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Kota / Kab</label>
                                            <select @change="districtData" v-model="form.city_id" :class="{ 'is-invalid': errors.city_id }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option v-for="city in form.cities" :value="city.id">
                                                {{ city.name }}
                                                </option>
                                            </select>

                                            <div v-if="errors.city_id" class="invalid-feedback">
                                                {{ errors.city_id }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Kecamatan</label>
                                            <select @change="villageData" v-model="form.district_id" :class="{ 'is-invalid': errors.district_id }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option v-for="district in form.districts" :value="district.id">
                                                {{ district.name }}
                                                </option>
                                            </select>
                                            <div v-if="errors.district_id" class="invalid-feedback">
                                                {{ errors.district_id }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Desa / Kelurahan</label>
                                            <select v-model="form.village_id" :class="{ 'is-invalid': errors.village_id }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option v-for="village in form.villages" :value="village.id">
                                                {{ village.name }}
                                                </option>
                                            </select>
                                            <div v-if="errors.village_id" class="invalid-feedback">
                                                {{ errors.village_id }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Alamat</label>
                                            <textarea cols="30" rows="10" v-model="form.address" :class="{ 'is-invalid': errors.address }" class="form-control" placeholder="Alamat RT/RW/No Rumah"></textarea>
                                            <div v-if="errors.address" class="invalid-feedback">
                                                {{ errors.address }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Email</label>
                                            <input type="email" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" placeholder="Email">
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Password</label>
                                            <input type="password" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" placeholder="Password">
                                            <div v-if="errors.password" class="invalid-feedback">
                                                {{ errors.password }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Konfirmasi Password</label>
                                            <input type="password" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" class="form-control" placeholder="Konfirmasi Password">
                                            <div v-if="errors.password_confirmation" class="invalid-feedback">
                                                {{ errors.password_confirmation }}
                                            </div>
                                        </div>

                                        <!-- Cloudflare Turnstile Widget -->
                                        <div class="col-12 text-center d-flex flex-column align-items-center my-2">
                                            <div class="cf-turnstile" :data-sitekey="$page.props.turnstile?.site_key || '1x00000000000000000000AA'" data-theme="light"></div>
                                            <div v-if="errors.turnstile" class="text-danger small mt-1">
                                                {{ errors.turnstile }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary" style="background-color: #007A1E; border-color: #007A1E; font-weight: 700;"><i class='bx bx-user'></i>Registrasi</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
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

    //import axios
    import axios from 'axios';

    export default {
        // layout
        layout: LayoutAuth,

        components: {
            Head,
            Link
        },
        data() {
            return {
                provinces: [],
                cities: []
            }
        },

        //props
        props: {
            errors: Object,
            session: Object,
            cities: Object,
        },
        mounted() {
            // get all provinces data
            axios.get('/region/province').then(response => {
                this.provinces = response.data;
            }).catch(error => console.error(error));
        },

        setup(props) {
            const form = reactive({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                province_id: '',
                city_id: '',
                district_id: '',
                village_id: '',
                address: '',
                phone_number: '',
                gender: '',
                // get from api
                cities: [],
                districts: [],
                villages: [],
            });

            const cityData = () => {
                form.city_id = '';
                form.district_id = '';
                form.village_id = '';
                axios.get(`/region/city/${form.province_id}`).then(response => {
                form.cities = response.data;
                }).catch(error => console.error(error));
            }

            const districtData = () => {
                form.district_id = '';
                form.village_id = '';
                axios.get(`/region/district/${form.city_id}`).then(response => {
                form.districts = response.data;
                }).catch(error => console.error(error));
            }

            const villageData = () => {
                form.village_id = '';
                axios.get(`/region/village/${form.district_id}`).then(response => {
                form.villages = response.data;
                }).catch(error => console.error(error));
            }

            // submit method
            const submit = () => {
                const turnstileInput = document.querySelector('[name="cf-turnstile-response"]');
                const turnstileToken = turnstileInput ? turnstileInput.value : '';

                // send data to server
                Inertia.post('/register', {
                    // data
                    name: form.name,
                    email: form.email,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                    province_id: form.province_id,
                    city_id: form.city_id,
                    district_id: form.district_id,
                    village_id: form.village_id,
                    address: form.address,
                    phone_number: form.phone_number,
                    gender: form.gender,
                    'cf-turnstile-response': turnstileToken,
                    turnstile_token: turnstileToken,
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
                cityData,
                districtData,
                villageData
            }
        }
    }
</script>
