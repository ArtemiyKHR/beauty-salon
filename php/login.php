<?php
session_start();

// Подключение к БД
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "form_submissions";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Проверяем форму при отправке данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_input = trim($_POST['username']);
    $password_input = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Проверяем пароль и авторизуем пользователя
    if ($user && password_verify($password_input, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Перенаправляем на страницу с заявками
        header("Location: admin_panel.php");
        exit();
    } else {
        $error = "Неверное имя пользователя или пароль.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Meddon&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Proza+Libre:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,800&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap"
      rel="stylesheet"
    />
</head>
<body>
    <div class="login-container">
        <h1>Вход</h1>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" placeholder="Введите имя" required>
            
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" placeholder="Введите пароль" required>
            
            <button type="submit">Войти</button>
        </form>
        <p><a href="../index.html">Вернуться на сайт</a></p>
    </div>
</body>
</html>
