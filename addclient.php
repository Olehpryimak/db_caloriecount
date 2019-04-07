<?php
require_once './app/header.php';
require_once './app/header2.php';




if (!isset($_SESSION['logged_coach'])) {
    header("Location: /");
}
include_once './app/header2.php';
$obj = (array) ($_SESSION['logged_coach']);
$id = $obj['id'];
$user1 = R::findOne('coachs', 'id = ?', array($id));


include_once './app/header2.php';


$errors;
if (isset($_POST['timezone'])) {
    $timezone = $_POST['timezone'];
} else {
    $timezone = $obj['timezone'];
}

$data = $_POST;
$cl_id = -2;

if (isset($data['add_client'])) {
    $clien = R::Load('users',$data['id']);
    $clien->coach = $id;
    R::store($clien);
}

$today = date("Y-m-d");

$clients = R::find('users', 'coach = ?', array(0));

foreach ($clients as $client) {
    $name = $client['name'];
    $itemC = $client['fname'];
    $idsC = $client['id'];
    $num = $client['pnumber'];
    $thirdname = $client['pob'];
    echo '<script type="text/javascript">$(document).ready(function () {
                    let item = "' . $itemC . '";
                    let ids = "' . $idsC . '";
                    let phone = "' . $num . '";
                    let sname = "' . $name . '";
                    let third = "' . $thirdname . '";    
                    addClientToTrainer(item,sname,third, phone,ids);});</script>';
}
foreach ($clients as $client) {
    $item = $client['fname'];
    $ids = $client['id'];
    echo '<script type="text/javascript">$(document).ready(function () {
                    let item = "' . $item . '";
                    let ids = "' . $ids . '";
                    addClients(item,ids);});</script>';
}
?>
<nav class="menu_wrap">
    <?php if ($cl_id != -1): ?>
        <div   class="col-md-1  navElement navLinks" style="font-size:20px"><?php echo $user['fname']; ?></div>
    <?php else : ?>
        <div   class="col-md-1" ></div>
    <?php endif; ?> 

    <ul class="col-md-2 navElement">
        <li class="col-md-12"><a href="mainpagetrainer.php" class="navLinks">Клієнти</a>
            <ul class="col-md-12 " id="navList">
                <form method="post" action="mainpagetrainer.php" class="copyThisLiItem" style="display: none"><input name="ids" id="secretInput" value="" style="display: none"/><button style="background: none; border: none" type="submit" name="check_client" class="col-md-12"  ><li  class="navLinks listAfterList" id="listItemText"></li></button></form>
            </ul>
        </li>
    </ul>
    <ul class="col-md-2 navElement">
        <a href="addClient.php" class="navLinks">Додати клієнта</a>
    </ul>
    <div class="col-md-3 navElementName"><h2 class="webName">Рахуємо калорії</h2></div>
    <div style="text-align: right; height: 100%; padding-top:13px;" class="col-md-3 "><h4 style="text-align: right; display: inline; color: white; margin-right: 1%;  "><?php echo $user1['fname'] . ' ' . $user1['name'] . ' ' . $user1['pob'].' [ T ]'; ?></h4>
    </div>
    <a href="logout.php" class="navElementImg col-md-1" style="padding-left: 0px; padding-top:8px;"><img src="https://image.flaticon.com/icons/svg/126/126467.svg "  width="32" height="32"></a>
</nav>
<div class="container-fluid" style="margin-top: 7%">
    <div class="row">   
        <div class="col-md-1"></div>
        <div class="col-md-6">
            <div class="well" id="bigLeftTrain"> 
                <div class="row" style="margin-top: 10px; margin-left: 10px">
                    <span class="col-md-2">Ім'я</span>
                    <span class="col-md-2">Прізвище</span>
                    <span class="col-md-3">По-батькові</span>
                    <span class="col-md-3">Номер телефону</span>
                </div>
                <div class="row bottomRows" id="copyThisClient" style="display: none" >
                    <span class="col-md-2   prodName" id='clientName'></span>
                    <span class="col-md-2   calProd" id='clientSurName' style="text-align: center"></span>
                    <span class="col-md-3   fatProd" id='clientThirdName' style="text-align: center"></span>
                    <span class="col-md-3   protProd" id='clientPhone' style="text-align: center"></span>
                    <span class=" col-md-2 ">
                        <button class="btn btn-default Bought" id="buy">Додати</button>
                    </span></div>
            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-3 well" >
            <div class="row" style="text-align: center">
                <h2>Додано</h2>
            </div>
            <form class="row bottomRows" method="post" action="addclient.php" style="min-height: 150px; margin-left:0px; margin-right: 0px; margin-top: 20px"  id="prodLeft" >
                <div id="copyLeftEx" style="display: none; ">
                    <span class="col-md-8" id="prodLeftEx" style="padding-top: 5px; text-align: center"></span>
                    </span>
                    <input style="display: none"  value="" name="id" class="id" id="currProdId">
                    <button type="submit" name="add_client" class="col-md-4 btnOk" style="display: inline">OK</button>
                </div>
            </form>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>

