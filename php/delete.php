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

// Проверяем, передан ли ID заявки
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Удаляем заявку
    $query = "DELETE FROM submissions WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_panel.php"); // Перенаправление обратно на страницу с заявками
        exit();
    } else {
        echo "Ошибка при удалении заявки: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "ID заявки не передан.";
}

$conn->close();
?>
