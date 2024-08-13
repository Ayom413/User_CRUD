# Система управления пользователями

Этот проект реализует систему управления пользователями с использованием Yii2. Система предоставляет как REST API для управления пользователями, так веб-интерфейс для удобства просмотра результатов работы.

## Веб-интерфейс

Веб-интерфейс позволяет вам:
- Просматривать список пользователей
- Создавать нового пользователя
- Обновлять информацию о существующих пользователях
- Удалять пользователей
- Выполнять вход в систему под учетной записью пользователя

Для доступа к веб-интерфейсу используйте следующие URL-адреса:
- **Список пользователей:** `http://crud.local/user`
- **Создание пользователя:** `http://crud.local/user/create`
- **Обновление пользователя:** `http://crud.local/user/update/<id>`
- **Удаление пользователя:** `http://crud.local/user/delete/<id>`
- **Вход в систему:** `http://crud.local/user/login`

## Начальная настройка проекта

### 1. Клонирование репозитория

Для начала клонируйте репозиторий на ваш локальный компьютер:

```bash
git clone https://github.com/ВашеИмяПользователя/User_CRUD.git
cd User_CRUD

### 2. Настройка базы данных
Откройте файл config/db.php и настройте параметры подключения к вашей базе данных:
```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=your_db_name',
    'username' => 'your_db_username',
    'password' => 'your_db_password',
    'charset' => 'utf8',
];
### 3. Проведение миграций
После подключения бд к проекту проведите миграции, для создания необходимых таблиц
```bash
php yii migrate


## REST API

REST API позволяет взаимодействовать с системой управления пользователями программным способом, используя JSON. API поддерживает следующие операции:

### 1. Получить список пользователей (Read)

- **URL:** `/api-user`
- **Метод:** `GET`
- **Описание:** Возвращает список всех пользователей.
- **Пример запроса (Bash):**
  ```bash
  curl -X GET http://your_domain/api-user

  ### 2. Получить информацию о пользователе по ID (Read by ID)

- **URL:** `/api-user/<id>`
- **Метод:** `GET`
- **Описание:** Возвращает информацию о конкретном пользователе по его ID.
- **Пример запроса (Bash):**
  curl -X GET http://your_domain/api-user/1

  ### 3. Создать нового пользователя (Create)

- **URL:** `/api-user`
- **Метод:** `POST`
- **Описание:** Создает нового пользователя.
- **Пример запроса (Bash):**
curl -X POST http://your_domain/api-user -H "Content-Type: application/json" -d '{
    "username": "newuser",
    "password": "newpassword"
}

  ### 4. Обновить информацию о пользователе по ID (Update)

- **URL:** `/api-user/<id>`
- **Метод:** `PUT`
- **Описание:** Обновляет информацию о пользователе по его ID.
- **Пример запроса (Bash):**
curl -X PUT http://your_domain/api-user/5 -H "Content-Type: application/json" -d '{
    "username": "updateduser",
    "password": "updatedpassword"
}'

### 5. Удалить пользователя по ID (Delete)

- **URL:** `/api-user/<id>`
- **Метод:** `DELETE`
- **Описание:** Удаляет пользователя по его ID.
- **Пример запроса (Bash):**
curl -X DELETE http://your_domain/api-user/5
