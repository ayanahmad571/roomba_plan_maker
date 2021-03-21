<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomba GUI PDDL</title>
    <link href="./css/bootstrap.css" rel="stylesheet">

    <script src="./js/jquery.js"></script>
    <script src="./js/bootstrap.js"></script>

    <style>
        .rowCell {
            display: flex;
        }

        .cell {
            width: 40px;
            height: 40px;
            border: 1px solid grey;
            margin: 1px;

        }

        .cont {
            padding: 10px
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

        .cl {
            cursor: pointer;
        }
        .cl:hover{
            scale: 1.05;
        }
    </style>
</head>

<body style="padding: 20px;">
    <?php
    if (isset($_GET['h']) && isset($_GET['w'])) {
        $HEIGHT = $_GET['h'];
        $WIDTH = $_GET['w'];
    ?>
        <div align="center" class="container">

            <div class="row">
                <div class="col-12">
                    <h2>
                        Plan Creator - Set Arguments
                    </h2>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class=" col-12">
                    <div style="width: 100%;" class="card ">
                        <div class="card-header">
                            Featured
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Special title treatment</h5>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            <div class="row">
                                <div onclick="setInput(1)" class="cl col-2">
                                    <div class="card c1">
                                        <div class="card-body">
                                            1 = empty
                                        </div>
                                    </div>
                                </div>

                                <div onclick="setInput(2)" class="cl col-2">
                                    <div class="card c2">
                                        <div class="card-body">
                                            2 = starting
                                        </div>
                                    </div>
                                </div>
                                <div onclick="setInput(3)" class="cl col-3">
                                    <div class="card c3">
                                        <div class="card-body">
                                            3 = charge station
                                        </div>
                                    </div>
                                </div>
                                <div onclick="setInput(4)" class="cl col-3">
                                    <div class="card c4">
                                        <div class="card-body">
                                            4 = garbage disposal
                                        </div>
                                    </div>
                                </div>
                                <div onclick="setInput(5)" class="cl col-2">
                                    <div class="card c5">
                                        <div class="card-body">
                                            5 = obstacle
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12">
                    <input type="number" value="1" min="1" max="5" class="form-control" placeholder="Type Of Box" id="type" />
                </div>
            </div>
            <br>
            <div class="row">
                <div align="center" class="col-12">
                    <?php
                    for ($y = $HEIGHT - 1; $y >= 0; $y--) {
                        echo '<div class="rowCell">';
                        for ($x = 0; $x < $WIDTH; $x++) {
                            echo "<div  data-row='" . $y . "' data-col='" . $x . "' id='bx" . $x . "y" . $y . "' class='cell c1'></div>";
                        }
                        echo '</div>';
                    }
                    ?></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="problem.php" method="POST">
                        <input id="dataInput" type="hidden" name="data" />
                        <input type="hidden" readonly name="w" value="<?php echo $WIDTH ?>">
                        <input type="hidden" readonly name="h" value="<?php echo $HEIGHT ?>">
                        <div class="form-group">
                            <label>Battery</label>
                            <input type="number" class="form-control" name="b" placeholder="Enter Battery">
                        </div>
                        <div class="form-group">
                            <label>Trash</label>
                            <input type="number" class="form-control" name="t" placeholder="Enter Trash">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>

        </div>


        <script>
            let room = Array(<?php echo $HEIGHT; ?>).fill(1).map(() => Array(<?php echo $WIDTH; ?>));
            const setCell = (x, y, val) => {
                room[<?php echo $HEIGHT - 1; ?> - y][x] = parseInt(val);
                $("#dataInput").val(JSON.stringify(room));
            };

            const setInput = (input) => {
                $("#type").val(input);
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
                    $("#bx<?php echo $x . "y" . $y; ?>").click(() => {
                        let type = $("#type").val();
                        setCell(<?php echo $x; ?>, <?php echo $y; ?>, type);
                        $("#bx<?php echo $x . "y" . $y; ?>").removeClass("c1 c2 c3 c4 c5").addClass(`c${type}`);

                    });
            <?php
                }
            }
            ?>
        </script>

    <?php
    } else {
    ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>
                        Plan Creator - Enter Grid Size
                    </h2>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <form action="index.php" method="get">
                        <div class="form-group">
                            <label>Height</label>
                            <input type="number" class="form-control" name="h" placeholder="Enter Height">
                        </div>
                        <div class="form-group">
                            <label>Width</label>
                            <input type="number" class="form-control" name="w" placeholder="Enter Width">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>

    <?php
    }
    ?>





</body>

</html>