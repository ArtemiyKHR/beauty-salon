<?php
// Настройки подключения к базе данных
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "form_submissions";

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Получение данных из формы
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$tel = isset($_POST['tel']) ? trim($_POST['tel']) : '';
$course = isset($_POST['course']) && is_array($_POST['course']) ? implode(', ', $_POST['course']) : '';
$agree = isset($_POST['agree']) ? 1 : 0;

// Проверка обязательных полей
if (empty($name) || empty($email) || empty($tel) || empty($course)) {
    die("Ошибка: Все обязательные поля должны быть заполнены.");
}

// Подготовка SQL-запроса
$stmt = $conn->prepare("INSERT INTO submissions (name, email, tel, course, agree) VALUES (?, ?, ?, ?, ?)");
if ($stmt === false) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

// Привязка параметров
$stmt->bind_param("ssssi", $name, $email, $tel, $course, $agree);

// Выполнение запроса
if ($stmt->execute()) {
    echo "Данные успешно сохранены.";
} else {
    echo "Ошибка при сохранении данных: " . $stmt->error;
}

// Закрытие соединения
$stmt->close();
$conn->close();
?>
