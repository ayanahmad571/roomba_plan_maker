<?php

$HEIGHT = 5;
$WIDTH = 10;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomba GUI PDDL</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        .row {
            display: flex;
        }

        .cell {
            width: 40px;
            height: 40px;
            border: 1px solid grey;
            margin: 1px;

        }

        .cont {
            padding:10px
        }

        .c1 {
            background-color: grey;
        }

        .c2 {
            background-color: green;
        }

        .c3 {
            background-color: yellow;
        }

        .c4 {
            background-color: purple;
        }

        .c5 {
            background-color: red;
        }
    </style>
</head>

<body>

    <div class="cont c1">1 = empty</div>
    <div class="cont c2">2 = starting</div>
    <div class="cont c3">3 = charge station</div>
    <div class="cont c4">4 = garbage disposal</div>
    <div class="cont c5">5 = obstacle</div>
    <br>
    <input type="number" placeholder="Type Of Box" id="type" />
    <hr>
    <?php
    for ($y = $HEIGHT - 1; $y >= 0; $y--) {
        echo '<div class="row">';
        for ($x = 0; $x < $WIDTH; $x++) {
            echo "<div  data-row='" . $y . "' data-col='" . $x . "' id='b" . $x . $y . "' class='cell c1'></div>";
        }
        echo '</div>';
    }
    ?>

    <hr>
    <form action="problem.php" method="POST">
        <input id="dataInput" type="hidden" name="data" />
        <br>
        W
        <input type="number" readonly name="w" value="<?php echo $WIDTH ?>">
        <br>
        H
        <input type="number" readonly name="h" value="<?php echo $HEIGHT ?>">
        
        <input type="submit" />
    </form>

    <script>
        let room = Array(<?php echo $HEIGHT; ?>).fill(1).map(() => Array(<?php echo $WIDTH; ?>));
        const setCell = (x, y, val) => {
            room[<?php echo $HEIGHT - 1; ?> - y][x] = parseInt(val);
            $("#dataInput").val(JSON.stringify(room));
        };
    </script>
    <script>
        for (y = 0; y < <?php echo $HEIGHT ?>; y++) {
            for (x = 0; x < <?php echo $WIDTH ?>; x++) {
                room[y][x] = 1;
            }
        }

        <?php
        for ($y = 0; $y < $HEIGHT; $y++) {
            for ($x = 0; $x < $WIDTH; $x++) {
        ?>
                $("#b<?php echo $x . $y; ?>").click(() => {
                    let type = $("#type").val();
                    setCell(<?php echo $x; ?>, <?php echo $y; ?>, type);
                    $("#b<?php echo $x . $y; ?>").removeClass("c1 c2 c3 c4 c5").addClass(`c${type}`);

                });
        <?php
            }
        }
        ?>
    </script>


</body>

</html>