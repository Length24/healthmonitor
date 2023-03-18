<?php


namespace app\modules\bp\models;

use yii\base\Model;
use Yii;

class ExportModels extends Model
{
    public array $reportFilters = [];

    /**
     * @return array
     */
    public function getReportFilters(): array
    {
        return $this->reportFilters;
    }

    /**
     * @param array $reportFilters
     */
    public function setReportFilters(array $reportFilters): void
    {
        $this->reportFilters = $reportFilters;
    }
}