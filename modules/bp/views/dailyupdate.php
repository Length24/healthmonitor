<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 14/09/2022
 * Time: 11:11
 */

$theDate = $theTime = $idValue = $sysValue = $diaValue = $pulValue = $stepValue = $otherValue = '';
if (isset($editJob)) {

    $date = new \dateTime($editJob['datetimecheck']);
    $theDate = $date->format('Y-m-d');
    $theTime = $date->format('H:i');
    $idValue = $editJob['id'];
    $sysValue = $editJob['sys'];
    $diaValue = $editJob['dia'];
    $pulValue = $editJob['pul'];
    $stepValue = $editJob['step'];
    $otherValue = $editJob['other'];
}


?>

<div class="row">
    <div class="col-md-12" style="text-align: center">
        <h1>Update Health Check for Today</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-1">
    </div>

    <div class="col-md-10">
        <form method="post">
            <div class="row">
                <div class="mb-3 col-md-2">
                    <label for="step">ID</label>
                    <input type="number" class="form-control" name="id" id="id" placeholder="90"
                           value="<?= $idValue ?>" disabled>
                </div>
                <div class="mb-3 col-md-4">
                    <label for="step">Step Count</label>
                    <input type="number" class="form-control" name="step" id="step" placeholder="90"
                           value="<?= $stepValue ?>">
                </div>
                <div class="mb-3 col-md-3">
                    <label for="senddaydate">Date of Check</label>
                    <br/>
                    <input type="date" id="senddaydate" name="senddaydate" value="<?= $theDate ?>">
                </div>
                <div class="mb-3 col-md-3">
                    <label for="senddaydate">Time of Check</label>
                    <br/>
                    <input type="time" id="senddaytime" name="senddaytime" value="<?= $theTime ?>">
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-4">
                    <label for="sys">Sys.mmHG</label>
                    <input type="number" class="form-control" id="sys" name="sys" placeholder="120"
                           value="<?= $sysValue ?>">
                </div>
                <div class="mb-3 col-md-4">
                    <label for="dia">Dia.mmHg</label>
                    <input type="number" class="form-control" id="dia" name="dia" placeholder="80"
                           value="<?= $diaValue ?>">
                </div>
                <div class="mb-3 col-md-4">
                    <label for="pul">Pulse</label>
                    <input type="number" class="form-control" id="pul" name="pul" placeholder="60"
                           value="<?= $pulValue ?>">
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label for="other">Any other information</label>
                    <textarea class="form-control" id="other" name="other" rows="3"><?= $otherValue ?> </textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck">
                    <label class="form-check-label" for="gridCheck">
                        I agree to this website storing my information for the purpose of reporting back to me.
                    </label>
                </div>
            </div>
            <button type="submit" id="btnSubmit" class="btn btn-primary" disabled>Submit</button>
        </form>
    </div>
    <div class="col-md-1">
    </div>
</div>

<script>
    $(function () { // this will be called when the DOM is ready
        $('#gridCheck').change(function () {
            if ($(this).is(':checked')) {
                $("#btnSubmit").attr("disabled", false);
            } else {
                $("#btnSubmit").attr("disabled", true);
            }
        });
    });
</script>