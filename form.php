<?php
require_once('FormValidator.php');

if (!empty($_POST)) {

    // валидация входных данных
    $validation = new FormValidator($_POST);
    $errors = $validation->validateForm();

    if (empty($errors)) {

        // фамилию имя сначала всё в нижний регистр, после первую букву в верхний
        // номер группы в верхний регистр
        //почту в нижний регистр

        //DONE - сохранить всё в бд
        //DONE - оповестить пользователя, что всё успешно добавлено
        //DONE - тут, наверное, нужно установить куки
        // redirect to main pageы


        //Сохранение в бд
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=students", "root", "44");


            $name = mb_strtoupper(mb_substr($_POST['name'], 0, 1)) . mb_strtolower(mb_substr($_POST['name'], 1));

            $lastName = mb_strtoupper(mb_substr($_POST['lastName'], 0, 1)) . mb_strtolower(mb_substr($_POST['lastName'], 1));

            // верхний регистр
            $groupNum = trim(mb_strtoupper($_POST['groupNum']));

            // нижний регистр
            $email = trim(mb_strtolower($_POST['mail']));

            $examPoints = trim($_POST['examPoints']);

            // Проверяется, существует ли уже такой студент в базе данных
            $sql = 'SELECT * from student WHERE name ="' . $name . '" AND last_name = "' . $lastName . '" AND gender = "' . $_POST['gender'] . '" AND group_num = "' . $groupNum . '" AND email = "' . $email . '" AND exam_points = "' . $examPoints . '" AND date_of_birth = "' . $_POST['dateOfBirth'] . '" AND residence_status = "' . $_POST['fromWhere'] . '" OR email = "' . $email . '"';

            $sth = $pdo->prepare($sql);
            $sth->execute();

            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

            // если не существует, добавляем
            if (empty($result)) {

                $sql = 'INSERT INTO student (name, last_name, gender, group_num, email, exam_points, date_of_birth, residence_status) VALUES (:name, :lastName, :gender, :groupNum, :mail, :examPoints, :dateOfBirth, :fromWhere)';

                $conn = $pdo->prepare($sql);

                $conn->bindParam(':name', $name);
                $conn->bindParam(':lastName', $lastName);
                $conn->bindParam(':gender', $_POST['gender']);
                $conn->bindParam(':groupNum', $groupNum);
                $conn->bindParam(':mail', $email);
                $conn->bindParam(':examPoints', $examPoints);
                $conn->bindParam(':dateOfBirth', $_POST['dateOfBirth']);
                $conn->bindParam(':fromWhere', $_POST['fromWhere']);

                $result = $conn->execute();

                //cookie
                $value = $_POST['name'] . ' ' . $_POST['lastName'];

                $time = strtotime('+10years');

                setcookie('student', $value, $time);
?>

                <div class="success">
                    Студент успешно добавлен в базу данных!
                </div>

            <?php


                //redirect to main page

            } else {
            ?>

                <div class="error">
                    Такие пользователь или почта уже зарегистрированы
                </div>

<?php
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
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
                <option <?= $_POST['gender'] == 'male' ? 'selected="true"' : '' ?> value="male">Мужской</option>
                <option <?= $_POST['gender'] == 'female' ? 'selected="true"' : '' ?> value="female">Женский</option>
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