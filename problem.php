<style>
    .row {
        display: flex;
    }

    .cell {
        width: 18px;
        margin: 1px;

    }
</style>
<?php
if (!isset($_POST['data']) || !isset($_POST['h']) || !isset($_POST['w'])) {
    header('Location: index.php');
}
$room = array();

$room = json_decode($_POST['data']);


define("WIDTH", $_POST['w']);
define("HEIGHT", $_POST['h']);
define("INITIAL_TRASH", 0);
define("STEPS", 0);
define("BATTERY_ROOMBA", 100);


function getCellVal($val)
{
    //1 = empty
    //2 = starting
    //3 = charge station
    //4 = garbage disposal
    //5 = obstacle
    $ret = "";
    switch ($val) {

        case 1:
            $ret = "_";
            break;
        case 2:
            $ret = "X";
            break;
        case 3:
            $ret = "C";
            break;
        case 4:
            $ret = "T";
            break;
        case 5:
            $ret = "O";
            break;

        default:
            $ret = "<i style='color:red;'>ERROR</i>";
            break;
    }
    return $ret;
}

function printRoom($roomIn)
{
    foreach ($roomIn as $row) {
        echo '<div class="row">';
        foreach ($row as $cell) {
            echo "<div class='cell'>" . getCellVal($cell) . "</div>";
        }
        echo '</div>';
    }
}

function setCell($x, $y, $val, $room)
{
    $newRoom = $room;
    $newRoom[HEIGHT - 1 - $y][$x] = $val;
    return $newRoom;
}

function getObjects($tab, $space)
{
    $ret = "";
    for ($y = 0; $y < HEIGHT; $y++) {
        for ($x = 0; $x < WIDTH; $x++) {
            $ret .= $tab . $tab . "x" . $x . "y" . $y . " - location" . $space;
        }
    }

    return $ret;
}

function getGoals($roomIn, $tab, $space)
{
    $ret = "";
    for ($y = HEIGHT - 1; $y >= 0; $y--) {
        $tempY = HEIGHT - 1 - $y;
        for ($x = 0; $x < WIDTH; $x++) {
            // if ($roomIn[$y][$x] == 1) {
            $ret .= $tab . $tab . $tab . "(is-clean x" . $x . "y" . $tempY . ")" . $space;
            // }
        }
    }

    return $ret;
}

function isValidCell($x, $y)
{
    if ($x < 0 || $y < 0 || $x >= WIDTH || $y >= HEIGHT) {
        return false;
    }
    return true;
}

function getInits($roomIn, $tab, $space)
{
    $ret = "";
    for ($y = HEIGHT - 1; $y >= 0; $y--) {
        $tempY = HEIGHT - 1 - $y;
        for ($x = 0; $x < WIDTH; $x++) {
            // if ($roomIn[$y][$x] != 5) {
            //left up right down
            $mapping = array(
                array($x - 1, $y),
                array($x, $y - 1),
                array($x + 1, $y),
                array($x, $y + 1)
            );
            foreach ($mapping as $map) {
                $futureX = $map[0];
                $futureY = $map[1];
                if (isValidCell($futureX, $futureY)) {
                    $tempFutureY = HEIGHT - 1 - $futureY;
                    // if ($roomIn[$futureY][$futureX] != 5) {
                    $ret .= $tab . $tab . "(has-path x" . $x . "y" . $tempY . " x" . $futureX . "y" . $tempFutureY . ")" . $space;
                    // }
                }
            }
            // }
        }
    }

    return $ret;
}

function getEmptys($roomIn, $tab, $space)
{
    $ret = "";
    for ($y = HEIGHT - 1; $y >= 0; $y--) {
        $tempY = HEIGHT - 1 - $y;
        for ($x = 0; $x < WIDTH; $x++) {
            if ($roomIn[$y][$x] != 5) {
                $ret .= $tab . $tab . "(is-empty x" . $x . "y" . $tempY . ")" . $space;
            } else {
                $ret .= $tab . $tab . "; x" . $x . "y" . $tempY . " occupied by some obstruction" . $space;
            }
        }
    }

    return $ret;
}

function getCleans($roomIn, $tab, $space)
{
    $ret = "";
    for ($y = HEIGHT - 1; $y >= 0; $y--) {
        $tempY = HEIGHT - 1 - $y;
        for ($x = 0; $x < WIDTH; $x++) {
            if ($roomIn[$y][$x] == 1 || $roomIn[$y][$x] == 2) {
                $ret .= $tab . $tab . "(is-dirty x" . $x . "y" . $tempY . ")" . $space;
            } else {
                $ret .= $tab . $tab . "(is-clean x" . $x . "y" . $tempY . ")" . $space;
            }
        }
    }

    return $ret;
}

function getSpecials($roomIn, $tab, $space)
{
    $ret = "";
    for ($y = HEIGHT - 1; $y >= 0; $y--) {
        $tempY = HEIGHT - 1 - $y;
        for ($x = 0; $x < WIDTH; $x++) {
            if ($roomIn[$y][$x] == 3) {
                //Charging
                $ret .= $tab . $tab . "(is-charger x" . $x . "y" . $tempY . ")" . $space;
            }
            if ($roomIn[$y][$x] == 4) {
                //garbage
                $ret .= $tab . $tab . "(is-trashplace x" . $x . "y" . $tempY . ")" . $space;
            }
        }
    }

    return $ret;
}


$tab = "&nbsp;&nbsp;";
$space = "<br>";
$head = $space . "
(define (problem roomba_problem1)" . $space . "
" . $tab . "(:domain roomba)" . $space;


$foot = "	
" . $space . "(:metric minimize" . $space . "
" . $tab . $tab . "(steps)" . $space . "
" . $tab . ")" . $space . "
)
";


// $row = array();
// for ($y = 0; $y < HEIGHT; $y++) {
//     for ($x = 0; $x < WIDTH; $x++) {
//         $row[$x] = 1;
//     }
//     $room[$y] = $row;
//     unset($row);
// }

//1 = empty
//2 = starting
//3 = charge station
//4 = garbage disposal
//5 = obstacle

// $room = setCell(0, 0, 2, $room);
// $room = setCell(1, 1, 5, $room);
// $room = setCell(0, 2, 4, $room);
// $room = setCell(2, 2, 3, $room);
printRoom($room);


$objects = $space . "
" . $tab . "(:objects" . $space . "
" . getObjects($tab, $space) . "
" . $tab . $tab . "roomba - roomba" . $space . "
" . $tab . ")" . $space;

$goals =  $space . "
" . $tab . "(:goal" . $space . "
" . $tab . $tab . "(and" . $space . "
" . $tab . $tab . $tab . "; All positions not dirty:" . $space . "
" . getGoals($room, $tab, $space) . "
" . $tab . $tab . ")" . $space . "
" . $tab . ")" . $space;

$inits = $space . "
" . $tab . "(:init" . $space . "
" . $tab . $tab . "; Paths:" . $space . "
" . getInits($room, $tab, $space) . $space  . "
" . getEmptys($room, $tab, $space) . $space  . "
" . getCleans($room, $tab, $space) . $space  . "
" . getSpecials($room, $tab, $space) . $space  . "
" . $tab . $tab . "(=(battery-amount roomba) " . BATTERY_ROOMBA . ")" . $space . "
" . $tab . $tab . "(=(trash-amount roomba) " . INITIAL_TRASH . ")" . $space . "
" . $tab . $tab . "(= (steps) " . STEPS . ")" . $space . "
" . $tab . ")" . $space;

echo $head . $objects . $inits . $goals . $foot;
