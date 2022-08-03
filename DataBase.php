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


    public function DataOutput()
    {

        $sql = 'SELECT * from student';

        if ($result = $this->pdo->query($sql)) {
            while ($row = $result->fetch()) {
?>

                <tr>
                    <td><?= $row[1] ?></td>
                    <td><?= $row[2] ?></td>
                    <td><?= $row[4] ?></td>
                    <td><?= $row[6] ?></td>

                </tr>

<?php

            }
        }
    }

    public function EditInformation($password)
    {

        $sql = 'SELECT * from student WHERE password = "' . $password . '"';

        $result = $this->pdo->query($sql);

        $row = $result->fetch();
        
        return $row;
    }

    public function DeleteInfo($password) {

        $sql = 'DELETE FROM student WHERE password = "' . $password . '"';
        $result = $this->pdo->query($sql);
    }
}
