<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 12/09/2022
 * Time: 12:23
 */

?>
                    <table style = "border: 1px solid black;
  border-collapse: collapse;">
                        <?php for ($r = 0; $r <= 50; $r++) {?>
                                <tr style = "height:15px">
                                    <?php for ($c = 0; $c <= 50; $c++) {?>
                        <td style = "border: 1px solid black;
  border-collapse: collapse; width:15px; background-color:<?= $color[$c][$r] ?>" onclick="location.href='/v1/game/game?r=<?= $r?>&c=<?= $c?>'"> </td>
                                  <?php } ?>
                                </tr>
                        <?php } ?>
                    </table>



<script>
    setTimeout(function() {
        window.location.reload();
    }, 5000);
</script>




