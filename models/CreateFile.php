<?php

namespace app\models;

use yii\base\Model;
use yii;

class CreateFile extends Model
{
    public static function createFile($fileName)
    {
        $img = CreateFile::getFilePath($fileName);
        $fp = fopen($img, "wb");
        return ['fp' => $fp, 'path' => $img];
    }

    public static function getFolder()
    {
        $baseFolder = Yii::$app->params['baseFolder'];
        $tempFolder = Yii::$app->params['savefilefolder'];
        if (!file_exists($baseFolder)) {
            mkdir($baseFolder);
        }
        if (!file_exists($tempFolder)) {
            mkdir($tempFolder);
        }
        return $tempFolder;
    }

    public static function getFilePath($fileName)
    {
        $tempFolder = CreateFile::getFolder();
        return $tempFolder . '/' . $fileName;
    }

    public static function clearTempFolder()
    {
        $tempFolder = CreateFile::getFolder();
        $files = glob($tempFolder . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
    }
}