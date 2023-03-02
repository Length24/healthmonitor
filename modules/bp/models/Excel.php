<?php

namespace app\modules\bp\models;

use yii\base\Model;

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
        $excel->getDefaultStyle()->getFont()->setName('Arial')->setSize(8);
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle(\Yii::t('app', "title"), true);
        $sheet->setCellValue([1, 1], 'A1');
        $sheet->setCellValue([1, 2], "2013-01-01");
        $sheet->getStyle([1, 2])->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm');

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=" . "Health_Checker_Report" . "_" . date('Y-m-d') . "_" . time() . ".xlsx");
        header("Cache-Control: max-age=0");

        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, "Xlsx");
        $objWriter->save("php://output");
        exit;
    }
}