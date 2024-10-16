document.addEventListener('DOMContentLoaded', function () {
    const output = document.getElementById("response-output");

    // Функция для отображения ответа сервера
    function displayResponse(response) {
        output.textContent = JSON.stringify(response, null, 4); // Отображаем ответ сервера в формате JSON
    }

    // Создание пользователя
    document.getElementById('create-user-form').addEventListener('submit', function (e) {
        e.preventDefault();  // Отменяем стандартное действие отправки формы

        const name = document.getElementById('create-name').value;
        const email = document.getElementById('create-email').value;
        const password = document.getElementById('create-password').value;

        fetch('api.php?action=create_user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email, password })
        })
            .then(response => response.json())
            .then(displayResponse)
            .catch(error => {
                console.error('Error:', error);
                output.textContent = 'Ошибка при запросе к серверу';
            });
    });

    // Обновление пользователя
    document.getElementById('update-user-form').addEventListener('submit', function (e) {
        e.preventDefault();  // Отменяем стандартное действие отправки формы

        const id = document.getElementById('update-id').value;
        const name = document.getElementById('update-name').value;
        const email = document.getElementById('update-email').value;

        fetch(`api.php?action=update_user&id=${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email })
        })
            .then(response => response.json())
            .then(displayResponse)
            .catch(error => {
                console.error('Error:', error);
                output.textContent = 'Ошибка при запросе к серверу';
            });
    });

    // Удаление пользователя
    document.getElementById('delete-user-form').addEventListener('submit', function (e) {
        e.preventDefault();  // Отменяем стандартное действие отправки формы

        const id = document.getElementById('delete-id').value;

        fetch(`api.php?action=delete_user&id=${id}`, {
            method: 'DELETE'
        })
            .then(response => response.json())
            .then(displayResponse)
            .catch(error => {
                console.error('Error:', error);
                output.textContent = 'Ошибка при запросе к серверу';
            });
    });

    // Авторизация пользователя
    document.getElementById('auth-user-form').addEventListener('submit', function (e) {
        e.preventDefault();  // Отменяем стандартное действие отправки формы

        const email = document.getElementById('auth-email').value;
        const password = document.getElementById('auth-password').value;

        fetch('api.php?action=auth_user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        })
            .then(response => response.json())
            .then(displayResponse)
            .catch(error => {
                console.error('Error:', error);
                output.textContent = 'Ошибка при запросе к серверу';
            });
    });

    // Получение информации о пользователе
    document.getElementById('get-user-form').addEventListener('submit', function (e) {
        e.preventDefault();  // Отменяем стандартное действие отправки формы

        const id = document.getElementById('get-id').value;

        fetch(`api.php?action=get_user&id=${id}`)
            .then(response => response.json())
            .then(displayResponse)
            .catch(error => {
                console.error('Error:', error);
                output.textContent = 'Ошибка при запросе к серверу';
            });
    });
});
