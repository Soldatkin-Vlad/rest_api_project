<?php
// Устанавливаем, что ответ будет в формате JSON
header("Content-Type: application/json");

// Подключение к базе данных MySQL
$host = 'localhost';        // Адрес сервера базы данных
$db_name = 'users_db';       // Имя базы данных
$username = 'root';          // Имя пользователя базы данных
$password = '';              // Пароль к базе данных
$conn = new mysqli($host, $username, $password, $db_name);

// Проверка подключения
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Обработка методов API
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'create_user') {
    // Логика для создания пользователя
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Пользователь создан']);
    } else {
        echo json_encode(['error' => 'Не удалось создать пользователя']);
    }

    $stmt->close();
}

// Обновление пользователя
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['action']) && $_GET['action'] == 'update_user' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $email = $data['email'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Пользователь успешно обновлен']);
    } else {
        echo json_encode(['error' => 'Не удалось обновить пользователя.']);
    }

    $stmt->close();
}

// Удаление пользователя
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['action']) && $_GET['action'] == 'delete_user' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Пользователь успешно удален']);
    } else {
        echo json_encode(['error' => 'Не удалось удалить пользователя.']);
    }

    $stmt->close();
}

// Авторизация пользователя
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'auth_user') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        echo json_encode(['message' => 'Авторизация прошла успешно']);
    } else {
        echo json_encode(['error' => 'Неверный Email или пароль']);
    }

    $stmt->close();
}

// Получение информации о пользователе
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'get_user' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'Пользователь не найден']);
    }

    $stmt->close();
}

// Закрываем соединение с базой данных
$conn->close();
