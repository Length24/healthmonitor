<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 14/09/2022
 * Time: 11:11
 */

?>

<div class="row">
    <div class="col-md-12" style ="text-align: center">
        <h1>Update Health for Today</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-1">
    </div>

    <div class="col-md-10 scroll-div">
        <div class="row">
        <form method="post">
                <div class="form-group">
                    <h3>
                        enter todays updates
                    </h3>
                    <div class="form-group">
                        <?= \app\models\jfDateTimePicker::getDateTimeInput('form-control',
                            'datesupplementary-123' ,
                            'timesupplementary-123',
                            'Date Visited',
                            'Time Visited');?>
                    </div>
                    <label class="control-label " for="syse">
                        Sys.mmHG
                    </label>
                    <input class="form-control" id="sys" name="sys" type="text"/>

                    <label class="control-label " for="dia">
                        Dia.mmHg
                    </label>
                    <input class="form-control" id="dia" name="dia" type="text"/>

                    <label class="control-label " for="pulse">
                        Pulse
                     </label>
                    <input class="form-control" id="pulse" name="pulse" type="text"/>

                    <label class="control-label" for="step">
                        Steps
                    </label>
                    <input class="form-control" id="step" name="step" type="text"/>

                    <label class="control-label" for="other">
                        Other Info
                    </label>
                    <input class="form-control" id="other" name="other" rows="3" type="textarea"/>

                </div>

                <div class="form-group">
                     <div>
                        <button class="btn btn-primary " name="submit" id = "btnSubmit" type="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-1">
    </div>
</div>