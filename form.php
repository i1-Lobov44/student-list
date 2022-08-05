<?php
require_once('FormValidator.php');
require_once('DataBase.php');
require_once('func.php');
$db = new DataBase('root', '44');

if (!empty($_POST)) {

    // валидация входных данных
    $validation = new FormValidator($_POST);
    $errors = $validation->validateForm();

    if (empty($errors)) {

        if (!empty($_COOKIE['password'])) {
            $db->DeleteInfo($_COOKIE['password']);
        }
        //Сохранение в бд
        if ($db->InsertIntoDB($_POST)) {

            $value = $_POST['name'] . ' ' . $_POST['lastName'];
            $email = $_POST['mail'];
            $time = strtotime('+10years');
            setcookie('student', $value, $time, "/");
            setcookie('email', $email, $time, "/");
            header("Location: index.php");

            exit;
        } else {
?>
            <div class="error">
                Студент с такими данными уже зарегистрирован
            </div>
<?php
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">

    <!-- jQuery DatePicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://jqueryui.com/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="datepicker.js"></script>

    <title>Document</title>
</head>

<body>

    <div class="container">

        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">

            <?php

            $arr = $db->EditInformation($_COOKIE['password']);

            $arr = !empty($_COOKIE) ? $db->EditInformation($_COOKIE['password']) : null;

            ?>

            <label for="name">Имя</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_GET['id'] == 'edit' ? $arr[1] : $_POST['name']) ?? '' ?>">
            <div class="error">
                <?= $errors['name'] ?? '' ?>
            </div>

            <label for="lastName">Фамилия</label>
            <input type="text" name="lastName" value="<?= htmlspecialchars($_GET['id'] == 'edit' ? $arr[2] : $_POST['lastName'])  ?? '' ?>">
            <div class="error">
                <?= $errors['lastName'] ?? '' ?>
            </div>

            <select name="gender">
                <option value="choose">Пол</option>
                <option <?= $_POST['gender'] == 'male' || $arr[3] == 'male' ? 'selected="true"' : '' ?> value="male">Мужской</option>
                <option <?= $_POST['gender'] == 'female' || $arr[3] == 'female' ? 'selected="true"' : '' ?> value="female">Женский</option>
                <option <?= $_POST['gender'] == 'other' || $arr[3] == 'other' ? 'selected="true"' : '' ?> value="other">Другое</option>
            </select>
            <div class="error">
                <?= $errors['gender'] ?? '' ?>
            </div>

            <label for="groupNum">Номер группы</label>
            <input type="text" name="groupNum" value="<?= htmlspecialchars($_GET['id'] == 'edit' ? $arr[4] : $_POST['groupNum'])  ?? '' ?>">
            <div class="error">
                <?= $errors['groupNum'] ?? '' ?>
            </div>

            <label for="mail">email</label>
            <input type="text" name="mail" value="<?= htmlspecialchars($_GET['id'] == 'edit' ? $arr[5] : $_POST['mail'])  ?? '' ?>">
            <div class="error">
                <?= $errors['mail'] ?? '' ?>
            </div>

            <label for="examPoints">Суммарное число баллов на ЕГЭ</label>
            <input type="text" name="examPoints" value="<?= htmlspecialchars($_GET['id'] == 'edit' ? $arr[6] : $_POST['examPoints'])  ?? '' ?>">
            <div class="error">
                <?= $errors['examPoints'] ?? '' ?>
            </div>

            <label for="dateOfBirth">Год рождения</label>

            <input type="text" id="datepicker" name="dateOfBirth" placeholder="дд-мм-гг" value="<?= htmlspecialchars($_GET['id'] == 'edit' ? $arr[7] : $_POST['dateOfBirth'])  ?? '' ?>">
            <div class="error">
                <?= $errors['dateOfBirth'] ?? '' ?>
            </div>

            <select name="fromWhere" id="fromWhere">
                <option value="choose">Статус проживания</option>
                <option <?= $_POST['fromWhere'] == 'visitor' || $arr[8] == 'visitor' ? 'selected="true"' : '' ?> value="visitor">Приезжий</option>
                <option <?= $_POST['fromWhere'] == 'local' || $arr[8] == 'local' ? 'selected="true"' : '' ?> value="local">Местный</option>
            </select>
            <div class="error">
                <?= $errors['fromWhere'] ?? '' ?>
            </div>

            <button type="submit">Отправить</button>
            <a href="index.php">Отмена</a>
        </form>

    </div>

</body>

</html>