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

    // Получаем данные заявки
    $query = "SELECT * FROM submissions WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        die("Заявка не найдена.");
    }

    $stmt->close();
} else {
    die("ID заявки не передан.");
}

// Обновляем данные заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $course = $_POST['course'];
    $agree = isset($_POST['agree']) ? 1 : 0;

    $query = "UPDATE submissions SET name = ?, email = ?, tel = ?, course = ?, agree = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssii", $name, $email, $tel, $course, $agree, $id);

    if ($stmt->execute()) {
        header("Location: admin_panel.php"); // Перенаправление обратно на страницу с заявками
        exit();
    } else {
        echo "Ошибка при обновлении заявки: " . $conn->error;
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
    <title>Редактирование заявки</title>
    <link rel="stylesheet" href="../css/edit.css">
</head>
<body>
    <div class="container">
        <a href="./admin_panel.php" class="back-link">Назад</a>
        <h1>Редактировать заявку</h1>
        <form method="POST">
            <label>Имя:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            
            <label>Телефон:</label>
            <input type="tel" name="tel" value="<?php echo htmlspecialchars($row['tel']); ?>" required>
            
            <label>Курс:</label>
            <input type="text" name="course" value="<?php echo htmlspecialchars($row['course']); ?>" required>
            
            <label>
                <input type="checkbox" name="agree" <?php echo $row['agree'] ? 'checked' : ''; ?>> Согласие на обработку данных
            </label>
            
            <button type="submit" class="default-btn">Сохранить</button>
        </form>
    </div>
</body>
</html>
