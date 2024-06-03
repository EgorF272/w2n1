<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вход в систему</title>
</head>

<body>
<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
if (!empty($_SESSION['login'])) {
  session_destroy();
  header('Location: ./');
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if (!empty($_GET['nologin']))
    print("<div>Пользователя с таким логином не существует</div>");
  if (!empty($_GET['wrongpass']))
    print("<div>Неверный пароль!</div>");

?>
  <form action="" method="post">
    <input name="login" placeholder="Введи логин"/>
    <input name="pass" placeholder="Введи пароль"/>
    <input type="submit" id="login" value="Войти" />
  </form>

  <?php
}
else {
  $db = new PDO('mysql:host=localhost;dbname=u67445', 'u67445', '2552975', array(PDO::ATTR_PERSISTENT => true));
  $stmt = $db->prepare("SELECT id, pass FROM login_pass WHERE login = ?");
  $stmt -> execute([$_POST['login']]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row) {
    header('Location: ?nologin=1');
    exit();
  }
  if($row["pass"] != md5($_POST['pass'])) {
    header('Location: ?wrongpass=1');
    exit();
  }
  $_SESSION['login'] = $_POST['login'];
  $_SESSION['uid'] = $row["id"];
  header('Location: ./');
}

?>

</body>

</html>