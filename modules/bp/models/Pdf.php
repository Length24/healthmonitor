<?php

namespace app\modules\bp\models;

use yii\base\Model;

class Pdf extends Model
{
    public static function createPDF($dataSet)
    {

        $pdf = new \TCPDF;
        $pdf->AddPage();
        $pageDimensions = $pdf->getPageDimensions();
        $pageWidth = $pageDimensions['wk'];
        $pageHeight = $pageDimensions['hk'];

        $pdf->SetFont('FreeSans', '', 12);
        $pdf->Cell($pageWidth - 20, 6, "Health Monitor");
        $pdf->SetXY(35, 12);
        $pdf->SetLineWidth(0.2);
        $pdf->RoundedRect(20, 70, $pageWidth - 60, 21, 3.50, '1111', 'DF');
        $pdf->WriteHTML("<h1>Bo</h1>", 34, false, true);

        $temp = tmpfile();
        $tempFilePath = stream_get_meta_data($temp)['uri'];
        $pdf->Output($tempFilePath, 'I');

    }
}