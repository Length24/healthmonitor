<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 12/09/2022
 * Time: 14:07
 */

?>


</div>
<div class="col-md-4" style ="padding: 30px">

    <H1>The Scores</H1>
    <h4>
    <ol>
        <?php foreach($scores as $score) {
            echo "<div class='box' style = ' height: 10px; width: 10px; background-color: ".$score['color']." !important;'></div><li>".$score['username'].": ".$score['score']."</li>";
        }
        ?>
    </ol>
    </h4>
</div>
</div>
</div>
</div>
<div class="row">
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
        Copyright = (c) James Fearnley 2022
    </div>
</div>
</body>
</html>