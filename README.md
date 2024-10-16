# REST API для работы с пользователями

## Методы API

### Создание пользователя (POST /users?action=create_user)
- Параметры: `name`, `email`, `password`
- Ответ: `User created successfully` или ошибка

### Обновление информации пользователя (PUT /users/{id}?action=update_user)
- Параметры: `name`, `email`
- Ответ: `User updated successfully` или ошибка

### Удаление пользователя (DELETE /users/{id}?action=delete_user)
- Ответ: `User deleted successfully` или ошибка

### Авторизация пользователя (POST /auth?action=auth_user)
- Параметры: `email`, `password`
- Ответ: `Authentication successful` или ошибка

### Получение информации о пользователе (GET /users/{id}?action=get_user)
- Ответ: JSON с информацией о пользователе или ошибка
