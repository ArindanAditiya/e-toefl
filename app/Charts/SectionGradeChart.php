<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class SectionGradeChart
{
    protected $chart;

    public function __construct($chart, $grade)
    {
        $this->chart = $chart;
        $this->grade = $grade;
    }

    public function build(): array
    {
        $gradeAnswers = $this->grade->answers;

        $totalCorrectPerSection = [];
        $generateLabel = [];

        for($i = 0; $i < $this->grade->exam->questionTitle->total_section; $i++) {
            $totalCorrect = array_filter($gradeAnswers, function ($var) use($i) {
                return ($var['is_correct'] == "Y" && $var['section'] == $i);
            });

            $totalCorrectPerSection[] = count($totalCorrect);
            $generateLabel[] = 'Kolom '.$i + 1;
        }

        return $this->chart->areaChart()
            ->setTitle('Grafik Nilai Per Kolom')
            ->setSubtitle('Menampilkan Data Jawaban Yang Benar Per Kolom')
            ->addData('Jawaban Benar', $totalCorrectPerSection)
            ->setMarkers(['#FF5722', '#E040FB'], 7, 10)
            ->setXAxis($generateLabel)
            ->toVue();
    }
}
