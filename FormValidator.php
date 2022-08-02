<?php

class FormValidator
{

    private $data; // POST data
    private $errors = [];
    private static $fields = [
        'name', 'lastName', 'gender',
        'groupNum', 'mail', 'examPoints',
        'dateOfBirth', 'fromWhere'
    ];


    public function __construct($postData)
    {
        $this->data = $postData;
    }

    public function validateForm()
    {
        foreach (self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                try {
                    throw new Exception("Поле '$field' не присутствует среди данных");
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                return;
            }
        }

        $this->validateName();
        $this->validateLastName();
        $this->validateGender();
        $this->validateGroupNum();
        $this->validateMail();
        $this->validateExamPoints();
        $this->validateDateOfBirth();
        $this->validateResidenceStatus();

        return $this->errors;
    }

    private function validateName()
    {

        $val = trim($this->data['name']);

        if (empty($val)) {
            $this->addError('name', 'Поле ввода имени обязательно');
        } else {
            if (!preg_match('~^[а-яА-ЯёЁ]{2,20}$~u', $val)) {
                $this->addError('name', 'Имя должно состоять только из кириллицы
                                         и быть размером от 2 до 20 букв');
            }
        }
    }

    private function validateLastName()
    {
        $val = trim($this->data['lastName']);

        if (empty($val)) {
            $this->addError('lastName', 'Поле ввода фамилии обязательно');
        } else {
            if (!preg_match('~^[а-яА-Я]{2,25}$~u', $val)) {
                $this->addError('lastName', 'Фамилия должна состоять только из кириллицы
                                         и быть размером от 2 до 25 букв');
            }
        }
    }

    private function validateGender()
    {
        $val = $this->data['gender'];

        if ($val == 'choose') {
            $this->addError('gender', 'Выберите пол');
        }
    }

    private function validateGroupNum()
    {
        // допускаются числа, кириллица и латиница

        $val = mb_strtoupper($this->data['groupNum']);

        if (empty($val)) {
            $this->addError('groupNum', 'Поле ввода номера группы обязательно');
        } else {
            if (!preg_match('~^[А-ЯA-Z1234567890]{1,10}$~u', $val)) {
                $this->addError('groupNum', 'Номер группы должен состоять только из чисел и кириллицы/латиницы в верхнем регистре и быть продолжительностью от 1 до 10 символов');
            }
        }
    }

    private function validateMail()
    {
        $val = trim($this->data['mail']);

        if (empty($val)) {
            $this->addError('mail', 'Поле ввода электронной почты обязательно');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addError('mail', 'Введите корректный адрес 
                электронной почты');
            }
        }
    }

    private function validateExamPoints()
    {
        // можно только цифры, размером от 1 до 3, но не больше 400

        $val = trim($this->data['examPoints']);

        if (empty($val)) {
            $this->addError('examPoints', 'Поле ввода количества баллов обязательно');
        } else {
            if (!preg_match('~^[1234567890]{1,3}$~', $val)) {
                $this->addError('examPoints', 'Количество баллов должно состоять только из цифр и быть продолжительностью от 1 до 3 символов');
            } else {
                if ($val > 400) {
                    $this->addError('examPoints', 'Суммарное число баллов не может быть больше 400');
                }
            }
        }
    }

    private function validateDateOfBirth()
    {
        $val = $this->data['dateOfBirth'];

        if (empty($val)) {
            $this->addError('dateOfBirth', 'Поле ввода даты рождения обязательно');
        } else {
            
            $arr = explode('-', $val);
            // если введённый год больше чем (нынешний минус 16) и ниже 1900
            if($arr[0] < 1900 || $arr[0] > (date('Y') - 16)) {
                $this->addError('dateOfBirth', 'Дата рождения не может быть раньше 1900 года и позже ' . (date('Y') - 16) . ' года');
            }
        }
    }

    private function validateResidenceStatus()
    {
        $val = $this->data['fromWhere'];

        if ($val == 'choose') {
            $this->addError('fromWhere', 'Выберите статус проживания');
        }
    }

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }
}