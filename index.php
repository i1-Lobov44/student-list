<?php

// TODO:

//DONE!!! Пагинация (50 человек на страницу)
//DONE сортировка по любому полю кликом на заголовок колонки таблицы
//DONE по умолчанию по числу баллов вниз
// поле поиска, которое ищет сразу по всем строкам таблицы, регистронезависимо
// подсвечивать в таблице найденную часть слова

require_once('DataBase.php');

if ($_SERVER['HTTP_REFERER'] == 'http://localhost/students/form.php' && !empty($_COOKIE['student'])) {
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
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th><a href="index.php?sort=name">Имя </a> <a href="index.php?sort=name&order=Desc">&darr;</a> <a href="index.php?sort=name&order=Asc">&uarr;</a> </th>
                <th><a href="index.php?sort=last_name">Фамилия</a> <a href="index.php?sort=last_name&order=Desc">&darr;</a> <a href="index.php?sort=last_name&order=Asc">&uarr;</a> </th>
                <th><a href="index.php?sort=group_num">Номер группы</a> <a href="index.php?sort=group_num&order=Desc">&darr;</a> <a href="index.php?sort=group_num&order=Asc">&uarr;</a> </th>
                <th><a href="index.php?sort=exam_points">Число баллов</a> <a href="index.php?sort=exam_points&order=Desc">&darr;</a> <a href="index.php?sort=exam_points&order=Asc">&uarr;</a> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $db = new DataBase('root', '44');

            if (empty($_GET['sort'])) {
                $res = $db->Pagination();
            } else {
                if (!empty($_GET['order'])) {
                    $res = $db->Pagination($_GET['sort'], $_GET['order']);
                } else {
                    $res = $db->Pagination($_GET['sort']);
                }
            }
            ?>
        </tbody>

    </table>

    <a href="form.php"><button type="button" class="btn btn-sm btn-outline-primary" <?= !empty($_COOKIE['student']) ? "disabled" : "" ?>>Добавить свои данные</button></a>
    <a href="form.php?id=edit"><button type="button" class="btn btn-sm btn-outline-primary" <?= empty($_COOKIE['student']) ? "disabled" : ""  ?>>Редактировать свои данные</button></a>


    <?php

    if (!empty($res)) {

        echo '<div id="paging"><p>', $res[0], ' Страница ', $res[1], ' из ', $res[2], ' страниц, отображаются ', $res[3], '-', $res[4], ' из ', $res[5], ' студентов ', $res[6], ' </p></div>';
    }

    ?>

</body>

</html>