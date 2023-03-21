<?php

namespace app\modules\bp\models;

use app\models\bp\HighCharts;
use app\models\CreateFile;
use yii\base\Model;
use yii;

class Word extends ExportModels
{

    public function createObject($dataSet)
    {

        CreateFile::clearTempFolder(); //clear the temp folder on start
        $graph1 = HighCharts::findOne(['id' => 1]);
        $graph = $graph1->getGraph();
        $img1 = $graph1->exportModule($graph);

        $graph2 = HighCharts::findOne(['id' => 2]);
        $graph = $graph2->getGraph();
        $img2 = $graph2->exportModule($graph);

        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection(['marginLeft' => 600, 'marginRight' => 600,
            'marginTop' => 600, 'marginBottom' => 600]);


        $section->addText(
            'Blood Pressure Report',
            ['name' => 'Tahoma', 'size' => 26, 'bold' => true]
        );

        $section->addText(
            'Date Downloaded: ' . date('Y-m-d'),
            ['name' => 'Tahoma', 'size' => 12, 'bold' => true]
        );

        $get = Yii::$app->request->get();

        if (empty($get)) {
            $section->addText(
                'No Date Filters Set, all date returned',
                ['name' => 'Tahoma', 'size' => 14, 'bold' => false]
            );
        } else {
            if (isset($get['fromdate']) && isset($get['todate'])) {
                $section->addText(
                    'Date Range ' . $get['fromdate'] . " to " . $get['todate'],
                    ['name' => 'Tahoma', 'size' => 12, 'bold' => false]
                );
            }
            if (isset($get['filter'])) {
                $section->addText(
                    'ReportType: ' . $this->reportFilters[$get['filter']],
                    ['name' => 'Tahoma', 'size' => 12, 'bold' => false]
                );
            }
        }


        $section->addText(
            'Produced using Bp-Monitor.org.uk',
            ['name' => 'Tahoma', 'size' => 8, 'bold' => false]
        );

        $section->addImage($img1, ['positioning' => 'relative', 'width' => 260, 'height' => 200, 'wrappingStyle' => 'tight']);
        $section->addImage($img2, ['positioning' => 'relative', 'width' => 260, 'height' => 200, 'wrappingStyle' => 'tight']);
        $section->addText('
        
        ');

        $model = new Bp();
        $fullData = $model->getBpData(false, " ORDER BY max(a.orderby) ASC;");

        if (!empty($fullData)) {

            $table = $section->addTable([
                'borderSize' => 12,
                'borderColor' => '000000',
                'afterSpacing' => 0,
                'Spacing' => 0,
                'cellMargin' => 0
            ]);
            $colSize = 11520;  #1440 twips = 1in ,  8 and a bit inches to a page


            $firstRow = true;
            foreach ($fullData as $col) {
                if (isset($col['edit'])) {
                    unset($col['edit']);
                }
                if (isset($col['del'])) {
                    unset($col['del']);
                }

                #datetimecheck
                $colNum = count($col);

                if (isset($col['otherInfo'])) {
                    if ($colNum > 1) {
                        $colSize = $colSize / 2;
                        $colNum--;
                        $InfoSize = $colSize;
                    }
                }

                if (isset($col['datetimecheck'])) {
                    $colNum++; #Datatimecheck double Size
                }

                if ($colNum > 0) {
                    $normalSize = $colSize / $colNum;
                } else {
                    $normalSize = $colSize;
                }

                #Add a header Row
                if ($firstRow) {
                    $firstRow = false;
                    $table->addRow();
                    $headers = array_keys($col);
                    foreach ($headers as $id) {
                        if ($id == 'otherInfo') {
                            $cellSize = $InfoSize;
                        }
                        if ($id == 'datetimecheck') {
                            $cellSize = $normalSize * 2;
                        }
                        $table->addCell($cellSize, ['borderSize' => 6])->addText($id, [
                            'name' => 'Arial',
                            'size' => '12',
                            'color' => '000000',
                            'bold' => true,
                            'italic' => false
                        ]);
                    }
                }

                $table->addRow();
                foreach ($col as $id => $cell) {
                    $cellSize = $normalSize;
                    if (is_null($cell)) {
                        $cell = " ";
                    }

                    if ($id == 'otherInfo') {
                        $cellSize = $InfoSize;
                        $cell = str_replace(',', '', $cell);
                        $cell = trim($cell);
                    }
                    if ($id == 'datetimecheck') {
                        $cellSize = $normalSize * 2;
                    }
                    if ($id !== 'otherInfo' && $id !== 'datetimecheck') {
                        $cell = (int)$cell;
                    }

                    $table->addCell($cellSize, ['borderSize' => 6])->addText($cell, [
                        'name' => 'Arial',
                        'size' => '10',
                        'color' => '000000',
                        'bold' => false,
                        'italic' => false
                    ]);
                }
            }
        }

        $this->OutPutDocument($phpWord);
    }

    public function OutPutDocument($object)
    {
        $filename = 'Bp_Export_' . date('Y-m-d') . "_" . time() . ".docx";
        $file = CreateFile::getFilePath($filename);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($object, 'Word2007');
        $objWriter->save($file);


        header("Content-Length: " . filesize($file));
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment;Filename=" . $filename);
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        readfile($file);
        unlink($file);

        return $file;
    }


}