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
<h1>Gift</h1>
If your impressed with what somebody has done on the Board, send them a gift! (all gifts taken from your score)
<form method="post" action="/v1/game/game/giftscore/" name="gift">
    <fieldset>

        <!-- Select Basic -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="selectbasic">Select Basic</label>
            <div class="col-md-4">
                <select id="selectbasic" name="amount" class="form-control">
                    <option value="100">100</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                    <option value="5000">5000</option>
                    <option value="10000">10000</option>
                </select>
            </div>
        </div>

        <!-- Select Basic -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="selectbasic">Select Basic</label>
            <div class="col-md-4">
                <select id="selectbasic" name="player" class="form-control">
                    <?php foreach ($players as $player) {
                        if ($ownerId != $player['id']) {
                            ?>
                            <option value="<?= $player['id'] ?>"><?= $player['username'] ?></option>
                        <?php }
                    }?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div>
                <button class="btn btn-primary " name="submit" type="submit">
                    Submit
                </button>
            </div>
        </div>

    </fieldset>
</form>
