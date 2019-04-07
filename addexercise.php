<?php
include_once './app/header.php';
if (!isset($_SESSION['logged_user'])) {
    header("Location: /");
}
include_once './public/../app/header2.php';
$obj = (array) ($_SESSION['logged_user']);

$id = $obj['id'];
$user = R::findOne('users', 'id = ?', array($id));


$errors;
if (isset($_POST['timezone'])) {
    $timezone = $_POST['timezone'];
} else {
    $timezone = $obj['timezone'];
}

$data = $_POST;
$today = date("Y-m-d");

if (isset($data['add_exercises'])) {
    $u = R::dispense('exercises');
    $u->intensity = $data['intensity'];
    $u->name = $data['name'];
    R::store($u);
}

if (isset($data['add_training'])) {
    $us = R::dispense('training');
    $us->client_id = $id;
    $us->exercise_id = $data['id'];
    $us->time = $data['time'];
    $us->date = $today;
    R::store($us);

    $exercises = R::load('exercises', $data['id']);


    $day_stats = R::findOne('daystats', 'client_id = ? AND date = ?', array($id, $today));
    if ($day_stats) {
        $old_date_timestamp = strtotime($today);
        $new_date = date('Y-m', $old_date_timestamp);
        $c = $day_stats['calories_added'];
        $p = $day_stats['proteins_added'];
        $f = $day_stats['fats_added'];
        $ca = $day_stats['carbohydrates_added'];
        $cw = $day_stats['calories_waisted'];


        $day = R::load('daystats', $day_stats['id']);
        $day->proteins_added = $p;
        $day->fats_added = $f;
        $day->calories_added = $c;
        $day->carbohydrates_added = $ca;
        $day->calories_waisted = $cw + $data['time'] * $exercises['intensity'] * 10;
        $day->date = $today;
        $day->client_id = $id;
        R::store($day);
        $month_stats = R::findOne('monthstats', 'id = ?', array($day_stats['month_id']));
        $day_ = R::findOne('daystats', 'id = ?', array($day_stats['id']));

        if ($month_stats) {
            $c5 = $month_stats['calories_added'];
            $p5 = $month_stats['proteins_added'];
            $f5 = $month_stats['fats_added'];
            $ca5 = $month_stats['carbohydrates_added'];
            $cw5 = $month_stats['calories_waisted'];
            $days = $month_stats['days'];
            $date5 = $month_stats['date'];

            $month = R::load('monthstats', $month_stats['id']);
            $month->proteins_added = ($p5 * $days + 0) / ($days );
            $month->fats_added = ($f5 * $days + 0) / ($days);
            $month->calories_added = ($c5 * $days + 0) / ($days);
            $month->carbohydrates_added = ($ca5 * $days + 0) / ($days);
            $month->calories_waisted = ($cw5 * $days + $data['time'] * $exercises['intensity'] * 10) / ($days);
            $month->date = $date5;
            $month->days = $days;
            R::store($month);
        } else {
            $n1 = R::dispense('monthstats');
            $n1->proteins_added = 0;
            $n1->fats_added = 0;
            $n1->calories_added = 0;
            $n1->carbohydrates_added = 0;
            $n1->calories_waisted = $data['time'] * $exercises['intensity'] * 10;
            $n1->date = $new_date;
            $n1->days = 1;
            $n1->client_id = $id;
            R::store($n1);
        }
    } else {
        $old_date_timestamp = strtotime($today);
        $new_date = date('Y-m', $old_date_timestamp);
        $m = R::findOne('monthstats', 'client_id = ? AND date = ?', array($id, $new_date));
        if ($m) {

            $c5 = $m['calories_added'];
            $p5 = $m['proteins_added'];
            $f5 = $m['fats_added'];
            $ca5 = $m['carbohydrates_added'];
            $cw5 = $m['calories_waisted'];
            $days = $m['days'];
            $date5 = $m['date'];

            $day = R::dispense('daystats');
            $day->proteins_added = 0;
            $day->calories_added = 0;
            $day->fats_added = 0;
            $day->carbohydrates_added = 0;
            $day->calories_waisted = $data['time'] * $exercises['intensity'] * 10;
            $day->date = $today;
            $day->client_id = $id;
            $day->month_id = $m['id'];
            R::store($day);
            $day_ = R::findOne('daystats', 'client_id = ? AND date = ?', array($id, $today));

            $n1 = R::load('monthstats', $m['id']);
            $n1->proteins_added = ($p5 * $days + $day_['proteins_added']) / ($days + 1);
            $n1->fats_added = ($f5 * $days + $day_['fats_added']) / ($days + 1);
            $n1->calories_added = ($c5 * $days + $day_['calories_added']) / ($days + 1);
            $n1->carbohydrates_added = ($ca5 * $days + $day_['carbohydrates_added']) / ($days + 1);
            $n1->calories_waisted = ($cw5 * $days + $data['time'] * $exercises['intensity'] * 10)/ ($days + 1);
            $n1->date = $date5;
            $n1->days = $days + 1;
            R::store($n1);
        } else {
            $n1 = R::dispense('monthstats');
            $n1->proteins_added = 0;
            $n1->fats_added = 0;
            $n1->calories_added = 0;
            $n1->carbohydrates_added = 0;
            $n1->calories_waisted = $data['time'] * $exercises['intensity'] * 10;
            $n1->date = $new_date;
            $n1->days = 1;
            $n1->client_id = $id;
            R::store($n1);


            $t = R::findOne('monthstats', 'client_id = ? AND date = ?', array($id, $new_date));
            $day = R::dispense('daystats');
            $day->proteins_added = 0;
            $day->calories_added = 0;
            $day->fats_added = 0;
            $day->carbohydrates_added = 0;
            $day->calories_waisted = $data['time'] * $exercises['intensity'] * 10;
            $day->date = $today;
            $day->client_id = $id;
            $day->month_id = $t['id'];
            R::store($day);
        }
    }
}


$c1 = 0;
$p1 = 0;
$f1 = 0;
$ca = 0;
$cw = 0;
$day_stats = R::findOne('daystats', 'client_id = ? AND date = ?', array($id, $today));

if ($day_stats) {
    $c1 = $day_stats['calories_added'];
    $p1 = $day_stats['proteins_added'];
    $f1 = $day_stats['fats_added'];
    $ca = $day_stats['carbohydrates_added'];
    $cw = $day_stats['calories_waisted'];
}


$exercises = R::find('exercises');
$day_stats = R::findOne('day_stats', 'client_id = ?', array($id));
?>
<div class="menu_wrap">
    <a href="mainpage.php"> <div   class="col-md-1 navElementImg" ><img src="https://image.flaticon.com/icons/svg/25/25694.svg" class="homeImg" style="display:inline-block;"></div></a>
    <div   class="col-md-1 navElement"><h4 style="display: inline;margin-right: 1%"></h4>
        <a href="upload.php" class="navLinks">Додати прийом</a>
    </div>
    <div   class="col-md-1 navElement"><h4 style="display: inline;margin-right: 1%"></h4>
        <a href="addexercise.php" class="navLinks">Додати вправу</a>
    </div>
    <div   class="col-md-1 navElement">
    </div>
    <div class="col-md-4 navElementName"><h2 class="webName">Рахуємо калорії</h2></div>
    <div style="text-align: right; height: 100%; padding-top:13px;" class="col-md-3 "><h4 style="text-align: right; display: inline; color: white; margin-right: 1%;  "><?php echo $user['fname'] . ' ' . $user['name'] . ' ' . $user['pob'].' [ К ]'; ?></h4>
    </div>
    <a href="logout.php" class="navElementImg col-md-1" style="padding-left: 0px; padding-top:8px;"><img src="https://image.flaticon.com/icons/svg/126/126467.svg "  width="32" height="32"></a>
</div>
<div class="container" style="margin-top: 7%">
    <div class="row">
        <div class="col-md-3">
            <div class="well">
                <div class="row col-md-12 wastedAddedHeader">
                    Калорії
                </div>
                <div class="well row">
                    <span>Набрано :</span>
                    <span id="callAdded"><?php echo $c1; ?></span>
                </div>
                <div class="well row">
                    <span>Витрачено :</span>
                    <span id="callWasted"><?php echo $cw; ?></span>


                </div>
            </div>

            <div class="well">
                <div class="row col-md-12 wastedAddedHeader" >
                    Жири
                </div>
                <div class="well row">
                    <span>Набрано :</span>
                    <span id="fatsAdded"><?php echo $f1; ?></span>
                </div>
            </div>
            <div class="well">
                <div class="row col-md-12 wastedAddedHeader">
                    Білки
                </div>
                <div class="well row">
                    <span>Набрано :</span>
                    <span id="protAdded"><?php echo $p1; ?></span>
                </div>
            </div>
            <div class="well">
                <div class="row col-md-12 wastedAddedHeader">
                    Вуглеводи
                </div>

                <div class="well row">
                    <span>Набрано :</span>
                    <span id="carboAdded"><?php echo $ca; ?></span>
                </div>

            </div>
        </div>
        <?php
        foreach ($exercises as $element) {

            $item = $element['name'];
            $intensity = $element['intensity'];
            $id = $element['id'];
            echo '<script type="text/javascript">$(document).ready(function () {
                    let ex = "' . $item . '";
                    let intensity = "' . $intensity . '";
                    let id = "' . $id . '";
                    addExercise(ex, intensity,id);});</script>';
        }
        ?>
        <div class="col-md-6">
            <div class="well" id="bigLeft" >
                <div class="row" >

                    <form action="addexercise.php" method="post">
                        <div class=" col-md-7 ">  <input class ="input form-control" type="text"  placeholder="Назва товару" name ="name" id="prodExAdd" required></div>
                        <div class=" col-md-3 ">  <input class ="input form-control" type="number" min="1" max="10"  placeholder="Інтенсивність" name="intensity" id="prodIntenseAdd" required></div>
                        <div class="col-lg-2 col-md-2 col-sm-4"> <button class="btn btn-primary" id="add" name="add_exercises">Додати</button></div>
                    </form>
                </div>
                <div class="row" style="margin-top: 4%; font-size: 18px;">
                    <span class="col-md-4" style="text-align: center">Назва</span>
                    <span class="col-md-4" style="text-align: center">Інтенсивність</span>
                </div>
                <div class="row bottomRows" id="copyThis" style="display: none" >
                    <span class="col-md-4   exName" style="text-align: center"></span>
                    <span class="col-md-4   intensity" style="text-align: center"></span>
                    <span class=" col-md-4 " style="text-align: center">
                        <button class="btn btn-default Bought" id="buy">Додати</button>
                    </span>
                </div>

            </div>

        </div>
        <div class="col-md-3 well" >
            <div class="row" style="text-align: center">
                <h2>Додано</h2>
            </div>
            <form class="row bottomRows" action="addexercise.php" method="post" style="min-height: 150px; margin-left:0px; margin-right: 0px; margin-top: 20px"  id="prodLeft">
                <div id="copyLeftEx" style="display: none; ">
                    <span class="col-md-3" id="prodLeftEx" style="padding-top: 5px; text-align: center"></span>
                    <span class="col-md-7"><input name="time" class ="input form-control" type="number" min="1" max="100000"  id="prodQuantity" style="display: inline">
                    </span>
                    <input style="display: none"  value="" name="id" class="id" id="currProdId">
                    <button type="submit" name="add_training" class="col-md-2 btnOk" style="display: inline">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>
