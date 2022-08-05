<?php
require_once('func.php');

class DataBase
{

    private $user;
    private $password;
    private $pdo;
    private $data;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->pdo = new PDO("mysql:host=localhost;dbname=students", $this->user, $this->password);
    }

    public function InsertIntoDB($postData)
    {
        $this->data = $postData;
        try {

            $name = mb_strtoupper(mb_substr($this->data['name'], 0, 1)) . mb_strtolower(mb_substr($this->data['name'], 1));
            $lastName = mb_strtoupper(mb_substr($this->data['lastName'], 0, 1)) . mb_strtolower(mb_substr($this->data['lastName'], 1));
            $groupNum = trim(mb_strtoupper($this->data['groupNum']));
            $email = trim(mb_strtolower($this->data['mail']));
            $examPoints = trim($this->data['examPoints']);

            // Проверяется, существует ли уже такой студент в базе данных
            $sql = 'SELECT * from student WHERE name ="' . $name . '" AND last_name = "' . $lastName . '" AND gender = "' . $this->data['gender'] . '" AND group_num = "' . $groupNum . '" AND email = "' . $email . '" AND exam_points = "' . $examPoints . '" AND date_of_birth = "' . $this->data['dateOfBirth'] . '" AND residence_status = "' . $this->data['fromWhere'] . '" OR email = "' . $email . '"';

            $sth = $this->pdo->prepare($sql);
            $sth->execute();

            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

            // если не существует, добавляем
            if (empty($result)) {
                $password = randomPassword();

                $sql = 'INSERT INTO student (name, last_name, gender, group_num, email, exam_points, date_of_birth, residence_status, password) VALUES (:name, :lastName, :gender, :groupNum, :mail, :examPoints, :dateOfBirth, :fromWhere, :password)';

                $conn = $this->pdo->prepare($sql);

                $conn->bindParam(':name', $name);
                $conn->bindParam(':lastName', $lastName);
                $conn->bindParam(':gender', $this->data['gender']);
                $conn->bindParam(':groupNum', $groupNum);
                $conn->bindParam(':mail', $email);
                $conn->bindParam(':examPoints', $examPoints);
                $conn->bindParam(':dateOfBirth', $this->data['dateOfBirth']);
                $conn->bindParam(':fromWhere', $this->data['fromWhere']);
                $conn->bindParam(':password', $password);

                $result = $conn->execute();

                setcookie('password', $password);

                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Перестал использоваться, но пока оставил его
    public function DataOutput($column = 'exam_points', $order = 'DESC')
    {
        // по умолчанию по числу баллов вниз
        $sql = 'SELECT * from student ORDER BY ' . $column . ' ' . $order;

        if ($result = $this->pdo->query($sql)) {
            while ($row = $result->fetch()) {
?>

                <tr>
                    <td><?= $row[1] ?></td>
                    <td><?= $row[2] ?></td>
                    <td><?= $row[4] ?></td>
                    <td><?= $row[6] ?></dbhtd>
                </tr>

            <?php

            }
        } else {
            ?>

            <div class="error">
                Такой колонки не существует!
            </div>

            <?php
        }
    }

    public function EditInformation($password)
    {

        $sql = 'SELECT * from student WHERE password = "' . $password . '"';

        $result = $this->pdo->query($sql);

        $row = $result->fetch();

        return $row;
    }

    public function DeleteInfo($password)
    {

        $sql = 'DELETE FROM student WHERE password = "' . $password . '"';
        $result = $this->pdo->query($sql);
    }

    // взял код со стэковерфлоу и под себя подделал
    public function Pagination($column = 'exam_points', $order = 'DESC')
    {


        try {
            // Определяем количество студентов в таблице
            $total = $this->pdo->query('
                SELECT
                    COUNT(*)
                FROM
                student
                ORDER BY ' . $column . ' ' . $order . '
                ')->fetchColumn();

            // Студентов на страницу
            $limit = 50;

            // Сколько всего будет страниц
            $pages = ceil($total / $limit);

            // Текущая страница
            $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
                'options' => array(
                    'default'   => 1,
                    'min_range' => 1,
                ),
            )));

            // вычисление смещения для запроса
            $offset = ($page - 1)  * $limit;

            // Инфа, которая будет отображаться
            $start = $offset + 1;
            $end = min(($offset + $limit), $total);

            // Назад
            $prevlink = ($page > 1) ? '<a href="?page=1" title="Первая страница">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Предыдущая страница">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

            // Вперёд
            $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Следующая страница">&rsaquo;</a> <a href="?page=' . $pages . '" title="Последняя страница">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

            // Постраничный запрос
            $stmt = $this->pdo->prepare('
                SELECT
                    *
                FROM
                    student
                ORDER BY
                ' . $column . ' ' . $order . '
                LIMIT
                    :limit
                OFFSET
                    :offset
            ');

            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            // Проверка на наличие результатов
            if ($stmt->rowCount() > 0) {
                // Define how we want to fetch the results
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $iterator = new IteratorIterator($stmt);

                // Вывод
                foreach ($iterator as $row) {
            ?>

                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['last_name'] ?></td>
                        <td><?= $row['group_num'] ?></td>
                        <td><?= $row['exam_points'] ?></dbhtd>
                    </tr>
<?php

                }
                
                $arr = [$prevlink, $page, $pages, $start, $end, $total, $nextlink];

                return $arr;
            } else {
                echo '<p>Нет зарегистрированных студентов в базе данных.</p>';
            }
        } catch (Exception $e) {
            echo '<p>', $e->getMessage(), '</p>';
        }
    }
}
