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
        <h1>Score Events</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-1">
    </div>

    <div class="col-md-10 scroll-div">

        <?php foreach ($scores as $score) {?>
        <div class='box' style = ' height: 10px; width: 10px; background-color: <?=$score['color']?> !important;'></div><?= $score['text'] ?>
        <br />
        <?php } ?>

    </div>

    <div class="col-md-1">
    </div>
</div>