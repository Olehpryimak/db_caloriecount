<?php
require_once './app/header.php';
require_once './app/header2.php';

class User {

    var $id;
    var $timezone;
    var $date;
    var $coach;

    function Gettz() {
        echo $this->$timezone;
    }

    function Settz($tz) {
        $this->timezone = $tz;
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
    
    function GetCoach() {
        echo $this->coach;
    }

    function SetCoach($coach) {
        $this->coach = $coach;
    }

}
?>
<!--Використовуємо Bootstrap для адаптивної верстки -->
<div class="container" style="margin-top: 4%">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-7">

            <?php
            $data = $_POST;
            $errors;

// Перевіряємо з якої кнопки ми прийшли і проводимо реєстрацію
            if (isset($data['tel'])) {
                if (isset($data['do_signup'])) {

                    if (R::count('users', "tel = ?", array($data["tel"])) > 0 || R::count('coachs', "tel = ?", array($data["tel"])) > 0) {
                        $errors[] = "Користувач з таким номером вже зареєстрований!";
                    }

                    if (isset($data['name']) == FALSE) {
                        $errors[] = "Введіть ім'я!";
                    }

                    if (isset($data['password']) == FALSE) {
                        $errors[] = 'Введіть пароль';
                    }

                    if (strlen($data['password']) <= 3) {
                        $errors[] = 'Пароль надто короткий - мінімум 4 символи!';
                    }

                    if (($data['password']) != $data['password_2']) {
                        $errors[] = 'Паролі не збігаються!';
                    }

                    $time = strtotime($_POST['bdate']);
                    $new_date = date('Y-m-d', $time);
                    $date_a = new DateTime($new_date);
                    $date_b = new DateTime();
                    $interval = $date_b->diff($date_a);
                    $res = $interval->format("%Y");

                    if ($data['sex'] == "Чоловіча") {
                        $sex = TRUE;
                    } else {
                        $sex = FALSE;
                    }

                    if ($sex) {
                        $cal = 88.36 + (13.4 * (int) $data['weight']) + (4.8 * (int) $data['height'] ) - (5.7 * $res);
                    } else {
                        $cal = 447.6 + (9.2 * (int) $data['weight']) + (3.1 * (int) $data['height'] ) - (4.3 * $res);
                    }
                    $part = (int) ((int) $cal / 6);
                    $fats = (int) ($part );
                    $prots = (int) ($part );
                    $carb = 4 * $part;
                    // запис в базу даних
                    if (empty($errors)) {
                        $user = R::dispense('users');
                        $user->name = $data['name'];
                        $user->fname = $data['fname'];
                        $user->pnumber = $data['tel'];
                        $user->pob = $data['pob'];
                        $user->height = $data['height'];
                        $user->weight = $data['weight'];
                        $user->bdate = $data['bdate'];
                        $user->age = $res;
                        $user->sex = $sex;
                        $user->fats = $fats;
                        $user->prots = $prots;
                        $user->carb = $carb;
                        $user->sdate = new DateTime();
                        $user->cal = (int) $cal;
                        $user->coach = 0;
                        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
                        R::store($user);
                        ?>    
                        <div class="bg-success" style="text-align: center; font-weight: bold">
                            <?php echo 'Ви успішно пройшли реєстрацію'; ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="bg-warning" style="text-align: center; font-weight: bold">
                            <?php echo array_shift($errors); ?>
                        </div>             
                        <?php
                    }
                } else if (isset($data['do_signup_coach'])) {

                    if (R::count('users', "pnumber = ?", array($data["tel"])) > 0 || R::count('coachs', "pnumber = ?", array($data["tel"])) > 0) {
                        $errors[] = "Користувач з таким номером вже зареєстрований!";
                    }

                    if (isset($data['name']) == FALSE) {
                        $errors[] = "Введіть ім'я!";
                    }

                    if (isset($data['password']) == FALSE) {
                        $errors[] = 'Введіть пароль';
                    }

                    if (strlen($data['password']) <= 3) {
                        $errors[] = 'Пароль надто короткий - мінімум 4 символи!';
                    }

                    if (($data['password']) != $data['password_2']) {
                        $errors[] = 'Паролі не збігаються!';
                    }

                    if (empty($errors)) {
                        $user = R::dispense('coachs');
                        $user->name = $data['name'];
                        $user->fname = $data['fname'];
                        $user->pnumber = $data['tel'];
                        $user->pob = $data['pob'];
                        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
                        R::store($user);
                        ?>    
                        <div class="bg-success" style="text-align: center; font-weight: bold">
                            <?php echo 'Ви успішно пройшли реєстрацію'; ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="bg-warning" style="text-align: center; font-weight: bold">
                            <?php echo array_shift($errors); ?>
                        </div>             
                        <?php
                    }
                }
            }
            ?>                  
            <div class="well row">
                <h2 style="text-align: center">Sign Up</h2>
                <div class="form-group col-md-6">
                    <form style="text-align: center" id="signup" action="/" method="post">
                        <h3 style="text-align: center">Клієнт:</h3>
                        <br>
                        <input type="text" name="fname" value="" class="form-control" placeholder="Ваше прівище" required >
                        <br>
                        <input type="text" name="name" value="" class="form-control" placeholder="Ваше ім'я" required >
                        <br>
                        <input type="text" name="pob" value="" class="form-control" placeholder="Ваше по-батькові" required >
                        <br>
                        <input type="number" name="height" min ="140" max="220" value="" class="form-control" placeholder="Ваш зріст" required >
                        <br>
                        <input type="date" name="bdate" value="" class="form-control" placeholder="Дата народження" required >
                        <br>
                        <select size="1" title="Ваша стать"  name="sex" class="form-control" required >
                            <option >Чоловіча</option>
                            <option >Жіноча</option>
                        </select>
                        <br>
                        <input type="tel" name="tel" value="" class="form-control" placeholder="Номер телефону" required >
                        <br>
                        <input type="number" name="weight" min="40" max="300" value="" class="form-control" placeholder="Ваша вага" required >
                        <br>
                        <input type="password" name="password" value="" class="form-control" placeholder="Введіть пароль" required  >
                        <br>
                        <input type="password" name="password_2" value="" class="form-control" placeholder="Повторіть пароль" required >
                        <br>
                        <button type="submit"  class="btn btn-success" name="do_signup">Зареєструватися</button>
                    </form>
                </div>
                <div class="form-group col-md-6" >
                    <form style="text-align: center" id="signup" action="/" method="post">
                        <h3 style="text-align: center">Тренер:</h3>
                        <br>
                        <input type="text" name="fname" value="" class="form-control" placeholder="Ваше прівище" required >
                        <br>
                        <input type="text" name="name" value="" class="form-control" placeholder="Ваше ім'я" required >
                        <br>
                        <input type="text" name="pob" value="" class="form-control" placeholder="Ваше по-батькові" required >
                        <br>
                        <input type="tel" name="tel" value="" class="form-control" placeholder="Номер телефону" required >
                        <br>
                        <input type="password" name="password" value="" class="form-control" placeholder="Введіть пароль" required  >
                        <br>
                        <input type="password" name="password_2" value="" class="form-control" placeholder="Повторіть пароль" required >
                        <br>
                        <button type="submit"  class="btn btn-success" name="do_signup_coach">Зареєструватися</button>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-3">

            <?php
            if (isset($data['do_login'])) {
                $user = R::findOne('users', 'pnumber = ?', array($data['tel']));
                if (!$user) {
                    $user = R::findOne('coachs', 'pnumber = ?', array($data['tel']));
                }
                if ($user) {
                    if (password_verify($data['password'], $user->password)) {


                        $object = new User;
                        $object->Setid($user['id']);
                        $object->Settz($_POST['timezone']);
                        $object->Setdate(date("Y-m-d"));
                        if(isset($user['sdate'])){
                            $object->SetCoach(FALSE);
                        }
                        else{
                            
                            $object->SetCoach(TRUE);
                        }


                        $_SESSION ['logged_user'] = $object;
                        ?>
                        <script>document.location.href = "./upload.php"</script>
                        <?php
                    } else {
                        $errors2 [] = 'Пароль введено не правильно!';
                    }
                } else {
                    $errors2 [] = 'Не існує користувача з таким номером телефону!';
                }


                if (!empty($errors2)) {
                    ?>
                    <div class="bg-warning" style="text-align: center; font-weight: bold">
                        <?php echo array_shift($errors2); ?>
                    </div>             
                    <?php
                }
            }
            ?>

            <div class="well">

                <div class="form-group">
                    <form action="/" method="post">
                        <div style="text-align: center">
                            <h2>Sign In</h2>
                        </div>
                        <br>
                        <input type="tel" name="tel" value="" class="form-control" placeholder="Введіть номер телефону" required>
                        <br>
                        <input type="password" name="password" value="" class="form-control" placeholder="Введіть пароль" required>
                        <input type="hidden" name="timezone" id="tz">
                        <br>

                        <div style="text-align: center">
                            <button type="submit"  name="do_login" class="btn btn-success btn-md" >Вхід</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<script>

    document.getElementById("tz").value = new Date().getTimezoneOffset();

</script>
<?php
require_once './app/footer.php';
?>
