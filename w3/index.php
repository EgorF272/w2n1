<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['save'])) {
        print('Спасибо, форма сохранена.');
    }
    include('form.html');
    exit();
}

$errors = FALSE;

if (empty($_POST['name'])) {
    print('Укажите ФИО.<br/>');
    $errors = TRUE;
} else {
    $name = $_POST['name'];
    if (!preg_match('/^[a-zA-Zа-яА-Я\s]{1,150}$/', $name)) {
        print('Неверный формат ФИО. Допустимы только буквы и пробелы, не более 150 символов.<br/>');
        $errors = TRUE;
    }
}

if (empty($_POST['phone']) || !preg_match('/^\+?\d{1,15}$/', $_POST['phone'])) {
    print('Укажите корректный телефонный номер.<br/>');
    $errors = TRUE;
} else {
    $phone = $_POST['phone'];
}

if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    print('Укажите корректный адрес электронной почты.<br/>');
    $errors = TRUE;
} else {
    $email = $_POST['email'];
}

$date_input = $_POST['date'];
$date_format = 'Y-m-d';

$date_valid = DateTime::createFromFormat($date_format, $date_input);
if (!$date_valid) {
    print('Неверно указана дата рождения.<br/>');
    $errors = TRUE;
} else {
    $date = $date_input; 
}

if (empty($_POST['gender']) ) {
    print('Укажите пол.<br/>');
    $errors = TRUE;
}

switch($_POST['gender']) {
    case 'm': {
        $gender='m';
        break;
    }
    case 'f': {
        $gender='f';
        break;
    }
};

if (empty($_POST['Languages'])) {
    print('Укажите хотя бы один язык программирования.<br/>');
    $errors = TRUE;
}

$availableLanguages = array('Pascal', 'C', 'C_plus_plus', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskel', 'Clojure', 'Prolog', 'Scala');

foreach ($_POST['Languages'] as $language) {
    if (!in_array($language, $availableLanguages)) {
        print('Выбран недопустимый язык программирования: ' . htmlspecialchars($language) . '.<br/>');
        $errors = TRUE;
        break;
    }
}

if (empty($_POST['biography'])) {
    print('Напишите кратко биографию.<br/>');
    $errors = TRUE;
}
$biography = $_POST['biography'];
if (!preg_match('/^[a-zA-Zа-яА-Яе0-9,.!? ]+$/', $biography)) {
    print('Биография содержит недопустимые символы.<br/>');
    $errors = TRUE;
}

$agree = 'agree';
if (empty($_POST['agree'])) {
    print('Вы не согласились с условиями контракта!<br/>');
    $errors = TRUE;
}

if ($errors) {
    exit();
}

$user = 'u67445';
$pass = '2552975';
$db = new PDO('mysql:host=localhost;dbname=u67445', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($_POST['languages'] as $language) {
    $stmt = $db->prepare("SELECT id FROM languages WHERE id= :id");
    $stmt->bindParam(':id', $language);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
      print('Ошибка при добавлении языка.<br/>');
      exit();
    }
}

try {
    $stmt = $db->prepare("INSERT INTO application (names,phones,email,dates,gender,biography)" . "VALUES (:name,:phone,:email,:date,:gender,:biography)");
    $stmt->execute(array('name' => $name, 'phone' => $phone, 'email' => $email, 'date' => $date, 'gender' => $gender, 'biography' => $biography));
    $applicationId = $db->lastInsertId();
   
    foreach ($_POST['Languages'] as $language) {
        $stmt = $db->prepare("SELECT id FROM languages WHERE title = :title");
        $stmt->bindParam(':title', $language);
        $stmt->execute();
        $languageRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($languageRow) {
            $languageId = $languageRow['id'];
    
            $stmt = $db->prepare("INSERT INTO application_languages (id_lang, id_app) VALUES (:languageId, :applicationId)");
            $stmt->bindParam(':languageId', $languageId);
            $stmt->bindParam(':applicationId', $applicationId);
            $stmt->execute();
        } else {
            print('Ошибка: Не удалось найти ID для языка программирования: ' . $language . '<br/>');
            exit();
        }
    }
        
    print('Спасибо, форма сохранена.');
}

catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
}

header('Location: ?save=1');
?>