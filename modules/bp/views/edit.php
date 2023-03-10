<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 14/09/2022
 * Time: 12:10
 */

$cookies = Yii::$app->request->cookies;
$ownerId = null;

if (isset($cookies['ownerId'])) {
    $ownerId = $cookies['ownerId']->value;
}

?>


<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"/>
<link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/css/jquery.dataTables_themeroller.css" rel="stylesheet"
      data-semver="1.9.4" data-require="datatables@*"/>
<link href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<div class="row">
    <div class="col-md-12" style="text-align: center">
        <h1>View/Edit Readings</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="healthtable" id="healthtable">

        </table>
    </div>
</div>
<script>


    var dataSet = <?= $dataset?>;
    var columnDefs = [
        {"className": "dt-body-left", "title": "Date and Time"},
        <?php if(isset($colInfo['SYSmmHg'])) { ?>
        {"className": "dt-body-center", "title": "SYS.mmHg"},
        <?php } ?>
        <?php if(isset($colInfo['DIAmmHg'])) { ?>
        {"className": "dt-body-center", "title": "DIA.mmHg"},
        <?php } ?>
        <?php if(isset($colInfo['Pulse'])) { ?>
        {"className": "dt-body-center", "title": "PUL.min"},
        <?php } ?>
        <?php if(isset($colInfo['Steps'])) { ?>
        {"className": "dt-body-center", "title": "Steps"},
        <?php } ?>
        <?php if(isset($colInfo['AverageKm'])) { ?>
        {"className": "dt-body-center", "title": "KM"},
        <?php } ?>
        <?php if(isset($colInfo['otherInfo'])) { ?>
        {"className": "dt-body-right dt-head-right", "title": "Other Information"},
        <?php } ?>
        <?php if(isset($colInfo['del'])) { ?>
        {
            "className": "dt-body-center dt-head-center",
            "title": "Delete",
            "data": "download_link",
            "render": function (data, type, row, meta) {
                return '<a href="' + row[7] + '"><i class="fa fa-trash fa-3" aria-hidden="true"></i></a>';
            },
        },
        <?php } ?>
        <?php if(isset($colInfo['edit'])) { ?>
        {
            "className": "dt-body-center dt-head-center", "title": "Edit",
            "data": "download_link",
            "render": function (data, type, row, meta) {
                return '<a href="' + row[8] + '"><i class="fa fa-pencil-square fa-3" aria-hidden="true"></i></a>';
            },
        }
        <?php } ?>
    ];

    $(document).ready(function () {
        var dt = $('#healthtable').dataTable({
            dom: 'Bfrtip',
            "bFilter": true,
            "bPaginate": false,
            "data": dataSet,
            "columns": columnDefs,
            "bInfo": true,
            aaSorting: [],
            "searching": true,

            paging: true,

            "iDisplayLength": 10,
            "deferRender": true,
            "lengthMenu": [25, 50, 100, 200],

            buttons: [
                'copy', 'csv', 'pdf'
            ]
        });
    });
</script>
