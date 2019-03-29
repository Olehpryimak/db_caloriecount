<?php
include_once './app/header.php';
if (!isset($_SESSION['logged_user'])) {
    header("Location: /");
}
$obj = (array)($_SESSION['logged_user']);
$id = $obj['id'];
$user = R::findOne('users', 'id = ?', array($id));
$data = $_POST;
include_once './app/header2.php';
$errors;
$requsets = R::find('requests', 'user_id = ?', array($user['id']));
if (isset($_POST['timezone'])) {
    $timezone = $_POST['timezone'];
}
else{
    $timezone = $obj['timezone'];
}
if ($_FILES) {
    if ($_FILES['filename']['type'] == "text/plain") {
        if (move_uploaded_file($_FILES['filename']['tmp_name'], $_FILES['filename']['name'])) {
            $t = file_get_contents($_FILES['filename']['name']);
            $get = mb_detect_encoding($t, array('utf-8', 'cp1251'));
            $t = iconv($get, 'UTF-8', $t);
            $num_chars = iconv_strlen($t);
            $file_array = file($_FILES['filename']['name']);
            $num_str = count($file_array);
            $path = $_FILES['filename']['name'];
            $gmm = gmmktime();
            $words = str_word_count($t, 0, 'ЙйЦцУуКкЕеНнГгШшЩщЗзХхЇїЇФфІіВвАаПпРрОоЛлДдЖжЄєЯяЧчСсМмИиТтЬьБбЮюЫыЭэ');
            $request = R::dispense('requests');
            $request->user_id = $user['id'];
            $request->num_chars = $num_chars;
            $request->num_str = $num_str;
            $request->path = $path;
            $request->words = $words;
            $request->data = $gmm;
            R::store($request);
            unlink($_FILES['filename']['name']);
        }
    } else {
        $errors [] = "Виберіть файл з розширенням txt!";
    }
}
?>
<div class="container" style="margin-top: 4%">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-5">

            <?php if (!empty($errors) && $_FILES): ?>
                <div class="bg-warning" style="text-align: center; font-weight: bold">
                    <?php echo array_shift($errors); ?>
                </div>             
            <?php elseif ($_FILES): ?>
                <div class="bg-success" style="text-align: center; font-weight: bold">
                    <?php echo $path . ' Слова - ' . $words . ' Символів - ' . $num_chars . ' Cтрічок - ' . $num_str ?>
                </div>
            <?php endif ?>

            <div class="well">
                <div style="text-align: center; display: inline-block; width: 48%; font-size:xx-large ;">
                    <?php echo $user['name']; ?>
                </div>
                <div style="text-align: center; display: inline-block; width: 48%;font-size:xx-large ; ">
                    <form action="/logout.php" method="post">
                        <button name="do_logout" class="btn btn-danger btn-lg" type="submit">Вихід</button>
                    </form>
                </div>
            </div>    
            <div class="well">

                <div class="form-group">
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <br>
                        <input type="file" name="filename" size="9" class="form-control"/>    
                        <br>
                        <input type="hidden" name="timezone" id="tz">
                        <div style="text-align: center">
                            <button type="submit" class="btn btn-success btn-md" >Завантажити</button>
                        </div>
                    </form>
                </div>

            </div>
            <?php if ($requsets) : ?>
                <table border="1" style="margin: 0 auto;text-align: center;">
                    <tr><th>Файл</th><th style="text-align: center">Слова</th><th style="text-align: center">Символи</th><th style="text-align: center">Стрічки</th><th style="text-align: center">Дата</th></tr>
                    <?php foreach ($requsets as $item): ?>
                        <tr>
                            <td><?php echo $item->path; ?></td>
                            <td><?php echo $item->words; ?></td>
                            <td><?php echo $item->num_chars; ?></td>
                            <td><?php echo $item->num_str; ?></td>
                            <td><?php
                                $time = $item->data;
                                echo date("M d Y H:i:s", $time - $timezone * 60);
                                ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif ?>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-3"></div>
    </div>
</div>
<script>
    document.getElementById("tz").value = new Date().getTimezoneOffset();
</script>