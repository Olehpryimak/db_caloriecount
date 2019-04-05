<?php
include_once './app/header.php';
if (!isset($_SESSION['logged_user'])) {
    header("Location: /");
}
include_once './public/../app/header2.php';
$obj = (array) ($_SESSION['logged_user']);

$id = $obj['id'];
$coach = $obj['coach'];
if ($coach == TRUE) {
    $user = R::findOne('coachs', 'id = ?', array($id));
} else {
    $user = R::findOne('users', 'id = ?', array($id));
}

$errors;
if (isset($_POST['timezone'])) {
    $timezone = $_POST['timezone'];
} else {
    $timezone = $obj['timezone'];
}

$today = date("Y-m-d");
$meals = R::find('meal', 'client_id = ? AND date = ?', array($id, $today));
?>
<div class="menu_wrap">
    <div   class="col-md-1 navElementImg" ><img src="https://image.flaticon.com/icons/svg/25/25694.svg" class="homeImg" style="display:inline-block;"></div>
    <div   class="col-md-1 navElement"><h4 style="display: inline;margin-right: 1%"></h4>
        <a href="upload.php" class="navLinks">Додати прийом</a>
    </div>
    <div   class="col-md-1 navElement"><h4 style="display: inline;margin-right: 1%"></h4>
        <a href="addexercise.php" class="navLinks">Додати вправу</a>
    </div>
    <div   class="col-md-1 navElement"><h4 style="display: inline;margin-right: 1%"></h4>
        <a href="checkstats.php" class="navLinks">Статистика</a>
    </div>
    <div class="col-md-4 navElementName"><h2 class="webName">Рахуємо калорії</h2></div>
    <div style="text-align: right; height: 100%; padding-top:13px;" class="col-md-3 "><h4 style="text-align: right; display: inline; color: white; margin-right: 1%;  ">Тарас Назар</h4>
    </div>
    <div   class="navElementImg col-md-1" style="padding-left: 0px; padding-top:8px;"><img src="https://image.flaticon.com/icons/svg/126/126467.svg "  width="32" height="32"></div>
</div>
<div class="continer-fluid" style="margin-top: 6%">
    <div>
        <img class="calendarIcon" src="https://image.flaticon.com/icons/svg/747/747310.svg">
    </div>
    <div class="dateChange">
        <button class="btnBackDate"></button>
        <div class="currDate">XXX</div>
        <button class="bthDateForward"></button>
    </div>
    <div class="info">
        <div class="productsConsumed">
            <div class="exampleConsumed">
                <span class="prodConsName col-md-2">Назва</span>
                <span class="prodConsumedPort col-md-2" >Порція</span>
                <span class="prodConsumedCal col-md-2">Калорії</span>
                <span class="prodConsumedProt col-md-2">Білки</span>
                <span class="prodConsumedFats col-md-2">Жири</span>
                <span class="prodConsumedCarbo col-md-2">Вуглеводи</span>
            </div>
            <?php foreach ($meals as $meal):
                $product = R::findOne('products', 'id = ?', array($meal['id']));
                $pc = $product['caloriess'];
                $pf = $product['fats'];
                $pp = $product['proteins'];
                $pca = $product['carbohydrates'];
                ?>
                <div class = "exampleConsumed">
                    <span class = "prodConsName col-md-2"><?php echo $product['name']; ?></span>
                    <span class = "prodConsumedPort col-md-2"><?php echo $meal['portion']; ?></span>
                    <span class = "prodConsumedCal col-md-2"><?php echo $pc; ?></span>
                    <span class = "prodConsumedProt col-md-2"><?php echo $pp; ?></span>
                    <span class = "prodConsumedFats col-md-2"><?php echo $pf; ?></span>
                    <span class = "prodConsumedCarbo col-md-2"><?php echo $pca; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="exercisesCompleted"></div>
    </div>
</div>

</body>
</html>