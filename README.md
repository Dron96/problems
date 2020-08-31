# API для Problems

## Стек
1. PHP 7.4.3
2. Laravel 7.21
3. PostgreSQL 12.3

## Описание:

### Авторизация и регистрация:
|№  | Имя метода | Описание операции               | URL           | Метод запроса | Принимаемые параметры |
|---|------------|---------------------------------|---------------|:-------------:|-----------------------|
|1. | register   | Регистрация нового пользователя | /api/register | POST          | <ol><li>name - имя пользователя</li><li>password - пароль</li><li>password_confirmation - повторите пароль</li><li>email - электронная почта</li></ol>      |
|2. | login      | Авторизация пользователя        | /api/login    | POST          | <ol><li>name - имя пользователя</li><li>password - пароль</li></ol> |
|3. | logout     | Авторизация пользователя        | /api/logout    | POST          | Нет параметров        |

### Операции над проблемой:
|№  | Имя метода      | Описание операции             | URL               | Метод запроса | Принимаемые параметры |
|---|-----------------|-------------------------------|-------------------|:-------------:|-----------------------|
|1. | problem.index   | Получение списка всех проблем | /api/problem      | GET / HEAD    | Нет параметров        |
|2. | problem.store   | Создание проблемы             | /api/problem      | POST          | name - имя проблемы   |
|3. | problem.update  | Изменение имеющейся проблемы  | /api/problem/{id} | PUT           | name - имя проблемы   |
|4. | problem.show    | Получение проблемы            | /api/problem/{id} | GET / HEAD    | Нет параметров        |
|5. | problem.destroy | Удаление проблемы             | /api/problem/{id} | DELETE        | Нет параметров        |

## Ответы:

### Авторизация и регистрация:
#### 1. register
##### Удачная операция:
##### Код: 201
```json
{
    "message": "Вы успешно зарегистрированы"
}
```
##### Ошибки при проверки полей:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "password": [
            "В поле "Пароль" должно быть не менее 8 символов"
        ]
    }
}
```

#### 1. login
##### Удачная операция:
##### Код: 200
```json
{
    "user": {
        "id": 2,
        "name": "user2",
        "email": "data2@data.com",
        "email_verified_at": null,
        "created_at": "2020-08-29T09:51:12.000000Z",
        "updated_at": "2020-08-29T09:51:12.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjM2YzY0YTZiZTIzMzBhMDkyNmJjZTU..."
}
```
##### Ошибки при проверки полей:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "password": [
            "В поле \"Пароль\" должно быть не менее 8 символов"
        ]
    }
}
```
##### Неправильный логин или пароль:
##### Код: 401
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "Имя пользователя или пароль неправильные"
        ]
    }
}
```

#### 1. logout
##### Удачная операция:
##### Код: 200
```json
{
    "message": "Вы успешно вышли"
}
```


### Операции над проблемой:
#### 1. problem.index
##### Удачная операция:
##### Код: 200
```json
[
    {
        "id": 31,
        "name": "_________",
        "created_at": "2020-08-25T18:01:20.000000Z",
        "updated_at": "2020-08-25T18:01:20.000000Z"
    },
    {
        "id": 30,
        "name": "123456",
        "created_at": "2020-08-25T17:42:03.000000Z",
        "updated_at": "2020-08-25T17:42:03.000000Z"
    }
]
```

#### 2. problem.store
##### Удачная операция:
##### Код: 201
```json
{
    "name": "dddddd",
    "updated_at": "2020-08-26T03:33:13.000000Z",
    "created_at": "2020-08-26T03:33:13.000000Z",
    "id": 33
}
```
##### Ошибка:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "Название проблемы должно быть не менее 6 символов"
        ]
    }
}
```

#### 3. problem.update
##### Удачная операция:
##### Код: 200
```json
{
    "id": 15,
    "name": "Важная проблема",
    "created_at": "2020-08-24T12:52:31.000000Z",
    "updated_at": "2020-08-26T03:40:24.000000Z"
}
```
##### Ошибка:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "Название проблемы должно быть не менее 6 символов"
        ]
    }
}
```
##### Ошибка отсутствия данного объекта:
##### Код: 404
```json
{
    "message": "Объект не найден"
}
```


#### 4. problem.show
##### Удачная операция:
##### Код: 200
```json
{
    "id": 5,
    "name": "gh1ggg",
    "created_at": "2020-07-13T09:29:48.000000Z",
    "updated_at": "2020-08-25T07:27:49.000000Z"
}
```
##### Ошибка отсутствия данного объекта:
##### Код: 404
```json
{
    "message": "Объект не найден"
}
```

#### 5. problem.destroy
##### Удачная операция:
##### Код: 200
```json
{
    "message": "Проблема успешно удалена"
}
```
##### Ошибка отсутствия данного объекта:
##### Код: 404
```json
{
    "message": "Объект не найден"
}
```

