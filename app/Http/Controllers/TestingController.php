<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam\Answer;
use App\Models\Exam\Exam;
use App\Models\Exam\ValueCategoryExam;
use App\Models\Lesson\{DetailValueCategory, ValueCategory};
use App\Models\Exam\GradeDetail;
use App\Models\Exam\Grade;
use DB;
use Ramsey\Uuid\Uuid as Generator;
use Carbon\Carbon;
use App\Models\Lesson\Question;
use App\Models\Transaction\Transaction;

class TestingController extends Controller
{
    public function index()
    {
        // $page =  1 - 1;
        // $section = 1;

        
        $exam = Exam::first();

        return $exam;
        // return $grade;
        $gradeAnswers = $grade['answers'];

        return $gradeAnswers;

        // return $gradeAnswers;

        $index = array_search('785a4f24-f3cb-43e4-9e58-289aa6b311a3', array_column($gradeAnswers, 'question_id'));

        $gradeAnswers[$index]['is_correct'] = 'D';
        $gradeAnswers[$index]['answer'] = '6';

        return $gradeAnswers;

        $questions = array_filter($gradeAnswers, function ($var) use($question_id) {
            return ($var['question_id'] == $question_id);
        });

        return $questions;

        $questions = empty($questions) ? [] : array_values($questions);

        return $questions;

        $totalQuetions = count($questions);
        $question = array_key_exists($page, $questions) ? $questions[$page] : [];

        return $question;

        return $totalAnswer;

        // $grade->end_time->diffInMilliseconds(Carbon::now());
        return $grade;
        // $exam = Exam::find('7c1667d5-0de0-4bdf-8749-d13dd9557d73');
        // $gradeAnswers = $grade['answers'];

        // // return $gradeAnswers;

        // $totalCorrect = array_filter($gradeAnswers, function ($var) {
        //     return ($var['is_correct'] == "Y");
        // });
        
        // $totalAnswer = array_filter($gradeAnswers, function ($var) {
        //     return ($var['answer'] != 0);
        // });

        // $gradeExam = 0;
        // foreach($totalAnswer as $correct) {
        //     $gradeExam += $correct['point_'.$correct['answer']];
        // }

        // return $gradeExam;
        // $totalCorrectPerSection = [];
        // for($i = 1; $i <= $exam->questionTitle->total_section; $i++) {
        //     $totalCorrect = array_filter($gradeAnswers, function ($var) use($i) {
        //         return ($var['is_correct'] == "Y" && $var['section'] == $i);
        //     });

        //     $totalCorrectPerSection[] = count($totalCorrect);
        // }

        // return $totalCorrectPerSection;
        // $data = Transaction::where([
        //     'user_id' => '62bcbaec-82a9-44f5-985b-e5abcc83ccca',
        //     'voucher_used' => 1
        //     ])
        //     ->where('voucher_expired_date', '>', Carbon::now())->get();

      
        // $dataSoal = [
        //     ["ℇ","ℰ","₻","₢","₦"],
        //     ["₥","℧","₮","ℓ","₰"],
        //     ["€","₻","⅊","₾","₫"],
        //     ["℄","℘","℣","℈","ℇ"],
        //     ["€","₾","₺","₹","℣"],
        //     ["ℐ","₥","€","℈","℘"],
        //     ["ℇ","₻","ℵ","℈","⅊"],
        //     ["Ω","₽","ℇ","₻","℣"],
        //     ["℈","₣","₿","₽","ℰ"],
        //     ["ℨ","℺","ℓ","₣","Ω"],
        // ];

        // $questionInsert = [];
        // $sectionNomor = 0;
        // for($indexSoal = 0; $indexSoal < count($dataSoal); $indexSoal++) {
        //     $sectionNomor++;
        //     // $arr = [1, 4, 6, 8];
        //     $arrayAsli = $dataSoal[$indexSoal];
        //     $arr = $dataSoal[$indexSoal];
        //     $section = $sectionNomor;

        //     $questionTitle = 'bd05f3b9-39b7-4beb-9723-d415fd39a3bb';

        //     $uniqueArray = [];
        //     for($i = 0; $i < count($arr); $i++) {
        //         for($i = 1; $i <= 500; $i++) {
        //             shuffle($arr);
        //             $uniqueArray[] = $arr;
        //         }
        //     }

        //     $generateValue = [];
        //     $kunciJawaban = [];


        //     foreach($uniqueArray as $array) {
        //         // unset($array[5]);
        //         // unset($array[6]);
        //         $unset = rand(0, 4);
        //         $indexHilang = array_search($array[$unset], $arrayAsli);
        //         unset($array[$unset]);
        //         $generateValue[] = implode(' ', $array)." = ".$this->getOption($indexHilang).$indexHilang + 1;
        //         $kunciJawaban[] = implode('', $array)." = ".$this->getOption($indexHilang);
        //     }

        //     $datagenerate =  array_values(array_unique($generateValue));
        //     $dataKunciJawaban =  array_values(array_unique($kunciJawaban));

        //     foreach($datagenerate as $index => $value) {
        //         if($index >= 0 && $index <= 49) {
        //             $question = explode(" ", $value);
        //             $answer = $value[strlen($value) - 1];

        //             $table = '<table style="border-collapse: collapse; width: 50%; height: 46px; border-color: #3598db; border-style: solid; margin-left: auto; margin-right: auto;" border="3">
        //                         <tbody>
        //                         <tr style="height: 22.4px; background-color: #3598db; border-color: #3598db;">
        //                         <td style="width: 100.142%; height: 22.4px; text-align: center;" colspan="5"><span style="color: #ffffff;"><strong>KOLOM '.$section.'</strong></span></td>
        //                         </tr>
        //                         <tr style="height: 22.4px;">
        //                         <td style="width: 20.1133%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">'.$arrayAsli[0].'</span></td>
        //                         <td style="width: 20.1133%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">'.$arrayAsli[1].'</span></td>
        //                         <td style="width: 20.1133%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">'.$arrayAsli[2].'</span></td>
        //                         <td style="width: 20.1133%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">'.$arrayAsli[3].'</span></td>
        //                         <td style="width: 19.6884%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">'.$arrayAsli[4].'</span></td>
        //                         </tr>
        //                         <tr style="height: 22.4px;">
        //                         <td style="width: 20.1133%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">A</span></td>
        //                         <td style="width: 20.1133%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">B</span></td>
        //                         <td style="width: 20.1133%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">C</span></td>
        //                         <td style="width: 20.1133%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">D</span></td>
        //                         <td style="width: 19.6884%; height: 30px; text-align: center; border: 2px solid #3598db;"><span style="font-size: 18pt;">E</span></td>
        //                         </tr>
        //                         </tbody>
        //                         </table>
        //                         <p>&nbsp;</p>
        //                         <table style="border-collapse: collapse; width: 50%; height: 49px; border-color: #3598db; border-style: solid; margin-left: auto; margin-right: auto;" border="3">
        //                         <tbody>
        //                         <tr style="height: 20px;">
        //                         <td style="width: 108.455px; height: 30px; text-align: center; border: 3px solid #3598db;"><span style="font-size: 18pt;">'.$question[0].'</span></td>
        //                         <td style="width: 108.364px; height: 30px; text-align: center; border: 3px solid #3598db;"><span style="font-size: 18pt;">'.$question[1].'</span></td>
        //                         <td style="width: 108.341px; height: 30px; text-align: center; border: 3px solid #3598db;"><span style="font-size: 18pt;">'.$question[2].'</span></td>
        //                         <td style="width: 108.352px; height: 30px; text-align: center; border: 3px solid #3598db;"><span style="font-size: 18pt;">'.$question[3].'</span></td>
        //                         </tr>
        //                         </tbody>
        //                         </table>
        //                         <p>&nbsp;</p>';


        //                 Question::insert([
        //                     'id' => Generator::uuid4()->toString(),
        //                     'question_title_id7be4091b-c336-477b-b310-3b00ad21806c          'value_category_id' => null,
        //                     'question' => $table,
        //                     'option_1' => $arrayAsli[0],
        //                     'option_2' => $arrayAsli[1],
        //                     'option_3' => $arrayAsli[2],
        //                     'option_4' => $arrayAsli[3],
        //                     'option_5' => $arrayAsli[4],
        //                     'answer' => $answer,
        //                     'section' => $section,
        //                     'created_at' => Carbon::now()->addMinutes($section * 10),
        //                     'updated_at' => Carbon::now()->addMinutes($section * 10),
        //                 ]);

        //         }
        //     }

        //     echo "--------------------------------------- ".$section . "<br>";

        //     foreach($dataKunciJawaban as $index => $value) {
        //         if($index >= 0 && $index <= 49) {
        //             echo $value."<br>";
        //         }
        //     }
        // }

    }

    public function getOption($number)
    {
        switch ($number) {
            case 0:
                return 'A';
                break;
            case 1:
                return 'B';
                break;
            case 2:
                return 'C';
                break;
            case 3:
                return 'D';
                break;
            default:
                return 'E';
                break;
        }
    }

    public function jsonRelation()
    {
        // $grades[] = [
        //     'id' => 1,
        //     'description' => 'testing'
        // ];


        // return $grades;
        $exam = \App\Models\Exam\Exam::find('4752946b-2948-497c-92e5-1be24d533f7e');

        $grade = Grade::with(['gradeDetail'])->find('272a49f1-5327-4f69-bccc-5131bfb1912f');

        return $grade;

        // for($i = 0; $i < 500; $i++) {
        //     $grade->forceFill([
        //         'answers->'.$i.'->is_correct' => 'Y',
        //     ])->save();
        // }
        // return $grade;
        $gradeAnswers = $grade['answers'];

        $totalCorrectPerSection = [];
        for($i = 0; $i < $exam->questionTitle->total_section; $i++) {
            $totalCorrect = array_filter($gradeAnswers, function ($var) use($i) {
                return ($var['is_correct'] == "Y" && $var['section'] == $i);
            });

            $totalCorrectPerSection[] = count($totalCorrect);
        }

        $totalCorrect = array_filter($gradeAnswers, function ($var) {
            return ($var['is_correct'] == "Y");
        });

        $totalWrong = array_filter($gradeAnswers, function ($var) {
            return ($var['is_correct'] == "N");
        });

        $count_correct_answer = count($totalCorrect);
        $grade_exam = round($count_correct_answer / count($grade['answers']) * 100, 2);

        $grade->end_time = Carbon::now();
        $grade->total_correct = $count_correct_answer;
        $grade->grade = $grade_exam;
        $grade->is_finished = 1;
        $grade->update();

        $valueCategories = ValueCategory::where('lesson_id', $exam->lesson_id)->get();

        if($exam->questionTitle->add_value_category == 1) {

            $grades = [];

            foreach($valueCategories as $index => $valueCategory) {
                if($exam->questionTitle->assessment_type == 1) {
                    $totalCorrect = array_filter($gradeAnswers, function ($var) use($valueCategory) {
                        return ($var['value_category_id'] == $valueCategory->id && $var['is_correct'] == "Y");
                    });

                    $totalWrong = array_filter($gradeAnswers, function ($var) use($valueCategory) {
                        return ($var['value_category_id'] == $valueCategory->id && $var['is_correct'] == "N");
                    });
                }

                $totalCorrectFinal = count($totalCorrect);
                $totalWrongFinal = count($totalWrong);
                $totalQuestion = (int) $totalCorrectFinal + (int) $totalWrongFinal;

                $grades[] = [
                    'category_id' => $exam->category_id,
                    'lesson_category_id' => $exam->lesson_category_id,
                    'lesson_id' => $exam->lesson_id,
                    'exam_id' => $exam->id,
                    'total_correct' => $totalCorrectFinal,
                    'total_wrong' => $totalWrongFinal,
                    'value_category_id' => $valueCategory->id,
                    'value_category_name' => $valueCategory->name,
                    'value_category_assessment_type' => $valueCategory->assessment_type,
                ];

                if($valueCategory->assessment_type == 1) {
                    $grades[$index]['grade'] =($totalCorrectFinal / $totalQuestion) * 100;
                }

                if($valueCategory->assessment_type == 2) {
                    $grades[$index]['grade'] = $totalCorrectFinal;
                }

                if($valueCategory->assessment_type == 3) {
                    $selisihGradien = abs($this->slope($totalCorrectPerSection, $exam->questionTitle->total_section - 1));
                    $grades[$index]['grade'] = (100 - $selisihGradien * 50) < 0 ? 0 : 100 - $selisihGradien * 50;
                }
            }

            $result = [];

            foreach($grades as $gradeData) {
                $detailValueCategories = DetailValueCategory::where('value_category_id', $gradeData['value_category_id'])->get();
                $finalGrade = 0;
                $finalCategoryGrade = 'KS';
                foreach($detailValueCategories as $detailValueCategory) {
                    if($gradeData['grade'] >= $detailValueCategory->min_grade && $gradeData['grade'] <= $detailValueCategory->max_grade) {
                        $finalGrade = $detailValueCategory->final_grade;
                        $finalCategoryGrade = $detailValueCategory->category_grade;
                    }
                }

                $result[] = [
                    'id' => Generator::uuid4()->toString(),
                    'category_id' => $gradeData['category_id'],
                    'lesson_category_id' => $gradeData['lesson_category_id'],
                    'lesson_id' => $gradeData['lesson_id'],
                    'exam_id' => $gradeData['exam_id'],
                    'user_id' => auth()->user()->id,
                    'value_category_id' => $gradeData['value_category_id'],
                    'grade_id' => $grade->id,
                    'total_correct' => $gradeData['total_correct'],
                    'total_wrong' => $gradeData['total_wrong'],
                    'grade_category' => $finalCategoryGrade,
                    'grade' => $finalGrade,
                ];
            }

            GradeDetail::insert($result);
            return $result;
        }
    }

    public function localStorage()
    {
        $grade = Grade::first();

        return $grade;
        $questions = array_column($grade->answers, 'question_id');


        $data = [];
        foreach($questions as $question) {
            $data[] = Question::find($question);
        }

        return $data;
    }

    function slope($valuesIn, $period)
    {
        $valuesOut = array();

        $startIdx = 0;
        $endIdx = count($valuesIn) - 1;

        $sumX = $period * ( $period - 1 ) * 0.5;
        $sumXSqr = $period * ( $period - 1 ) * ( 2 * $period - 1 ) / 6;
        $divisor = $sumX * $sumX - $period * $sumXSqr;

        for ( $today = $startIdx, $outIdx = 0; $today <= $endIdx; $today++, $outIdx++ ) {
            $sumXY = 0;
            $sumY = 0;
            if ( $today >= $period - 1 ) {
            for( $aux = $period; $aux-- != 0;) {
                $sumY += $tempValue = $valuesIn[$today - $aux];
                $sumXY += (double)$aux * $tempValue;
            }

            $valuesOut[$outIdx] = ( $period * $sumXY - $sumX * $sumY) == 0 ? 0 : ( $period * $sumXY - $sumX * $sumY) / $divisor;
            }
        }

        return $valuesOut[$outIdx - 1];
    }

    public function onprogress()
    {
        return inertia('OnProgress/OnProgress');
    }

    public function testingWa()
    {
        $token = '_0Zi4K@S!3YUr9Cc4UiV';
        $curl = curl_init();

        $phoneNumber = '083814305092';
        $message = "*Mohon dibaca dan dipahami!*\n\n_Hallo, saya admin dari e-TOEFL, akun anda telah terdaftar di platform kami, berikut link verifikasi untuk aktivasi akun anda._\n\nNama: Natalia Sukmalina\nEmail: natalia.sukmalina@gmail.com\n\nBerikut link verifikasi anda\nhttps://ujicoba.online/user/271373d8-0ed8-47a2-837a-e849b96ac0bd/activation \n\n*Jika link tidak bisa diklik, silakan copy dan paste dibrowser anda.*\n\n_terimakasih telah menjadi bagian dari kami, semoga e-TOEFL dapat membantu proses belajar anda. aamiin._";
        
        $data = [
            'target' => $phoneNumber,
            'message' => $message
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)),
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$token.''
        ),
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
}
