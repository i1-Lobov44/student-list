<?php

// методы для полей:
// имя, фамилия, пол, номер группы, mail, баллы, др, статус проживания

// конструктор принимающий POST данные

//DONE -- Регулярка для почты (нельзя ;,+()*&?!<>/|\^:%$#№""'{}~`а-ЯёЁ)
// Регулярка на проверку недопустимых символов (цифры, знаки препинания и прочее) для имени, фамилии
// Регулярка для баллов (нельзя .;,+()*&?!<>/|\^:%$#№""'{} ёЁа-яА-Яa-zA-Z~`)
// Регулярка для номера группы (нельзя .;,+()*&?!<>/|\^:%$#№""'{} a-zA-Z~`)
// dateOfBirth год не должен быть меньше 1900 и больше (нынешний год - 16)
// gender fromWhere уже проверены на выбор

// Имя должно начинаться с заглавной буквы и все остальные должны быть маленькими

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
        $this->validateNameExamPoints();
        $this->validateDateOfBirth();
        $this->validateResidenceStatus();

        return $this->errors;
    }

    private function validateName()
    {

        $val = trim($this->data['name']);

        if (empty($val)) {
            $this->addError('name', 'Поле ввода имени не 
                                        может быть пустым');
        } else {
            if (!preg_match('~^[а-яА-Я]{2,20}$~', $val)) {
                $this->addError('name', 'Имя должно состоять только из кириллицы
                                         и быть размером от 2 до 20 букв');
            }
        }
    }

    private function validateLastName()
    {
    }

    private function validateGender()
    {
    }

    private function validateGroupNum()
    {
    }

    private function validateMail()
    {
        $val = trim($this->data['mail']);

        if (empty($val)) {
            $this->addError('mail', 'Поле ввода электронной почты не 
                                        может быть пустым');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addError('mail', 'Введите корректный адрес 
                                            электронной почты');
            }
        }
    }

    private function validateNameExamPoints()
    {
    }

    private function validateDateOfBirth()
    {
    }

    private function validateResidenceStatus()
    {
    }

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }
}
