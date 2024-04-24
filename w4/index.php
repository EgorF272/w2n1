<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['names'] = !empty($_COOKIE['names_error']);
  $errors['phone'] = !empty($_COOKIE['phone_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['date'] = !empty($_COOKIE['date_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['languages'] = !empty($_COOKIE['languages_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);
  $errors['agree'] = !empty($_COOKIE['agree_error']);

  // Выдаем сообщения об ошибках.
  if ($errors['names']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('names_error', '', 100000);
    setcookie('names_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя.</div>';
  }

  if ($errors['phone']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('phone_error', '', 100000);
    setcookie('phone_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните телефон.</div>';
  }

  
  if ($errors['email']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('email_error', '', 100000);
    setcookie('email_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните почту.</div>';
  }


  if ($errors['date']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('date_error', '', 100000);
    setcookie('date_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните дату.</div>';
  }


  if ($errors['gender']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('gender_error', '', 100000);
    setcookie('gender_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните пол.</div>';
  }

  if ($errors['languages']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('languages_error', '', 100000);
    setcookie('languages_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните языки.</div>';
  }


  if ($errors['biography']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('biography_error', '', 100000);
    setcookie('biography_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните биографию.</div>';
  }


  if ($errors['agree']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('agree_error', '', 100000);
    setcookie('agree_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните соглашение.</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['names'] = empty($_COOKIE['names_value']) ? '' : $_COOKIE['names_value'];
  $values['phone'] = empty($_COOKIE['phone_value']) ? '' : $_COOKIE['phone_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['date'] = empty($_COOKIE['date_value']) ? '' : $_COOKIE['date_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['languages'] = empty($_COOKIE['languages_value']) ? '' : $_COOKIE['languages_value'];
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
  $values['agree'] = empty($_COOKIE['agree_value']) ? '' : $_COOKIE['agree_value'];
  $values['languages'] = empty($_COOKIE['languages_value']) ? '' : explode(',', $_COOKIE['languages_value']);
  $values['agree'] = empty($_COOKIE['agree_value']) ? '' : ($_COOKIE['agree_value'] === '1');

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['names'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('names_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
    // Сохраняем ранее введенное в форму значение на месяц.
setcookie('names_value', $_POST['names'], time() + 30 * 24 * 60 * 60);
  if (empty($_POST['phone'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('phone_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);


  if (empty($_POST['email'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);


  if (empty($_POST['date'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('date_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('date_value', $_POST['date'], time() + 30 * 24 * 60 * 60);

  if (empty($_POST['gender'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);

  if (empty($_POST['languages'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('languages_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  $language_string = implode(",", $_POST['languages']);
  setcookie('languages_value', $language_string, time() + 30 * 24 * 60 * 60);
  $languages_array = explode(",", $_COOKIE['languages_value']);

  if (empty($_POST['biography'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('biography_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);

  if (empty($_POST['agree'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('agree_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('agree_value', $_POST['agree'], time() + 30 * 24 * 60 * 60);

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    setcookie('names_error', '', 100000);
    setcookie('phone_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('date_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('languages_error', '', 100000);
    setcookie('biography_error', '', 100000);
    setcookie('agree_error', '', 100000);
  }
$names = isset($_POST['names']) ? $_POST['names'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$biography = isset($_POST['biography']) ? $_POST['biography'] : '';
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
        $stmt = $db->prepare("INSERT INTO application (names,phones,email,dates,gender,biography)" . "VALUES (:names,:phone,:email,:date,:gender,:biography)");
        $stmt->execute(array('names' => $names, 'phone' => $phone, 'email' => $email, 'date' => $date, 'gender' => $gender, 'biography' => $biography));
        $applicationId = $db->lastInsertId();
      
        foreach ($_POST['languages'] as $language) {
            $stmt = $db->prepare("SELECT id FROM languages WHERE id = :id");
            $stmt->bindParam(':id', $language);
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

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}