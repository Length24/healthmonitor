<?php

use miloschuman\highcharts\Highcharts;

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/10.3.3/highcharts.js"
        integrity="sha512-8cJ3Lf1cN3ld0jUEZy26UOg+A5YGLguP6Xi6bKLyYurrxht+xkLJ9oH9rc7pvNiYsmYuTvpe3wwS6LriK/oWDg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="row">
    <div class="col-md-12" style="text-align: center">
        <h1>Reporting</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div id="graphcontainer_1"></div>
    </div>
    <div class="col-md-6">
        <div id="graphcontainer_2"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div id="graphcontainer_3"></div>
    </div>
</div>

<script type="text/javascript">
    <?php
    $noEntries = true;

    for ($x = 1; $x <= 3; $x++) {
        if($graphs[$x] !== "[]") {
        $noEntries = false;
    ?>

    $(function () {
        $('#graphcontainer_<?=$x?>').highcharts(<?=$graphs[$x]?>);
    });

    <?php }
    }


    ?>

</script>
<?php if ($noEntries) { ?>
    <h2 style = "text-align: center"> No Readings have yet been recorded, so reporting is impossible</h2>
    <h3 style = "text-align: center">Please add some readings on the new health information page to see data reporting</h3>

<?php } ?>

