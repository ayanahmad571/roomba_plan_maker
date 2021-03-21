<?php

$HEIGHT = 5;
$WIDTH = 10;

?>
<style>
    .row {
        display: flex;
    }

    .cell {
        width: 20px;
        height: 20px;
        border: 1px solid grey;
        margin: 1px;

    }
</style>


<body>
    <?php
    for ($y = $HEIGHT - 1; $y >= 0; $y--) {
        echo '<div class="row">';
        for ($x = 0; $x < $WIDTH; $x++) {
            echo "<div class='cell'>_</div>";
        }
        echo '</div>';
    }
    ?>
</body>