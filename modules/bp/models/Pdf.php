<?php

namespace app\modules\bp\models;

use app\models\CreateFile;
use yii\base\Model;

class Pdf extends Word
{
    public function OutPutDocument($object)
    {
        // Yes its wrong, but PHPWORD broke
        // https://github.com/map-os/PHPWord/commit/798b379acabd614ad260ddeb287f79e1706de1a8
        // would fix it, but would require amending a vendor file, which would break a future fix being added.
        // so better to supress the error in this function for now.
        error_reporting(E_ALL ^ E_DEPRECATED);

        include_once '../vendor/tecnickcom/tcpdf/tcpdf.php';
        \PhpOffice\PhpWord\Settings::setPdfRendererPath('../vendor/tecnickcom/tcpdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererName('TCPDF');
        $fileName = 'Bp_PDF_ Export_' . date('Y-m-d') . "_" . time() . ".pdf";
        $file = CreateFile::getFilePath($fileName);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($object, 'PDF');
        $objWriter->save($file);

        header("Content-Length: " . filesize($file));
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment;Filename=" . $fileName);
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        readfile($file);
        unlink($file);

        return $file;
    }
}