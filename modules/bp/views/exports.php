<?php
$get = Yii::$app->request->get();
$getUrl = http_build_query($get);

?>

<div class="row">
    <div class="col-md-12" style="text-align: center">
        <h1>Exports</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h2>Instructions</h2>
        <p>Please select the filters to the left and then click submit to ensure you obtain the correct report, when the
            correct filters are set, click on the export you require.</p>
        <p>Filters Currently Set: </p>
        <h6>
            <ul>
                <?php
                if (!empty($get)) {
                    if (isset($get['filter'])) {
                        echo "<li>Report Set : " . $filters[$get['filter']] . "</li>";
                    }
                    if (isset($get['SYSmmHg'])) {
                        echo "<li>Data Point  : SYSmmHg</li>";
                    }
                    if (isset($get['DIAmmHg'])) {
                        echo "<li>Data Point  : DIAmmHg</li>";
                    }
                    if (isset($get['Pulse'])) {
                        echo "<li>Data Point  : Pulse</li>";
                    }
                    if (isset($get['Steps'])) {
                        echo "<li>Data Point  : Steps</li>";
                    }
                    if (isset($get['otherInfo'])) {
                        echo "<li>Data Point   : Other Infomation</li>";
                    }
                    if (isset($get['fromdate']) && isset($get['todate'])) {
                        echo "<li>Date Range From : " . $get['fromdate'] . " To: " . $get['todate'] . "</li>";
                    }
                } else {
                    echo "None";
                }
                ?>
            </ul>
        </h6>
    </div>

</div>
<div class="row">
    <div class="col-md-4 p-4 border border-3 rounded" style="text-align: center">
        <h3> Download Spreadsheet Data</h3>

        <a href="/bp/bp/excel<?= $getUrl ?>"><i class="fa fa-table fa-5x" aria-hidden="true"></i></a>
    </div>
    <div class="col-md-4 p-4 border border-3 rounded" style="text-align: center">
        <h3> Download Formatted PDF</h3>
        <a href="/bp/bp/pdf<?= $getUrl ?>"><i class="fa fa-file-pdf-o fa-5x" aria-hidden="true"></i></a>
    </div>
    <div class="col-md-4 p-4 border border-3 rounded" style="text-align: center">
        <h3> Download Word Document</h3>
        <a href="/bp/bp/word?<?= $getUrl ?>"><i class="fa fa-file-word-o fa-5x" aria-hidden="true"></i></a>
    </div>
</div>

