# Список студентов

Пока лень, но придётся, наверное, переехать на другие платформы

* Что уже сделано: 

    + HTML-форма для регистрации пользователя
    + Класс валидации
    + Валидация введённых имени и электронной почты пользователя

* TODO на первое время: 

    * HTML:
    
        + Добавить главную страницу - Список зарегистрированных абитуриентов

    * CSS:

        + (Опционально) Подключить Bootstrap на первое время

    * PHP:

        + Валидация всех полей

        + Запоминание пользователя с помощью куки (на 10 лет)

        + Подключение к бд

            + Вывод данных из бд в таблицу на главной странице

            + Вывод по 50 человек на страницу

            + Сортировка по любому полю кликом на заголовок колонки таблицы (по умолчанию по числу баллов вниз)

            + Добавить поле поиска, которое ищет сразу по всем строкам таблицы, регистронезависимо

            + При поиске подсвечивать в таблице найденную часть слова

        + Добавить возможность редактировать информацию о себе (чужие данные редактировать запрещено)


    * MySQL

        + Создать таблицу в phpmyadmin

        + Написать запросы, которые после будут использоваться в PDO