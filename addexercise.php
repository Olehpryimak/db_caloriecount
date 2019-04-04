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
            </div>
        </div>
        <div class="col-md-7">


            <div class="well">
                <div class="row" style="margin: 4%">
                        <span class="col-md-4">Назва</span>
                        <span class="col-md-2">Інтенсивність</span>
                    </div> 
                <?php foreach ($exercises as $element): ?>
                    <div class="well row">
                        <span class="col-md-4"><?php echo $element['name']; ?></span>
                        <span class="col-md-2"><?php echo $element['intensity']; ?></span>
                    </div> 
                <?php endforeach; ?> 
            </div>    

        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<script>
    document.getElementById("tz").value = new Date().getTimezoneOffset();
</script>