<template>
    <Head>
        <title>{{ appName }} - Ujian</title>
        style
    </Head>
    <!--start page wrapper -->
    <div class="fullscreen-wrapper">
        <div class="container" style="margin-top: 100px;margin-bottom: 100px;">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" :class="exam.show_question_number_navigation == 1 && questionLists[indexPage]['type'] == 1 ? 'col-lg-8' : 'col-lg-12'">
                <div class="card">
                    <div class="card-header bg-primary mb-3">
                        <div class="d-flex justify-content-between">
                            <div class="text-start">
                                <div v-if="exam.show_question_number == 1">
                                    <h4 class="mb-0 text-white"><span class="badge bg-danger" v-if="questionLists[indexPage]['type'] == 1">Question {{ questionLists[indexPage]['navigation_order'] }}</span></h4>
                                    <h4 class="mb-0 text-white"><span class="badge bg-danger" v-if="questionLists[indexPage]['type'] == 2">Directions</span></h4>
                                </div>
                            </div>
                            <div class="text-end">
                                <VueCountdown :transform="transform" :time="duration" @progress="handleChangeDuration" @end="showModalEndTimeExam = true" v-slot="{ hours, minutes, seconds }">
                                    <h4 class="mb-0 text-white">
                                        <span class="badge bg-danger">{{ hours }}</span> :
                                        <span class="badge bg-danger">{{ minutes }}</span> :
                                        <span class="badge bg-danger">{{ seconds }}</span>
                                    </h4>
                                </VueCountdown>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="questionLists[indexPage]">
                            <div v-if="questionLists[indexPage]['type'] == 1">
                                <div v-if="questionLists[indexPage]['audio_input'] == 1">
                                    <div style="min-height: 75px;" class="text-center">
                                        <h2>{{  }}</h2>
                                        <p>Audio play limit : {{ questionLists[indexPage]['audio_played_limit'] - questionLists[indexPage]['audio_played'] <= 0 ? 0 : questionLists[indexPage]['audio_played_limit'] - questionLists[indexPage]['audio_played']  }}</p>
                                        <div v-if="questionLists[indexPage]['audio_played'] == questionLists[indexPage]['audio_played_limit']">
                                            <VueCountdown v-if="questionLists[indexPage]['audio_answer_time'] > 0" :transform="transform" :time="questionLists[indexPage]['audio_answer_time']" @progress="handleChangeDurationListening" @end="endedAudio('nextPage')" v-slot="{ hours, minutes, seconds }">
                                                <p><b>Time Remaining</b></p>
                                                <h5 class="mb-0 text-white">
                                                    <span class="badge bg-danger">{{ hours }}</span> :
                                                    <span class="badge bg-danger">{{ minutes }}</span> :
                                                    <span class="badge bg-danger">{{ seconds }}</span>
                                                </h5>
                                            </VueCountdown>
                                        </div>
                                        <div v-if="(questionLists[indexPage]['audio_answer_time'] == 0 && questionLists[indexPage]['audio_played'] == questionLists[indexPage]['audio_played_limit']) || questionLists[indexPage]['audio_played'] > questionLists[indexPage]['audio_played_limit']">
                                            <audio controls controlsList="noremoteplayback nodownload noplaybackrate">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                        <div v-if="questionLists[indexPage]['audio_played'] < questionLists[indexPage]['audio_played_limit']" class="text-center" id="audioDiv">
                                            <button id="playButtonAudio" v-on:click="playAudio" class="btn btn-secondary btn-sm" style="display:none; position:absolute; z-index: 1;margin-top:10px;margin-left:15px; height:36px;width: 36px;border-radius: 50%;"><i class="bx bx-play"></i></button>
                                            <audio id="myAudio" v-on:playing="removePause" controls v-bind:autoplay="true" controlsList="noremoteplayback nodownload" v-on:ended="endedAudio()" v-bind:src="'/storage/upload_files/questions/' + questionLists[indexPage]['audio']">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    </div>
                                    <hr style="border:1px solid red;">
                                </div>

                                <div>
                                    <div v-html="questionLists[indexPage]['question']" class="prevent-select"></div>
                                </div>
                                <table>
                                    <tbody v-if="exam.type_option == 1">
                                        <tr v-for="(answer, index) in (questionLists[indexPage]['answer_order'].split(','))" :key="index">
                                            <span>
                                                <td width="50" style="padding: 10px;">
                                                    <button v-if="answer == questionLists[indexPage]['answer']"  @click="submitAnswer(questionLists[indexPage]['question_id'], answer)" class="btn btn-success text-white">{{ options[index] }}</button>
                                                    <button v-else @click="submitAnswer(questionLists[indexPage]['question_id'], answer)" class="btn btn-outline-primary w-100">{{ options[index] }}</button>
                                                </td>
                                                <td style="padding: 10px;">
                                                    <p class="prevent-select" v-if="exam.show_answer == 1" v-html="questionLists[indexPage]['option_' + answer]"></p>
                                                </td>
                                            </span>
                                        </tr>
                                    </tbody>
                                    <tbody v-if="exam.type_option == 2">
                                        <tr v-for="(answer, index) in (questionLists[indexPage]['answer_order'].split(','))" :key="index"  v-if="exam.show_answer == 1">
                                            <td width="30">
                                                <p>{{ options[index] }}.</p>
                                            </td>
                                            <td>
                                                <p v-html="questionLists[indexPage]['option_'+answer]"></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <br> -->
                                <div class="text-center" v-if="exam.type_option == 2">
                                    <span v-for="(answer, index) in answerOrder" :key="index">
                                        <button v-if="answer == questionLists[indexPage]['answer']" class="btn btn-success ms-3">{{ options[index] }}</button>
                                        <button v-else @click="submitAnswer(page, answer)" class="btn btn-outline-primary ms-3">{{ options[index] }}</button>
                                    </span>
                                </div>
                            </div>
                            <div v-if="questionLists[indexPage]['type'] == 2">
                                <div v-html="questionLists[indexPage]['direction']" class="prevent-select"></div>
                            </div>
                        </div>

                        <div v-else>
                            <div class="alert alert-danger border-0 shadow">
                                <i class="fa fa-exclamation-triangle"></i> Question Not Found!.
                            </div>
                            </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div class="text-start">
                                <div  v-if="exam.show_prev_next_button == 1">
                                    <button v-if="indexPage > 0 && questionLists[indexPage]['type'] == 1" @click="prevPage()" type="button" class="btn btn-primary me-3">Prev</button>
                                    <button v-if="indexPage < Object.keys(questionLists).length - 1" @click="nextPage()" type="button" class="btn btn-success">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="exam.show_question_number_navigation == 1 && questionLists[indexPage]['type'] == 1" class="col-md-12 col-sm-12 col-xs-12" :class="{ 'col-lg-4': exam.show_question_number_navigation == 1}">
                <div class="card">
                    <div class="card-header text-white bg-primary mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="mb-0 text-white">Navigation</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height: 450px; overflow-y: auto">
                        <div v-for="(question, index) in questionLists" :key="index">
                            <div v-if="question.type == 1" style="width: 20%; float: left;">
                                <div style="padding: 3px;">
                                    <button @click="clickQuestion(index)" v-if="index == indexPage" class="btn btn-primary w-100"><small>{{ question.navigation_order }}</small></button>
                                    <button @click="clickQuestion(index)" v-if="index != indexPage && question.answer == 0" class="btn btn-outline-primary w-100"><small>{{ question.navigation_order }}</small></button>
                                    <button @click="clickQuestion(index)" v-if="index != indexPage && question.answer != 0" class="btn btn-success w-100"><small>{{ question.navigation_order }}</small></button>
                                </div>
                            </div>
                            <div v-if="question.type == 2" style="width: 100%; float: left;">
                                <div style="padding: 3px;">
                                    <button v-if="index == indexPage" class="btn btn-warning w-100"><small>Directions</small></button>
                                    <button v-if="index != indexPage" class="btn btn-outline-warning w-100"><small>Directions</small></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button v-if="section == lastSection" @click="showModalEndExam = true" class="btn btn-danger btn-md border-0 shadow w-100">Akhiri Ujian</button>
                        <button v-else @click="endExam" class="btn btn-danger btn-md border-0 shadow w-100">Next Section</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--end page wrapper -->

    <!-- modal akhiri ujian -->
    <div v-if="showModalEndExam" class="modal fade" :class="{ 'show': showModalEndExam }" tabindex="-1" aria-hidden="true" style="display:block;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Akhiri Ujian ?</h5>
                </div>
                <div class="modal-body">
                    Setelah mengakhiri ujian, Anda tidak dapat kembali ke ujian ini lagi. Yakin akan mengakhiri ujian?
                </div>
                <div class="modal-footer">
                    <button @click="endExam" type="button" class="btn btn-primary">Ya, Akhiri</button>
                    <button @click="showModalEndExam = false" type="button" class="btn btn-secondary">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal waktu ujian berakhir -->
    <div v-if="showModalEndTimeExam" class="modal fade" :class="{ 'show': showModalEndTimeExam }" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" style="display:block;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Waktu Habis !</h5>
                </div>
                <div class="modal-body">
                    <div v-if="section == lastSection">Waktu ujian sudah berakhir!. Klik <strong class="fw-bold">Ya</strong> untuk mengakhiri ujian.</div>
                    <div v-else>Waktu ujian pada bagian ini sudah selesai!. Klik <strong class="fw-bold">Ya</strong> untuk lanjut ke bagian berikutnya.</div>
                </div>
                <div class="modal-footer">
                    <button @click="endExam" type="button" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    //import layout admin
    import LayoutUser from '../../../../Layouts/LayoutUser.vue';

    import axios from 'axios';

    import MathJax, { initMathJax, renderByMathjax } from 'mathjax-vue3'

    import {
        Head,
        Link
    } from '@inertiajs/inertia-vue3';

    //import ref
    import {
        ref
    } from 'vue';

    //import VueCountdown
    import VueCountdown from '@chenfengyuan/vue-countdown';

    //import inertia adapter
    import {
        Inertia
    } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    export default {
        //layout
        layout: LayoutUser,

        //register components
        components: {
            Head,
            Link,
            VueCountdown,
            MathJax
        },

        //props
        props: {
            id: String,
            page: Number,
            exam: Object,
            duration: Object,
            questionLists: Object,
            section: Number,
            lastSection: Number,
            grade: Object,
            indexPage: Object,
        },
        mounted() {
            let recaptchaScript = document.createElement('script')
            recaptchaScript.setAttribute('src', 'https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML')
            document.head.appendChild(recaptchaScript)

            function onMathJaxReady() {
                const el = document.getElementById('elementId')
                renderByMathjax(el)
            }
            initMathJax({}, onMathJaxReady);
        },

        setup(props) {            
            var indexPage = ref(props.indexPage);
            let options = ["A", "B", "C", "D", "E"];

            const counter = ref(0);
            const counterListening = ref(0);

            const duration = ref(props.duration);

            const handleChangeDuration = (() => {
                duration.value = duration.value - 1000;
                counter.value = counter.value + 1;
            });

            const handleChangeDurationListening = (() => {
                props.questionLists[indexPage.value].audio_answer_time = props.questionLists[indexPage.value].audio_answer_time - 1000;
                counterListening.value = counterListening.value + 1;
            });

            const prevPage = (() => {
                indexPage.value = indexPage.value - 1;
            });

            const nextPage = (() => {
                indexPage.value = indexPage.value + 1;
            });

            const clickQuestion = ((index) => {
                indexPage.value = index;
            });

            const confirm = (() => {
                if(props.grade.is_blocked != 1) {
                    let total_tolerance = parseInt(props.grade.total_tolerance) > 0 ? parseInt(props.grade.total_tolerance) - 1 : 0;
                    let tolerance = total_tolerance == 0 ? 'Tolerasi habis, ' : 'Toleransi Tersisa '+ total_tolerance +' kali lagi, ';
                    Swal.fire({
                        title: 'UJIAN AKAN DI DIBLOKIR JIKA ANDA MENINGGALKAN SESI UJIAN',
                        text: tolerance +" jika toleransi habis anda tidak dapat melanjutkan ujian dan harus menghubungi admin",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Tutup',
                        allowOutsideClick: false
                    })
                    .then((result) => {
                        if (result.isConfirmed) {

                            Inertia.post(`/user/exams/${props.exam.id}/decrement-tolerance`, {
                                grade_id: props.grade.id,
                            },{
                                onSuccess: () => {
                                    if(props.grade.total_tolerance == 0) {
                                        window.removeEventListener("blur", confirm);
                                        endExam('block');
                                    }
                                },
                            });                        
                        }
                    })
                }
            });

            const removePause = (() => {
                $('#playButtonAudio').hide();
            })

            const playAudio = (() => {
                var play = document.getElementById("myAudio"); 
                play.play();
            })

            const submitAnswer = ((question_id, answer) => {
                axios.post(`/user/exams/${props.exam.id}/exam-answer`, {
                    grade_id: props.grade.id,
                    question_id: question_id,
                    answer: answer,
                    number: indexPage.value,
                })
                .then(function (response) {
                    props.questionLists[indexPage.value]['answer'] = answer;
                    if(props.exam.next_question_automatically == 1) {
                        if(indexPage.value < Object.keys(props.questionLists).length - 1) {
                            nextPage();
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    alert('error silakan refresh halaman.');
                });
            });

            const endedAudio = ((description = '') => {
                axios.post(`/user/exams/${props.exam.id}/audio-played`, {
                    grade_id: props.grade.id,
                    question_id: props.questionLists[indexPage.value].question_id,
                })
                .then(function (response) {
                    props.questionLists[indexPage.value]['audio_played'] = props.questionLists[indexPage.value]['audio_played'] + 1;

                    if(props.questionLists[indexPage.value]['audio_played'] < props.questionLists[indexPage.value]['audio_played_limit']) {
                        $('#playButtonAudio').show();
                    }

                    if(description == 'nextPage') {
                        nextPage();
                    }
                })
                .catch(function (error) {
                    alert('error silakan refresh halaman.');
                });
            });

            //define state modal
            const showModalEndExam      = ref(false);
            const showModalEndTimeExam  = ref(false);

            //method endExam
            const endExam = ((block = '') => {
                //show success alert
                if(props.section == props.lastSection || block == 'block') {
                    Inertia.post(`/user/exams/${props.exam.id}/exam-end`, {
                        exam_id: props.exam.id,
                        grade_id: props.grade.id,
                        // last_page: props.lastPage,
                    },{
                        onSuccess: () => {
                            if(block == 'block') {
                                Swal.fire({
                                    title: 'UJIAN DIBLOKIR KARENA SUDAH MELEWATI BATAS TOLERANSI.',
                                    text: "Anda tidak dapat melanjutkan ujian, silakan hubungi admin",
                                    icon: 'warning',
                                    showCancelButton: false,
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Mengerti',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Success..',
                                    text: "Ujian Anda Selesai, Semoga Mendapatkan Nilai Terbaik.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Tutup',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }
                        },
                    });
                } else {
                    Inertia.get(`/user/exams/${props.exam.id}/sections/${props.section + 1}?nextsection=1`);

                    Swal.fire({
                        title: 'Success!',
                        text: 'Continue to the next section...',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }

            });

            window.addEventListener("blur", confirm);
            
            return {
                indexPage,
                options,
                removePause,
                playAudio,
                // answerOrder,
                duration,
                handleChangeDuration,
                handleChangeDurationListening,
                prevPage,
                nextPage,
                clickQuestion,
                confirm,
                submitAnswer,
                endedAudio,
                showModalEndExam,
                showModalEndTimeExam,
                endExam,
                fullscreen: false,
            }
        },
        methods: {
            transform(props) {
                Object.entries(props).forEach(([key, value]) => {
                    // Adds leading zero
                    const digits = value < 10 ? `0${value}` : value;
                    props[key] = `${digits}`;
                });

                return props;
            },
        },
    }
</script>
