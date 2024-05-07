<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web6</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php  
    if (!empty($messages)) {
      print('<div class="messages">');
      foreach ($messages as $message) {
        print($message);
      }
      print('</div>');
    }
  ?>

  <div class="container">
    <h2>Форма</h2>
    <form action="" method="POST">
      Имя:<br><input type="text" name="names" <?php if ($errors['names']) {print 'class="group error"';} else print 'class="group"'; ?> value="<?php print $values['names']; ?>">
      <br>
      Телефон:<br><input type="tel" name="phone" <?php if ($errors['phone']) {print 'class="group error"';} else print 'class="group"'; ?> value="<?php print $values['phone']; ?>">
      <br>
      E-mail:
        <br><input type="text" name="email" <?php if ($errors['email']) {print 'class="group error';} else print 'class="group"'; ?> value="<?php print $values['email']; ?>"><br>
      Дата рождения:
      <div class="form-group">
        <input type="date" id="data" size="3" name="data" <?php if ($errors['data']) {print 'class="group error"';} else print 'class="group"';?> value="<?php print $values['data']; ?>">
      </div>
      <div <?php if ($errors['gender']) {print 'class="error"';} ?>>
        Пол:<br>
        <input class="radio" type="radio" name="gender" value="M" <?php if ($values['gender'] == 'M') {print 'checked';} ?>> Мужской
        <input class="radio" type="radio" name="gender" value="W" <?php if ($values['gender'] == 'W') {print 'checked';} ?>> Женский
      </div>
      Любимый язык программирования:<br>
      <select class="group" name="languages[]" size="10" multiple>
        <option value="Pascal" <?php if (in_array("Pascal", $values['language'])) {print 'selected';} ?>>Pascal</option>
        <option value="C" <?php if (in_array("C", $values['language'])) {print 'selected';} ?>>C</option>
        <option value="C_plus_plus" <?php if (in_array("C++", $values['language'])) {print 'selected';} ?>>C++</option>
        <option value="JavaScript" <?php if (in_array("JavaScript", $values['language'])) {print 'selected';} ?>>JavaScript</option>
        <option value="PHP" <?php if (in_array("PHP", $values['language'])) {print 'selected';} ?>>PHP</option>
        <option value="Python" <?php if (in_array("Python", $values['language'])) {print 'selected';} ?>>Python</option>
        <option value="Java" <?php if (in_array("Java", $values['language'])) {print 'selected';} ?>>Java</option>
        <option value="Haskel" <?php if (in_array("Haskel", $values['language'])) {print 'selected';} ?>>Haskel</option>
        <option value="Clojure" <?php if (in_array("Clojure", $values['language'])) {print 'selected';} ?>>Clojure</option>
        <option value="Prolog" <?php if (in_array("Prolog", $values['language'])) {print 'selected';} ?>>Prolog</option>
      </select>
      <br>
      Биография:<br><textarea class="group" name="biography" rows="3" cols="30"><?php print $values['biography']; ?></textarea>
      <div  <?php if ($errors['agree']) {print 'class="error"';} ?>>
        <input type="checkbox" name="agree" <?php if ($values['agree']) {print 'checked';} ?> value="1"> С контрактом ознакомлен(a) 
      </div>
      <input type="submit" id="send" value="ОТПРАВИТЬ">
    </form>
  </div>
  
  <div class="container">
    <?php
      if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']))
        print('<a href="login.php" class = "enter-exit" title = "Log out">Выйти</a>');
      else
        print('<a href="login.php" class = "enter-exit"  title = "Log in">Войти</a>');
    ?>
  </div>
</body>
</html>