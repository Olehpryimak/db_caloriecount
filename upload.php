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

$products = R::find('products');

$day_stats = R::findOne('day_stats', 'client_id = ?', array($id));
?>
<div class="menu_wrap row">
    <div style="margin:1%;"  class="col-md-2"><h4 style="display: inline;margin-right: 1%"></h4>
        <a href="upload.php">Додати прийом</a>
    </div>
    <div style="margin:1%;"  class="col-md-2"><h4 style="display: inline;margin-right: 1%"></h4>
        <a href="addexercise.php">Додати вправу</a>
    </div>
    <div style="margin:1%;"  class="col-md-2"><h4 style="display: inline;margin-right: 1%"></h4>
        <a href="checkstats.php">Статистика</a>
    </div>
    <div style=" margin:1%;text-align: right;"  class="col-md-5"><h4 style="text-align: right; display: inline; margin-right: 1%"><?php echo $user['fname'] . ' ' . $user['name'] . ' ' . $user['pob']; ?></h4>
        <a href="logout.php"><img src="public/img/exit_image.jpg" width="32" height="32" border="0"></a>
    </div>
</div>
<div class="container-fluid" style="margin-top: 7%">
    <div class="row">
        <div class="col-md-3">
            <div class="well">
                <div class="row" style="margin: 4%">
                    <span class="col-md-4">Набрано</span>
                    <span class="col-md-2">Витрачено</span>
                </div> 
                <div class="well row">
                    <span class="col-md-4"><?php echo $day_stats['calories_added']; ?></span>
                    <span class="col-md-2"><?php echo $day_stats['calories_waisted']; ?></span>
                </div>
            </div></div>
        <?php
        foreach ($products as $element) {

            $item = $element['name'];
            $fat = $element['fats'];
            $cal = $element['caloriess'];
            $carb = $element['carbohydrates'];
            $prot = $element['proteins'];
            echo '<script type="text/javascript">$(document).ready(function () {
                    let ex = "' . $item . '";
                    let fat = "' . $fat . '";
                    let cal = "' . $cal . '";
                    let carb = "' . $carb . '";
                    let prot = "' . $prot . '";
                    addProd(ex, cal, fat, prot, carb);});</script>';
        }
        ?>
        <div class="col-md-6">
            <div class="well" id="bigLeft">
                <div class="row" >
                    <div class=" col-md-4 ">  <input class ="input form-control" type="text"  placeholder="Назва товару" id="prodNameAdd"></div>
                    <div class=" col-md-2 " style="align-content: center">  <input class ="input form-control" type="number" min="1" max="10"  placeholder="Калорії" id="cal"></div>
                    <div class=" col-md-2 " style="align-content: center">  <input class ="input form-control" type="number" min="1" max="10"  placeholder="Жири" id="fats"></div>
                    <div class=" col-md-2 " style="align-content: center">  <input class ="input form-control" type="number" min="1" max="10"  placeholder="Білки" id="proteins"></div>
                    <div class=" col-md-2 " style="align-content: center">  <input class ="input form-control" type="number" min="1" max="10"  placeholder="Вуглеводи" id="carbo"></div>

                    <div class="col-md-12 " style="margin-top:10px "> <button class="btn btn-primary" id="add" style="width: 100%">Додати</button></div>
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
            <div class="row">
                <h2>Додано</h2>
            </div>
            <div class="row bottomRows" id="prodLeft">
                <span id="copyLeftEx" style="display: none">
                    <span id="prodLeftEx"></span>
                    <span ><input class ="input form-control" type="number" min="1" max="10"  id="prodQuantity"></span>
                </span>
            </div>
        </div>    
    </div>
</div>

<script>
    document.getElementById("tz").value = new Date().getTimezoneOffset();
</script>