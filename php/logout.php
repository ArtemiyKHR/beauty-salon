<?php
session_start();
session_destroy(); // Удаляем данные сессии
header("Location: login.php"); // Перенаправляем на страницу входа
exit();
?>
