<?php
session_start();

// Включение пользовательских сообщений об ошибках
ini_set('display_errors', 0);
error_reporting(0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    echo "Произошла ошибка. Пожалуйста, попробуйте позже.";
}
set_error_handler('customErrorHandler');

// Подключение к базе данных с использованием PDO
$db = new PDO('mysql:host=localhost;dbname=u67445', 'u67445', '2552975', array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!isset($_GET['id'])) {
    echo "Ошибка: ID пользователя не указан.";
    exit();
}

// Использование подготовленных выражений для защиты от SQL-инъекций
$stmt = $db->prepare("SELECT * FROM application WHERE id = ?");
$stmt->execute([$_GET['id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    echo "Пользователь с указанным ID не найден.";
    exit();
}

// Генерация CSRF токена
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];

// Валидация CSRF токена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("CSRF token validation failed");
    }
    // Обновление данных пользователя
    if (isset($_POST['update'])) {
        $stmt = $db->prepare("UPDATE application SET names = ?, phones = ?, email = ?, dates = ?, gender = ?, biography = ? WHERE id = ?");
        $stmt->execute([
            $_POST['names'],
            $_POST['phones'],
            $_POST['email'],
            $_POST['dates'],
            $_POST['gender'],
            $_POST['biography'],
            $_GET['id']
        ]);

        header("Location: admin.php");
        exit();
    }

    // Удаление пользователя
    if (isset($_POST['delete'])) {
        $userId = $_GET['id'];

        $stmt = $db->prepare("DELETE FROM application_languages WHERE id_app = ?");
        $stmt->execute([$userId]);

        $stmt = $db->prepare("DELETE FROM application WHERE id = ?");
        $stmt->execute([$userId]);

        header("Location: admin.php");
        exit();
    }
}

// Функция экранирования выходных данных для защиты от XSS
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование пользователя</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Редактирование пользователя</h1>
    <form method="POST">
        <input type="hidden" name="token" value="<?php echo $token; ?>"> <!-- CSRF token -->
        <label for="names">Имя:</label><br>
        <input type="text" id="names" name="names" value="<?php echo escape($userData['names']); ?>"><br>
        <label for="phones">Телефон:</label><br>
        <input type="tel" id="phones" name="phones" value="<?php echo escape($userData['phones']); ?>"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo escape($userData['email']); ?>"><br>
        <label for="dates">Дата рождения:</label><br>
        <input type="date" id="dates" name="dates" value="<?php echo escape($userData['dates']); ?>"><br>
        <label for="gender">Пол:</label><br>
        <select id="gender" name="gender">
            <option value="M" <?php if ($userData['gender'] == 'M') echo 'selected'; ?>>Мужской</option>
            <option value="F" <?php if ($userData['gender'] == 'F') echo 'selected'; ?>>Женский</option>
        </select><br>
        <label for="biography">Биография:</label><br>
        <textarea id="biography" name="biography"><?php echo escape($userData['biography']); ?></textarea><br>
        <input type="submit" name="update" value="Сохранить изменения">
        <input type="submit" name="delete" value="Удалить пользователя" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
    </form>
</body>
</html>