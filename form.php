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

    <!-- <link href="app.css" rel="stylesheet" /> -->

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://jqueryui.com/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker({
                changeYear: true,
                changeMonth: true,
                // dateFormat: "dd-mm-yy"

                maxDate: "-16Y",
                minDate: "-100Y",
                yearRange: "-100:-16"
            });

            $.datepicker.setDefaults($.datepicker.regional["ru"]);
            var dateFormat = $(".selector").datepicker("option", "dateFormat");

        });
    </script>

    <script>
        /* Russian (UTF-8) initialisation for the jQuery UI date picker plugin. */
        /* Written by Andrew Stromnov (stromnov@gmail.com). */
        (function(factory) {
            "use strict";

            if (typeof define === "function" && define.amd) {

                // AMD. Register as an anonymous module.
                define(["../widgets/datepicker"], factory);
            } else {

                // Browser globals
                factory(jQuery.datepicker);
            }
        })(function(datepicker) {
            "use strict";

            datepicker.regional.ru = {
                closeText: "Закрыть",
                prevText: "Пред",
                nextText: "След",
                currentText: "Сегодня",
                monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
                    "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
                ],
                monthNamesShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн",
                    "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"
                ],
                dayNames: ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"],
                dayNamesShort: ["вск", "пнд", "втр", "срд", "чтв", "птн", "сбт"],
                dayNamesMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                weekHeader: "Нед",
                dateFormat: "dd-mm-yy",
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ""
            };
            datepicker.setDefaults(datepicker.regional.ru);

            return datepicker.regional.ru;

        });
    </script>

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

            <!-- Script -->
            <script type='text/javascript'>
                $(function() {

                    // Initialize and change language to hindi
                    $('#datepicker').datepicker($.datepicker.regional["hi"]);

                });
            </script>

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