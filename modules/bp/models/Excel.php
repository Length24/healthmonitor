<?php

namespace app\modules\bp\models;

use yii\base\Model;
use Yii;

class Excel extends Model
{
    public array $dataSet = [];
    protected $titleHeaderStyleArray = [
        'fill' => [
            'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['argb' => 'FFCFCFCF'],
        ],
        'font' => [
            'bold' => true,
            'size' => 12,
            'name' => 'Arial'
        ]
    ];
    protected $headerStyleArray = [
        'fill' => [
            'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['argb' => 'FFD8D8D8'],
        ],
        'font' => [
            'bold' => true,
            'size' => 8,
            'name' => 'Arial'
        ]
    ];

    public static function createExcel($dataSet)
    {
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $dataByWeek = Bp::makeItIntoWeeks($dataSet);
        //Set title
        $excel->getDefaultStyle()->getFont()->setName('Arial')->setSize(8);
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle(\Yii::t('app', "title"), true);

        $get = Yii::$app->request->get();

        if (isset($get['filter'])) {
            $filter = $get['filter'];
            if ($filter == 2) {

            } else {
                Excel::createAllDataExcel($dataByWeek, $sheet);
            }
        } else {
            Excel::createAllDataExcel($dataByWeek, $sheet);
        }

        Excel::outputExcel($excel);
    }

    private static function outputExcel($excel)
    {
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=" . "Health_Checker_Report" . "_" . date('Y-m-d') . "_" . time() . ".xlsx");
        header("Cache-Control: max-age=0");

        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, "Xlsx");
        $objWriter->save("php://output");
        exit;
    }

    private static function createAllDataExcel($dataByWeek, $sheet)
    {
        $c = 1;
        $r = 2;
        $heldRow = $r;
        foreach ($dataByWeek as $week => $Row) {
            $sheet->setCellValue([$c, $r], "Week Number " . $week);
            $c++;
            $countOfEntries = 0;
            foreach ($Row as $date => $cells) {
                $dateTime = new \DateTime($date);
                $year = $dateTime->format("l");
                $sheet->setCellValue([$c, $r], $year);
                $startrow = $r;
                $r++;
                $c++;

                //work out average
                $sysTemp = 0;
                $diaTemp = 0;
                $pulseTemp = 0;
                $steps = 0;
                $km = 0;
                $mainCount = 0;

                foreach ($cells as $cell) {
                    $sysTemp = $sysTemp + $cell['SYSmmHg'];
                    $diaTemp = $diaTemp + $cell['DIAmmHg'];
                    $pulseTemp = $pulseTemp + $cell['Pulse'];
                    if ($cell['Steps'] != 0) {
                        $steps = $cell['Steps'];
                        $km = $cell['AverageKm'];
                    }
                    $mainCount++;
                };
                $sheet->setCellValue([$c - 1, $r], "s for " . $dateTime->format("Y-m-d"));
                $r++;
                $runArray = ['SYSmmHg' => ["count" => $mainCount, 'value' => $sysTemp],
                    'DIAmmHg' => ["count" => $mainCount, 'value' => $diaTemp],
                    'Pulse' => ["count" => $mainCount, 'value' => $pulseTemp],
                    'Steps' => ["count" => 1, 'value' => $steps],
                    'AverageKm' => ["count" => 1, 'value' => $km]];
                foreach ($runArray as $keyName => $count) {
                    Excel::addAverageCell($sheet, $keyName, $c, $r, $count['value'], $count['count']);
                }
                $r = $r + 2;

                foreach ($cells as $item => $cell) {
                    foreach ($cell as $iname => $name) {
                        if ($iname != 'del' && $iname != 'edit') {
                            $sheet->setCellValue([$c - 1, $r], $iname);
                            $sheet->setCellValue([$c, $r], $name);
                            $r++;
                        }
                    }
                    $r++;
                }
                if ($r > $countOfEntries) {
                    $countOfEntries = $r;
                }
                $r = $startrow;
                $c = $c + 3;
            }
            $r = $countOfEntries;
            $r++;
            $c = 1;
        }
    }

    private static function addAverageCell($sheet, $name, &$c, &$r, $value, $mainCount)
    {
        $sheet->setCellValue([$c - 1, $r], $name);
        $sheet->setCellValue([$c, $r], "0");
        if ($value > 0 && $mainCount > 0) {
            $sheet->setCellValue([$c, $r], $value / $mainCount);
        }
        $r++;
    }
}