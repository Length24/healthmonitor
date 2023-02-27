<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 12/09/2022
 * Time: 14:07
 */
?>
</div>
<div class="col-md-4" style="padding: 30px">
    <?php if ($username !== null) { ?>
        <?php if (isset($params['showfilter'])) { ?>
            <H1>Filter your reports</H1>
            <div class="col-md-12">
                <form method="get">
                    <div class="row">
                        <div class="mb-3 col-xs-12 col-md-12">
                            <label for="filter">Select Report-type:</label>
                            <select id="filter" name="filter">
                                <option value="1" <?= $footerParams['type1'] ?>>All Data</option>
                                <option value="2" <?= $footerParams['type2'] ?>>Average AM and PM</option>
                                <option value="3" <?= $footerParams['type3'] ?>>Average Daily</option>
                                <option value="4" <?= $footerParams['type4'] ?>>Average Weekly</option>
                                <option value="5" <?= $footerParams['type5'] ?>>Highest Daily</option>
                                <option value="6" <?= $footerParams['type6'] ?>>Lowest Daily</option>
                            </select>
                        </div>
                        <div class="mb-3 col-xs-12 col-md-12">
                            <div class="row">
                                <div class="mb-3 col-xs-12 col-md-4">
                                    <label for="filter">Select Data to include (Ctrl Click to select multiple) <i
                                                class="fa fa-arrow-circle-right" aria-hidden="true"></i></label>
                                </div>
                                <div class="mb-3 col-xs-12 col-md-6">
                                    <input type="checkbox" name="SYSmmHg" id="SYSmmHg"
                                           value="" <?= $footerParams['SYSmmHg'] ?>>SYSmmHg<br>
                                    <input type="checkbox" name="DIAmmHg" id="DIAmmHg"
                                           value="" <?= $footerParams['DIAmmHg'] ?>>DIA.mmHg<br>
                                    <input type="checkbox" name="Pulse" id="Pulse"
                                           value="" <?= $footerParams['Pulse'] ?>>Pulse<br>
                                    <input type="checkbox" name="Steps" id="Steps"
                                           value="" <?= $footerParams['Steps'] ?>>Steps<br>
                                    <input type="checkbox" name="AverageKm" id="AverageKm"
                                           value="" <?= $footerParams['AverageKm'] ?>>AverageKm<br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-xs-12 col-md-6">
                            <label for="fromdate">From</label>
                            <br/>
                            <input type="date" id="fromdate" name="fromdate" value="<?= $footerParams['from'] ?>">
                        </div>
                        <div class="mb-3 col-xs-12 col-md-6">
                            <label for="todate">to</label>
                            <br/>
                            <input type="date" id="todate" name="todate" value="<?= $footerParams['to'] ?>">
                        </div>
                    </div>
                    <div class="row mb-3 col-xs-12">
                        <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <hr>
        <?php } ?>
        <H1>Weekly Stats</H1>
        <div class="col-md-12">
            <div class="row">
                Average Readings recorded during the week : X <br/>
                Average SYS for the last 7 days : X <br/>
                Average DIA for the last 7 days : X <br/>
                Average PUL for the last 7 days : X <br/>
                Average total steps for the last 7 Days : X <br/>
                Average Km* walked for the last 12 days : X <br/>
                <br/>
                <p class="fw-lighter">*Assuming 1,400 steps to 1 km</p>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center">
                    <i class="fa fa-hand-o-up fa-5x"" aria-hidden="true"></i>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Please Log in</h2>
                    <h4 class="d-inline">To see the</h4>
                    <h3>weekly stats</h3>
                    <h4 class="d-inline">Give</h4>
                    <h3 class="d-inline"> readings</h3>
                    <h4> and see your </h4>
                    <h3 class="d-inline">personalised</h3>
                    <h4 class="d-inline"> reporting
                </div>
            </div>
        </div>
    <?php } ?>
</div>
</div>
</div>
<div class="row fixed-bottom">
    <div class="col-md-2">
    </div>
    <div class="col-md-8" style="text-align :center">
        Copyright = &copy; James Fearnley 2022 - <?= date('Y') ?>, built using the <a
                href="https://www.yiiframework.com/"> yii framework </a>under
        the <a href="https://www.yiiframework.com/license"> under the BSD-3 license</a> <br/>.
    </div>
</div>
</body>
</html>