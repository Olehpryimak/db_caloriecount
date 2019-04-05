<?php
include_once './app/header.php';
if (!isset($_SESSION['logged_user'])) {
    header("Location: /");
}
include_once './app/header2.php';
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

$data = $_POST;

$today = date("Y-m-d");

if (isset($data['add_product'])) {
    $user = R::dispense('products');
    $user->caloriess = $data['calories'];
    $user->name = $data['name'];
    $user->proteins = $data['proteins'];
    $user->fats = $data['fats'];
    $user->carbohydrates = $data['carbohydrates'];
    $user->portion = 100;
    R::store($user);
}

if (isset($data['add_record'])) {
    $user = R::dispense('meal');
    $user->client_id = $id;
    $user->product_id = $data['id'];
    $user->portion = $data['weight'];
    $user->date= $today;
    R::store($user);

    $product = R::load('products', $data['id']);

   
    $day_stats = R::findOne('daystats', 'client_id = ? AND date = ?', array($id, $today));
    if ($day_stats) {
        $c = $day_stats['calories_added'];
        $p = $day_stats['proteins_added'];
        $f = $day_stats['fats_added'];
        $ca = $day_stats['carbohydrates_added'];
        $cw = $day_stats['calories_waisted'];
        R::trash($day_stats);


        $day = R::dispense('daystats');
        $day->proteins_added = $p + ($product['proteins'] * $data['weight']) / 100;
        $day->fats_added = $f + ($product['fats'] * $data['weight']) / 100;
        $day->calories_added = $c + ($product['caloriess'] * $data['weight']) / 100;
        $day->carbohydrates_added = $ca + ($product['carbohydrates'] * $data['weight']) / 100;
        $day->calories_waisted = $cw;
        $day->date = $today;
        $day->client_id = $id;
        R::store($day);
    } else {
        $day = R::dispense('daystats');
        $day->proteins_added = ($product['proteins'] * $data['weight']) / 100;
        $day->calories_added = ($product['caloriess'] * $data['weight']) / 100;
        $day->fats_added = ($product['fats'] * $data['weight']) / 100;
        $day->carbohydrates_added = ($product['carbohydrates'] * $data['weight']) / 100;
        $day->calories_waisted = 0;
        $day->date = $today;
        $day->client_id = $id;
        R::store($day);
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


$products = R::find('products');
?>
<div class="menu_wrap">
    <a href="mainpage.php"> <div   class="col-md-1 navElementImg" ><img src="https://image.flaticon.com/icons/svg/25/25694.svg" class="homeImg" style="display:inline-block;"></div></a>
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
    <div style="text-align: right; height: 100%; padding-top:13px;" class="col-md-3 "><h4 style="text-align: right; display: inline; color: white; margin-right: 1%;  "><?php echo $user['fname'] . ' ' . $user['name'] . ' ' . $user['pob']; ?></h4>
    </div>
    <a href="logout.php" class="navElementImg col-md-1" style="padding-left: 0px; padding-top:8px;"><img src="https://image.flaticon.com/icons/svg/126/126467.svg "  width="32" height="32"></a>
</div>
<div class="container-fluid" style="margin-top: 7%">
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
        foreach ($products as $element) {

            $item = $element['name'];
            $fat = $element['fats'];
            $cal = $element['caloriess'];
            $carb = $element['carbohydrates'];
            $prot = $element['proteins'];
            $id = $element['id'];
            echo '<script type="text/javascript">$(document).ready(function () {
                    let ex = "' . $item . '";
                    let fat = "' . $fat . '";
                    let cal = "' . $cal . '";
                    let ide = "' . $id . '";
                    let carb = "' . $carb . '";
                    let prot = "' . $prot . '";
                    addProd(ex, cal, fat, prot, carb,ide);});</script>';
        }
        ?>
        <div class="col-md-6">
            <div class="well" id="bigLeft">
                <div class="row" >
                    <form action="/upload.php" method="post">
                        <div class=" col-md-4 ">  <input class ="input form-control" type="text"  placeholder="Назва товару" name="name" id="prodNameAdd" required></div>
                        <div class=" col-md-2 " style="align-content: center">  <input class ="input form-control" name="calories" type="number" min="1" max="1000"  placeholder="Калорії" required></div>
                        <div class=" col-md-2 " style="align-content: center">  <input class ="input form-control" name="fats" type="number" min="1" max="1000"  placeholder="Жири"  required></div>
                        <div class=" col-md-2 " style="align-content: center">  <input class ="input form-control" name="proteins" type="number" min="1" max="1000"  placeholder="Білки"  required></div>
                        <div class=" col-md-2 " style="align-content: center">  <input class ="input form-control" name="carbohydrates" type="number" min="1" max="1000"  placeholder="Вуглеводи"  required></div>

                        <div class="col-md-12 " style="margin-top:10px "> <button class="btn btn-primary" id="add" name="add_product" style="width: 100%">Додати</button></div>
                    </form>
                </div> 
                <div class="row" style="margin-top: 10px; margin-left: 10px">
                    <span class="col-md-2">Назва</span>
                    <span class="col-md-2">Калорій</span>
                    <span class="col-md-2">Жирів</span>
                    <span class="col-md-2">Білків</span>
                    <span class="col-md-2">Вуглеводів</span>
                </div>
                <div class="row bottomRows" id="copyThis" style="display: none" >
                    <span class="col-md-2   prodName"></span>
                    <span class="col-md-2   calProd" style="text-align: center"></span>
                    <span class="col-md-2   fatProd" style="text-align: center"></span>
                    <span class="col-md-2   protProd" style="text-align: center"></span>
                    <span class="col-md-2   carboProd" style="text-align: center"></span>
                    <span class=" col-md-2 ">
                        <button class="btn btn-default Bought" id="buy">Додати</button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 well" >
            <div class="row" style="text-align: center">
                <h2>Додано</h2>
            </div>
            <form class="row bottomRows" method="post" action="upload.php" style="min-height: 150px; margin-left:0px; margin-right: 0px; margin-top: 20px"  id="prodLeft" >
                <div id="copyLeftEx" style="display: none; ">
                    <span class="col-md-3" id="prodLeftEx" style="padding-top: 5px; text-align: center"></span>
                    <span class="col-md-7"><input name="weight" class ="input form-control" type="number" min="1" max="100000"  id="prodQuantity" style="display: inline">
                    </span>
                    <input style="display: none"  value="" name="id" class="id" id="currProdId">
                    <button type="submit" name="add_record" class="col-md-2 btnOk" style="display: inline">OK</button>
                </div>
            </form>
        </div> 
    </div>
</div>