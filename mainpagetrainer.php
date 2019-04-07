<?php
require_once './app/header.php';
require_once './app/header2.php';

class Coach {

    var $id;
    var $timezone;
    var $date;
    var $client;

    function Gettz() {
        echo $this->$timezone;
    }

    function Settz($tz) {
        $this->timezone = $tz;
    }

    function GetClient() {
        echo $this->$client;
    }

    function SetClient($tz) {
        $this->client = $tz;
    }

    function Getdate() {
        echo $this->$date;
    }

    function Setdate($tz) {
        $this->date = $tz;
    }

    function Getid() {
        echo $this->id;
    }

    function Setid($id) {
        $this->id = $id;
    }

}

if (!isset($_SESSION['logged_coach'])) {
    header("Location: /");
}
include_once './app/header2.php';
$obj = (array) ($_SESSION['logged_coach']);
$id = $obj['id'];
$user1 = R::findOne('coachs', 'id = ?', array($id));




$errors;
if (isset($_POST['timezone'])) {
    $timezone = $_POST['timezone'];
} else {
    $timezone = $obj['timezone'];
}

$data = $_POST;
$cl_id = -1;




$today = date("Y-m-d");

$clients = R::find('users', 'coach = ?', array($id));

if (isset($data['check_client'])) {
    $cl_id = $data['ids'];
    $date = $obj['date'];
    $object = new Coach;
    $object->Setid($id);
    $object->Settz($_POST['timezone']);
    $object->Setdate($date);
    $object->SetClient($cl_id);
    unset($_SESSION['logged_coach']);
    $_SESSION ['logged_coach'] = $object;


    $user = R::findOne('users', 'id = ?', array($cl_id));




    $meals = R::find('meal', 'client_id = ? AND date = ?', array($cl_id, $today));
    $training = R::find('training', 'client_id = ? AND date = ?', array($cl_id, $today));

    $c1 = 0;
    $p1 = 0;
    $f1 = 0;
    $ca = 0;
    $cm1 = 0;
    $pm1 = 0;
    $fm1 = 0;
    $cma = 0;
    $day_stats = R::findOne('daystats', 'client_id = ? AND date = ?', array($cl_id, $today));
    $old_date_timestamp = strtotime($today);
    $new_date = date('Y-m', $old_date_timestamp);
    if ($day_stats) {
        $c1 = $day_stats['calories_added'];
        $p1 = $day_stats['proteins_added'];
        $f1 = $day_stats['fats_added'];
        $ca = $day_stats['carbohydrates_added'];
    }
    $month_stats = R::findOne('monthstats', 'client_id = ? AND date = ?', array($cl_id, $new_date));
    if ($month_stats) {
        $cm1 = $month_stats['calories_added'];
        $pm1 = $month_stats['proteins_added'];
        $fm1 = $month_stats['fats_added'];
        $cma = $month_stats['carbohydrates_added'];
    }
    $cp = (int) ($c1 * 100) / $user['cal'];
    $pp = (int) ($p1 * 100) / $user['prots'];
    $fp = (int) ($f1 * 100) / $user['fats'];
    $cap = (int) ($ca * 100) / $user['carb'];
    $cpm = (int) ($cm1 * 100) / $user['cal'];
    $ppm = (int) ($pm1 * 100) / $user['prots'];
    $fpm = (int) ($fm1 * 100) / $user['fats'];
    $capm = (int) ($cma * 100) / $user['carb'];

    echo '<script type="text/javascript">$(document).ready(function () {
                    let cp = "' . $cp . '";
                    let pp = "' . $pp . '";
                    let fp = "' . $fp . '";
                    let cap = "' . $cap . '";
                    let cp2 = "' . $cpm . '";
                    let pp2 = "' . $ppm . '";
                    let fp2 = "' . $fpm . '";
                    let cap2 = "' . $capm . '";
updateProgress(cp, pp, fp,cap,cp2,pp2,fp2,cap2);});</script>';
}


if (isset($data['bthDateForward'])) {
    $date = $obj['date'];
    $today = date('Y-m-d', strtotime($date . ' +1 day'));
    $cl_id = $obj['client'];
    $object = new Coach;
    $object->Setid($id);
    $object->Settz($_POST['timezone']);
    $object->Setdate($today);

    $object->SetClient($cl_id);
    unset($_SESSION['logged_coach']);
    $_SESSION ['logged_coach'] = $object;

    $user = R::findOne('users', 'id = ?', array($cl_id));




    $meals = R::find('meal', 'client_id = ? AND date = ?', array($cl_id, $today));
    $training = R::find('training', 'client_id = ? AND date = ?', array($cl_id, $today));

    $c1 = 0;
    $p1 = 0;
    $f1 = 0;
    $ca = 0;
    $cm1 = 0;
    $pm1 = 0;
    $fm1 = 0;
    $cma = 0;

    $old_date_timestamp = strtotime($today);
    $new_date = date('Y-m', $old_date_timestamp);
    $day_stats = R::findOne('daystats', 'client_id = ? AND date = ?', array($cl_id, $today));

    if ($day_stats) {
        $c1 = $day_stats['calories_added'];
        $p1 = $day_stats['proteins_added'];
        $f1 = $day_stats['fats_added'];
        $ca = $day_stats['carbohydrates_added'];
    }
    $month_stats = R::findOne('monthstats', 'client_id = ? AND date = ?', array($cl_id, $new_date));
    if ($month_stats) {
        $cm1 = $month_stats['calories_added'];
        $pm1 = $month_stats['proteins_added'];
        $fm1 = $month_stats['fats_added'];
        $cma = $month_stats['carbohydrates_added'];
    }
    $cp = (int) ($c1 * 100) / $user['cal'];
    $pp = (int) ($p1 * 100) / $user['prots'];
    $fp = (int) ($f1 * 100) / $user['fats'];
    $cap = (int) ($ca * 100) / $user['carb'];
    $cpm = (int) ($cm1 * 100) / $user['cal'];
    $ppm = (int) ($pm1 * 100) / $user['prots'];
    $fpm = (int) ($fm1 * 100) / $user['fats'];
    $capm = (int) ($cma * 100) / $user['carb'];

    echo '<script type="text/javascript">$(document).ready(function () {
                    let cp = "' . $cp . '";
                    let pp = "' . $pp . '";
                    let fp = "' . $fp . '";
                    let cap = "' . $cap . '";
                    let cp2 = "' . $cpm . '";
                    let pp2 = "' . $ppm . '";
                    let fp2 = "' . $fpm . '";
                    let cap2 = "' . $capm . '";
updateProgress(cp, pp, fp,cap,cp2,pp2,fp2,cap2);});</script>';
}


foreach ($clients as $client) {
    $item = $client['fname'];
    $ids = $client['id'];
    echo '<script type="text/javascript">$(document).ready(function () {
                    let item = "' . $item . '";
                    let ids = "' . $ids . '";
                    addClients(item,ids);});</script>';
}
$today = date("Y-m-d");

if (isset($data['btnBackDate'])) {

    $date = $obj['date'];
    $today = date('Y-m-d', strtotime($date . ' -1 day'));
    $cl_id = $obj['client'];
    $object = new Coach;
    $object->Setid($id);
    $object->Settz($_POST['timezone']);
    $object->Setdate($today);

    $object->SetClient($cl_id);
    unset($_SESSION['logged_coach']);
    $_SESSION ['logged_coach'] = $object;

    $user = R::findOne('users', 'id = ?', array($cl_id));




    $meals = R::find('meal', 'client_id = ? AND date = ?', array($cl_id, $today));
    $training = R::find('training', 'client_id = ? AND date = ?', array($cl_id, $today));

    $c1 = 0;
    $p1 = 0;
    $f1 = 0;
    $ca = 0;
    $cm1 = 0;
    $pm1 = 0;
    $fm1 = 0;
    $cma = 0;

    $old_date_timestamp = strtotime($today);
    $new_date = date('Y-m', $old_date_timestamp);
    $day_stats = R::findOne('daystats', 'client_id = ? AND date = ?', array($cl_id, $today));

    if ($day_stats) {
        $c1 = $day_stats['calories_added'];
        $p1 = $day_stats['proteins_added'];
        $f1 = $day_stats['fats_added'];
        $ca = $day_stats['carbohydrates_added'];
    }
    $month_stats = R::findOne('monthstats', 'client_id = ? AND date = ?', array($cl_id, $new_date));
    if ($month_stats) {
        $cm1 = $month_stats['calories_added'];
        $pm1 = $month_stats['proteins_added'];
        $fm1 = $month_stats['fats_added'];
        $cma = $month_stats['carbohydrates_added'];
    }
    $cp = (int) ($c1 * 100) / $user['cal'];
    $pp = (int) ($p1 * 100) / $user['prots'];
    $fp = (int) ($f1 * 100) / $user['fats'];
    $cap = (int) ($ca * 100) / $user['carb'];
    $cpm = (int) ($cm1 * 100) / $user['cal'];
    $ppm = (int) ($pm1 * 100) / $user['prots'];
    $fpm = (int) ($fm1 * 100) / $user['fats'];
    $capm = (int) ($cma * 100) / $user['carb'];

    echo '<script type="text/javascript">$(document).ready(function () {
                    let cp = "' . $cp . '";
                    let pp = "' . $pp . '";
                    let fp = "' . $fp . '";
                    let cap = "' . $cap . '";
                    let cp2 = "' . $cpm . '";
                    let pp2 = "' . $ppm . '";
                    let fp2 = "' . $fpm . '";
                    let cap2 = "' . $capm . '";
updateProgress(cp, pp, fp,cap,cp2,pp2,fp2,cap2);});</script>';
}
?>
<nav class="menu_wrap">
    <?php if ($cl_id != -1): ?>
        <div   class="col-md-1  navElement navLinks" style="font-size:20px" ><?php echo $user['fname']; ?></div>
    <?php else : ?>
        <div   class="col-md-1" ></div>
    <?php endif; ?>    
    <ul class="col-md-2 navElement">
        <li class="col-md-12"><a href="" class="navLinks">Клієнти</a>
            <ul class="col-md-12 " id="navList">
                <form method="post" action="mainpagetrainer.php" class="copyThisLiItem" style="display: none"><input name="ids" id="secretInput" value="" style="display: none"/><button style="background: none; border: none" type="submit" name="check_client" class="col-md-12"  ><li  class="navLinks listAfterList" id="listItemText"></li></button></form>
            </ul>
        </li>
    </ul>

    <div class="col-md-5 navElementName"><h2 class="webName">Рахуємо калорії</h2></div>
    <div style="text-align: right; height: 100%; padding-top:13px;" class="col-md-3 "><h4 style="text-align: right; display: inline; color: white; margin-right: 1%;  "><?php echo $user1['fname'] . ' ' . $user1['name'] . ' ' . $user1['pob']; ?></h4>
    </div>
    <a href="logout.php" class="navElementImg col-md-1" style="padding-left: 0px; padding-top:8px;"><img src="https://image.flaticon.com/icons/svg/126/126467.svg "  width="32" height="32"></a>
</div>
<?php if ($cl_id != -1): ?>
    <div class="continer-fluid" style="margin-top: 6%">
        <div class="container"> 
            <div class="col-md-4">
                <div style="text-align: center"><h4>За день</h4></div>
                <div class="col-md-6">
                    <h5>Набрано калорій</h5>
                    <progress id="calProg" max="100" value="0">
                    </progress>
                    <h5><?php echo $c1 . '/' . $user['cal']; ?></h5>
                </div>
                <div class="col-md-6">    
                    <h5>Набрано білків</h5>
                    <progress id="protProg" max="100" value="0">
                    </progress>
                    <h5><?php echo $p1 . '/' . $user['prots']; ?></h5>
                </div>
                <div class="col-md-6">
                    <h5>Набрано жирів</h5>
                    <progress id="fatsProg" max="100" value="0">
                    </progress>
                    <h5><?php echo $f1 . '/' . $user['fats']; ?></h5>
                </div>
                <div class="col-md-6">
                    <h5>Набрано вуглеводів</h5>
                    <progress  id="carboProg" max="100" value="0">
                    </progress>
                    <h5><?php echo $ca . '/' . $user['carb']; ?></h5>
                </div>
            </div>
            <div class="col-md-4">
                <img class="calendarIcon" src="https://image.flaticon.com/icons/svg/747/747310.svg">
                <div class="dateChange">
                    <form action="mainpagetrainer.php" method="post"><button type="submit" name="btnBackDate" class="btnBackDate" ></button></form>
                    <div class="currDate"><?php echo $today; ?></div>
                    <form action="mainpagetrainer.php" method="post"><button type="submit" name="bthDateForward" class="bthDateForward"></button></form>
                </div>
            </div>
            <div class="col-md-4">
                <div style="text-align: center"><h4>За місяць</h4></div>
                <div class="col-md-6">
                    <h5>Набрано калорій</h5>
                    <progress id="calProgM" max="100" value="0">
                    </progress>
                    <h5><?php echo $cm1 . '/' . $user['cal']; ?></h5>
                </div>
                <div class="col-md-6">    
                    <h5>Набрано білків</h5>
                    <progress  id="protProgM" max="100" value="0">
                    </progress>
                    <h5><?php echo $pm1 . '/' . $user['prots']; ?></h5>
                </div>
                <div class="col-md-6">
                    <h5>Набрано жирів</h5>
                    <progress  id="fatsProgM" max="100" value="25">
                    </progress>
                    <h5><?php echo $fm1 . '/' . $user['fats']; ?></h5>
                </div>
                <div class="col-md-6">
                    <h5>Набрано вуглеводів</h5>
                    <progress id="carboProgM" max="100" value="0">
                    </progress>
                    <h5><?php echo $cma . '/' . $user['carb']; ?></h5>
                </div>
            </div>
        </div>
        <div style="text-align: center"><h4>Вправи та прийоми їжі</h4></div>
        <div class="info">
            <div class="well" style="width: 40%">
                <div class="well" style="padding-bottom:30px">
                    <span class="prodConsName col-md-2">Назва</span>
                    <span class="prodConsumedPort col-md-2">Порція</span>
                    <span class="prodConsumedCal col-md-2">Калорії</span>
                    <span class="prodConsumedProt col-md-2">Білки</span>
                    <span class="prodConsumedFats col-md-2">Жири</span>
                    <span class="prodConsumedCarbo col-md-2">Вуглеводи</span>
                </div>
                <?php
                foreach ($meals as $meal):
                    $product = R::findOne('products', 'id = ?', array($meal['product_id']));
                    $pc = $product['caloriess'];
                    $pf = $product['fats'];
                    $pp = $product['proteins'];
                    $pca = $product['carbohydrates'];
                    ?>
                    <div class = " well" style="padding-bottom:30px">
                        <div class = "prodConsName col-md-4"><?php echo $product['name']; ?></div>
                        <div class = "prodConsumedPort col-md-1"><?php echo $meal['portion']; ?></div> 
                        <div class = "prodConsumedCal col-md-2"><?php echo $pc * $meal['portion'] / 100; ?></div>
                        <div class = "prodConsumedProt col-md-2"><?php echo $pp * $meal['portion'] / 100; ?></div>
                        <div class = "prodConsumedFats col-md-1"><?php echo $pf * $meal['portion'] / 100; ?></div>
                        <div class = "prodConsumedCarbo col-md-2"><?php echo $pca * $meal['portion'] / 100; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="well" style="width: 40%">
                <div class="well" style="padding-bottom:30px">
                    <span class="prodConsName col-md-5">Назва</span>
                    <span class="prodConsumedPort col-md-3">Інтенсивність</span>
                    <span class="prodConsumedCal col-md-1">Час</span>
                    <span class="prodConsumedProt col-md-3">Витрачено</span>
                </div>
                <?php
                foreach ($training as $tr):
                    $ex = R::findOne('exercises', 'id = ?', array($tr['exercise_id']));
                    $en = $ex['name'];
                    $ei = $ex['intensity'];
                    $et = $tr['time'];
                    $es = $et * $ei * 10;
                    ?>
                    <div class="well" style="padding-bottom:30px">
                        <span class="prodConsName col-md-5"><?php echo $en; ?></span>
                        <span class="prodConsumedPort col-md-3"><?php echo $ei; ?></span>
                        <span class="prodConsumedCal col-md-1"><?php echo $et; ?></span>
                        <span class="prodConsumedProt col-md-3"><?php echo $es; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>