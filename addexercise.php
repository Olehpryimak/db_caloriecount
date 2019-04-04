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

$exercises = R::find('exercises');
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
<div class="container" style="margin-top: 7%">
    <div class="row">
        <div class="col-md-1">
            <div class="well">
                <div class="row" style="margin: 4%">
                    <span class="col-md-4">Набрано</span>
                    <span class="col-md-2">Витрачено</span>
                </div> 
                <div class="well row">
                    <span class="col-md-4"><?php echo $day_stats['calories_added']; ?></span>
                    <span class="col-md-2"><?php echo $day_stats['calories_waisted']; ?></span>
                </div> 
            </div>
        </div>
        <div class="col-md-7">
            <?php
            foreach ($exercises as $element) {

                $item = $element['name'];
                $intensity=$element['intensity'];
                echo '<script type="text/javascript">$(document).ready(function () {
                    let ex = "' . $item . '";
                    let intensity = "' . $intensity . '";
                    addExercise(ex, intensity);});</script>';
            }
            ?>
            <div class="well" id="bigLeft" >

                <div class="row" >
                    <div class=" col-md-7 ">  <input class ="input form-control" type="text"  placeholder="Назва товару" id="prodExAdd"></div>
                     <div class=" col-md-3 ">  <input class ="input form-control" type="number" min="1" max="10"  placeholder="Інтенсивність" id="prodIntenseAdd"></div>
                    <div class="col-lg-2 col-md-2 col-sm-4"> <button class="btn btn-primary" id="add">Додати</button></div>
                </div>
                <div class="row" style="margin: 4%">
                    <span class="col-md-4">Назва</span>
                    <span class="col-md-2">Інтенсивність</span>
                </div>
                <div class="row bottomRows" id="copyThis" style="display: none" >
                    <span class="col-md-4   exName"></span>
                     <span class="col-md-2   intensity"></span>
                    <span class=" col-md-4  btnBox1">
                        <button class="btn btn-warning" id="plus">+</button>
                        <span class="counter" id="quantity">1</span>
                        <button class="btn btn-warning" id="minus">-</button>
                    </span>
                    <span class=" col-md-2 ">
                        <button class="btn btn-default Bought" id="buy">Куплено</button>
                    </span>
                </div>

            </div>    

        </div>
        <div class="col-md-2 well" style="margin: 5%">
            <div class="row">
                <h2>Додано</h2>
            </div>
            <div class="row bottomRows" id="prodLeft">
                <span id="copyLeftEx" style="display: none">
                    <span id="prodLeftEx"></span>
                    <span ><button class="btn btn-warning" id="prodLeftQuantity" disabled>1</button></span>
                </span>
            </div>
        </div>
    </div>
</div>
