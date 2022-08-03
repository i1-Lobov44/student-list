<?php

// TODO:

// Пагинация (50 человек на страницу)
// сортировка по любому полю кликом на заголовок колонки таблицы
    // по умолчанию по числу баллов вниз
// поле поиска, которое ищет сразу по всем строкам таблицы, регистронезависимо
    // подсвечивать в таблице найденную часть слова

require_once('DataBase.php');

if ($_SERVER['HTTP_REFERER'] == 'http://localhost/students/form.php') {
?>
    <div class="success">
        Данные успешно сохранены!
    </div>
<?php
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="app.css" rel="stylesheet" />

    <title>Document</title>
</head>

<body>

    <a href="form.php?id=edit" class="btn btn-sm btn-outline-primary">Редактировать/Добавить свои данные</a>


    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Номер группы</th>
                <th>Число баллов</th>
            </tr>
        </thead>

        <?php

        $db = new DataBase('root', '44', $_POST);

        $db->DataOutput();

        ?>

    </table>

</body>

</html>