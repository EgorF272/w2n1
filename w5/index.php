<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
        $messages[] = 'Спасибо, результаты сохранены. ';
        if (!empty($_COOKIE['pass'])) {
            $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['pass']));
        }
    }
    $errors = array();
    $errors['names'] = !empty($_COOKIE['name_error']);
    $errors['phone'] = !empty($_COOKIE['phone_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['data'] = !empty($_COOKIE['data_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['agree'] = !empty($_COOKIE['agree_error']);

    if ($errors['names']) {
        setcookie('names_error', '', 100000);
        $messages[] = '<div>Заполните имя.</div>';
    }
    if ($errors['phone']) {
        setcookie('phone_error', '', 100000);
        $messages[] = '<div>Некорректный телефон.</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div>Некорректный email.</div>';
    }
    if ($errors['data']) {
        setcookie('data_error', '', 100000);
        $messages[] = '<div>Выберите год рождения.</div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error', '', 100000);
        $messages[] = '<div>Выберите пол.</div>';
    }
    if ($errors['agree']) {
        setcookie('agree_error', '', 100000);
        $messages[] = '<div>Поставьте галочку.</div>';
    }
    $values = array();
$values['names'] = isset($_COOKIE['names_value']) ? strip_tags($_COOKIE['names_value']) : '';
$values['phone'] = isset($_COOKIE['phone_value']) ? strip_tags($_COOKIE['phone_value']) : '';
$values['email'] = isset($_COOKIE['email_value']) ? strip_tags($_COOKIE['email_value']) : '';
$values['data'] = isset($_COOKIE['data_value']) ? $_COOKIE['data_value'] : '';
$values['gender'] = isset($_COOKIE['gender_value']) ? $_COOKIE['gender_value'] : '';
$values['biography'] = isset($_COOKIE['biography_value']) ? strip_tags($_COOKIE['biography_value']) : '';
$values['agree'] = isset($_COOKIE['agree_value']) ? $_COOKIE['agree_value'] : ''; 
if (empty($_COOKIE['language_value'])) {
        $values['language'] = array();
    } else {
        $values['language'] = json_decode($_COOKIE['language_value'], true);  
    }
    $language = isset($language) ? $language : array();
    if (!empty($_SESSION['login'])) {
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
}
    session_start();
    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
        $db = new PDO('mysql:host=localhost;dbname=u67501', 'u67445', '2552975', array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("SELECT * FROM application WHERE id = ?");
        $stmt->execute([$_SESSION['uid']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $values['names'] = strip_tags($row['names']);
        $values['phone'] = isset($_COOKIE['phone']) ? strip_tags($_COOKIE['phone']) : '';
        $values['email'] = strip_tags($row['email']);
        $values['data'] = isset($_COOKIE['data']) ? $_COOKIE['data'] : '';
        $values['gender'] = $row['gender'];
        $values['biography'] = strip_tags($row['biography']);
        $values['agree'] = true; 
        $stmt = $db->prepare("SELECT * FROM languages WHERE id = ?");
        $stmt->execute([$_SESSION['uid']]);
        $ability = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $values['language'] = isset($_COOKIE['language_value']) ? json_decode($_COOKIE['language_value'], true) : array();
        }
        $values['language'] = $language;
        printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
    }
    include('form.php');
}
else {
    $errors = FALSE;
    if (empty(htmlentities($_POST['names']))) {
        setcookie('names_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('names_value', $_POST['names'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', $_POST['phone'])) {
        setcookie('phone_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('email_value', $_POST['email'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (empty($_POST['data'])) {
        setcookie('data_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('data_value', $_POST['data'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (empty($_POST['gender'])) {
        setcookie('gender_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('gender_value', $_POST['gender'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (empty($_POST['agree'])) {
        setcookie('agree_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('agree_value', $_POST['agree'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (isset($_POST['language'])) {
        $language = $_POST['language'];
    } else {
        $language = array(); 
    }
    if (!empty($_POST['biography'])) {
        setcookie ('biography_value', $_POST['biography'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (!empty($_POST['language'])) {
        $json = json_encode($_POST['language']);
        setcookie ('language_value', $json, time() + 12 * 30 * 24 * 60 * 60);
    }

    if ($errors) {
        header('Location: index.php');
        exit();
    } else {
        setcookie('names_error', '', 100000);
        setcookie('phone_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('data_error', '', 100000);
        setcookie('gender_error', '', 100000);
        setcookie('agree_error', '', 100000);
    }
    if (!empty($_COOKIE[session_name()]) &&
        session_start() && !empty($_SESSION['login'])) {
        $db = new PDO('mysql:host=localhost;dbname=u67445', 'u67445', '2552975', array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("UPDATE application SET names = ?, phones = ?, email = ?, data = ?, gender = ?, biography = ? WHERE id = ?");
        $stmt->execute([$_POST['names'], $_POST['phone'], $_POST['email'], $_POST['data'], $_POST['gender'], $_POST['biography'], $_SESSION['uid']]);
        $stmt = $db->prepare("DELETE FROM languages WHERE id = ?");
        $stmt->execute([$_SESSION['uid']]);
        $ability = $_POST['language'];
        foreach ($language as $item) {
            $stmt = $db->prepare("INSERT INTO application_languages SET id = ?, name_of_language = ?");
            $stmt->execute([$_SESSION['uid'], $item]);
        }
    } else {
        $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max=rand(8,16);
        $size=StrLen($chars)-1;
        $pass=null;
        while($max--)
            $pass.=$chars[rand(0,$size)];
        $login = $chars[rand(0,25)] . strval(time());
        setcookie('login', $login);
        setcookie('pass', $pass);
        $db = new PDO('mysql:host=localhost;dbname=u67445', 'u67445', '2552975', array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("INSERT INTO application SET names = ?, phones = ?, email = ?, dates = ?, gender = ?, biography = ?");
        $stmt->execute([$_POST['names'], $_POST['phone'], $_POST['email'], $_POST['data'], $_POST['gender'], $_POST['biography']]);
        $res = $db->query("SELECT max(id) FROM application");
        $row = $res->fetch();
        $count = (int) $row[0];
        $ability = $_POST['language'];
        foreach ($language as $item) {
            $stmt = $db->prepare("INSERT INTO application_languages SET id = ?, name_of_language = ?");
            $stmt->execute([$count, $item]);
        }
        $stmt = $db->prepare("INSERT INTO login_pass SET id = ?, login = ?, pass = ?");
        $stmt->execute([$count, $login, md5($pass)]);
    }
    setcookie('save', '1');
    header('Location: ./');
}
?>