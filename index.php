<?php

require_once('FormValidator.php');

if (!empty($_POST)) {

    // validate entries
    $validation = new FormValidator($_POST);
    $errors = $validation->validateForm();

    // echo $_POST['dateOfBirth'];

    if (empty($errors)) {

        // фамилию имя сначала всё в нижний регистр, после первую букву в верхний
        // номер группы в верхний регистр
        //почту в нижний регистр

        // сохранить всё в бд
        // оповестить пользователя, что всё успешно добавлено
        // тут, наверное, нужно установить куки
        // redirect to main pageы
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

    <title>Document</title>
</head>

<body>

    <div class="container">

        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">

            <label for="name">Имя</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'])  ?? '' ?>">
            <div class="error">
                <?= $errors['name'] ?? '' ?>
            </div>

            <label for="lastName">Фамилия</label>
            <input type="text" name="lastName" value="<?= htmlspecialchars($_POST['lastName'])  ?? '' ?>">
            <div class="error">
                <?= $errors['lastName'] ?? '' ?>
            </div>

            <select name="gender">
                <option value="choose">Пол</option>
                <option <?= $_POST['gender'] == 'male' ? 'selected="true"' : '' ?> value="male">Мужчина</option>
                <option <?= $_POST['gender'] == 'female' ? 'selected="true"' : '' ?> value="female">Женщина</option>
                <option <?= $_POST['gender'] == 'other' ? 'selected="true"' : '' ?> value="other">Другое</option>
            </select>
            <div class="error">
                <?= $errors['gender'] ?? '' ?>
            </div>

            <label for="groupNum">Номер группы</label>
            <input type="text" name="groupNum" value="<?= htmlspecialchars($_POST['groupNum'])  ?? '' ?>">
            <div class="error">
                <?= $errors['groupNum'] ?? '' ?>
            </div>

            <label for="mail">email</label>
            <input type="text" name="mail" value="<?= htmlspecialchars($_POST['mail'])  ?? '' ?>">
            <div class="error">
                <?= $errors['mail'] ?? '' ?>
            </div>

            <label for="examPoints">Суммарное число баллов на ЕГЭ</label>
            <input type="text" name="examPoints" value="<?= htmlspecialchars($_POST['examPoints'])  ?? '' ?>">
            <div class="error">
                <?= $errors['examPoints'] ?? '' ?>
            </div>

            <label for="dateOfBirth">Год рождения</label>
            <input type="date" name="dateOfBirth" value="<?= htmlspecialchars($_POST['dateOfBirth'])  ?? '' ?>">
            <div class="error">
                <?= $errors['dateOfBirth'] ?? '' ?>
            </div>

            <select name="fromWhere" id="fromWhere">
                <option value="choose">Статус проживания</option>
                <option <?= $_POST['fromWhere'] == 'visitor' ? 'selected="true"' : '' ?> value="visitor">Приезжий</option>
                <option <?= $_POST['fromWhere'] == 'local' ? 'selected="true"' : '' ?> value="local">Местный</option>
            </select>
            <div class="error">
                <?= $errors['fromWhere'] ?? '' ?>
            </div>

            <button type="submit">Отправить</button>
        </form>

    </div>

</body>

</html>