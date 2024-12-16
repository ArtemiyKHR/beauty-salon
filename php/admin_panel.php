<?php
session_start();

// Проверяем авторизацию пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Перенаправление на страницу входа
    exit();
}

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "form_submissions";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Получаем данные из базы данных
$query = "SELECT * FROM submissions ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Ошибка при выполнении запроса: " . $conn->error);
}

$username = htmlspecialchars($_SESSION['username']); // Введенное имя пользователя
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="../css/admin.css" />
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            alert("Добро пожаловать, <?php echo $username; ?>!");
        });
    </script>
</head>
<body>
    <div class="container">
    <div class="header">
    <div class="nav-links">
        <a href="../index.html" class="back-link">Вернуться на сайт</a>
        <a href="logout.php" class="back-link">Выйти</a>
    </div>
</div>

        <h1>Заявки</h1>
        <table>
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Курс</th>
                    <th>Согласие на обработку данных</th>
                    <th>Дата заявки</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['tel']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo $row['agree'] ? "Да" : "Нет"; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <!-- Кнопки для редактирования и удаления -->
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Изменить</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Вы уверены, что хотите удалить заявку?');">Удалить</a>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($result->num_rows === 0) { ?>
                    <tr>
                        <td colspan="7" style="text-align:center;">Заявок нет</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Закрываем соединение
$conn->close();
?>
