# API для Problems

## Стек
1. PHP 7.4.3
2. Laravel 7.21
3. PostgreSQL 12.3

## Описание:

### Операции над задачей:
|№   | Имя метода       | Описание операции                              | URL                            | Метод запроса | Принимаемые параметры   |
|----|------------------|------------------------------------------------|--------------------------------|:-------------:|-------------------------|
|1. | task.index        | Получение списка всех задач для решения        | /api/solution/{solution}/task  | GET / HEAD    | Нет параметров          |
|2. | task.store        | Создание задачи для решения                    | /api/solution/{solution}/task  | POST          | <ol><li>description - описание задачи</li><li>executor_id - id ответственного</li><li>status - статус задачи ('К исполнению', 'В процессе', 'Выполнено')</li><li>deadline - срок исполнения (Формат: ДД-ММ-ГГГГ)</li></ol>|
|3. | task.show         | Получение задачи                               | /api/task/{task}               | GET / HEAD    | Нет параметров          |
|4. | task.update       | Изменение описания задачи                      | /api/task/{task}               | PUT           | description - описание задачия |
|5. | task.destroy      | Удаление задачи                                | /api/task/{task}               | DELETE        | Нет параметров          |
|6. | task.changeStatus | Изменение статуса задачи                       | /api/task/{task}/change-status | PUT           | status - статус задачи ('К исполнению', 'В процессе', 'Выполнено') |
|7. | task.setDeadline  | Установка срока исполнения задачи              | /api/task/{task}/set-deadline  | PUT           | deadline - дата в формате ГГГГ-ММ-ДД |
|8. | task.setExecutor  | Назначить исполнителя/ответственного за задачу | /api/task/{task}/set-executor  | PUT           | executor_id - id пользователя |

### Ответы:
#### 1. task.index
##### Удачная операция:
##### Код: 200
```json
[
    {
        "id": 3,
        "description": "gfhfhfh2232",
        "creator_id": 16,
        "solution_id": 1,
        "status": "К исполнению",
        "deadline": null,
        "executor_id": null,
        "created_at": "2020-09-13T13:16:59.000000Z",
        "updated_at": "2020-09-14T07:51:46.000000Z",
        "deleted_at": null
    },
    {
        "id": 4,
        "description": "gfhfhfh2232",
        "creator_id": 16,
        "solution_id": 1,
        "status": "К исполнению",
        "deadline": "2020-12-20",
        "executor_id": null,
        "created_at": "2020-09-13T13:18:22.000000Z",
        "updated_at": "2020-09-14T07:52:21.000000Z",
        "deleted_at": null
    }
]
```
##### Ошибка отсутствия данного решения:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```

#### 2. task.store
##### Удачная операция:
##### Код: 201
```json
{
    "description": "gfhfhfh2232",
    "deadline": "20.12.2020",
    "creator_id": 16,
    "solution_id": 2,
    "updated_at": "2020-09-14T16:05:25.000000Z",
    "created_at": "2020-09-14T16:05:25.000000Z",
    "id": 16
}
```
##### Ошибка отсутствия данного решения:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```
##### Ошибки валидации:
##### Код: 422
```json
{
     "message": "The given data was invalid.",
     "errors": {
         "deadline": [
             "Формат срока исполнения не верен",
             "Срок исполнения не может быть раньше текущей даты"
         ]
     }
}
```

#### 3. task.show
##### Удачная операция:
##### Код: 200
```json
{
    "id": 7,
    "description": "Пример1",
    "creator_id": 16,
    "solution_id": 2,
    "status": "Выполнено",
    "deadline": "2020-09-14",
    "executor_id": 15,
    "created_at": "2020-09-13T13:24:41.000000Z",
    "updated_at": "2020-09-14T15:38:05.000000Z",
    "deleted_at": null
}
```
##### Ошибка отсутствия данной задачи:
##### Код: 404
```json
{
    "message": "Такой задачи не существует"
}
```

#### 4. task.update
##### Удачная операция:
##### Код: 200
```json
{
    "id": 7,
    "description": "Пример1",
    "creator_id": 16,
    "solution_id": 2,
    "status": "Выполнено",
    "deadline": "2020-09-14",
    "executor_id": 15,
    "created_at": "2020-09-13T13:24:41.000000Z",
    "updated_at": "2020-09-14T15:38:05.000000Z",
    "deleted_at": null
}
```
##### Ошибки валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "description": [
            "Для описания решения доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9."
        ]
    }
}
```
##### Ошибка отсутствия данной задачи:
##### Код: 404
```json
{
    "message": "Такой задачи не существует"
}
```

#### 5. task.destroy
##### Удачная операция:
##### Код: 200
```json
{
    "message": "Задача успешно удалена"
}
```
##### Ошибка отсутствия данной задачи:
##### Код: 404
```json
{
    "message": "Такой задачи не существует"
}
```

#### 6. task.changeStatus
##### Удачная операция:
##### Код: 200
```json
{
    "id": 7,
    "description": "Пример1",
    "creator_id": 16,
    "solution_id": 2,
    "status": "Выполнено",
    "deadline": "2020-09-14",
    "executor_id": 15,
    "created_at": "2020-09-13T13:24:41.000000Z",
    "updated_at": "2020-09-14T15:38:05.000000Z",
    "deleted_at": null
}
```

##### Ошибка валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "status": [
            "Неверный статус"
        ]
    }
}
```
##### Ошибка отсутствия данной задачи:
##### Код: 404
```json
{
    "message": "Такой задачи не существует"
}
```

#### 7. task.setDeadline
##### Удачная операция:
##### Код: 200
```json
{
    "id": 7,
    "description": "Пример1",
    "creator_id": 16,
    "solution_id": 2,
    "status": "Выполнено",
    "deadline": "2020-9-16",
    "executor_id": 15,
    "created_at": "2020-09-13T13:24:41.000000Z",
    "updated_at": "2020-09-14T16:11:50.000000Z",
    "deleted_at": null
}
```
##### Ошибка валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "deadline": [
            "Срок исполнения не может быть раньше текущей даты"
        ]
    }
}
```

#### 8. task.setExecutor
##### Удачная операция:
##### Код: 200
```json
{
    "id": 7,
    "description": "Пример1",
    "creator_id": 16,
    "solution_id": 2,
    "status": "Выполнено",
    "deadline": "2020-09-16",
    "executor_id": "1",
    "created_at": "2020-09-13T13:24:41.000000Z",
    "updated_at": "2020-09-14T16:13:03.000000Z",
    "deleted_at": null
}
```

##### Ошибка валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "executor_id": [
            "Такого ответственного не существует"
        ]
    }
}
```

### Операции над решением:
|№   | Имя метода            | Описание операции                                   | URL                                     | Метод запроса | Принимаемые параметры   |
|----|-----------------------|-----------------------------------------------------|-----------------------------------------|:-------------:|-------------------------|
|1.  | solution.index        | Получение списка всех решений для проблемы          | /api/problem/{problem}/solution         | GET / HEAD    | Нет параметров          |
|2.  | solution.store        | Создание решения для проблемы                       | /api/problem/{problem}/solution         | POST          | name - описание решения |
|3.  | solution.in-work      | Получение списка всех решений в работе для проблемы | /api/problem/{problem}/solution-in-work | GET / HEAD    | Нет параметров          |
|4.  | solution.show         | Получение решения                                   | /api/solution/{solution}                | GET / HEAD    | Нет параметров          |
|5.  | solution.changeInWork | Смена статуса "в работе" для решения                | /solution/{solution}/change-in-work     | PUT           | in_work - статус в работе (да - любая не пустая строка, 1; нет - пустая строка, 0) |
|6.  | solution.update       | Изменение описания решения                          | /api/solution/{solution}                | PUT           | name - описание решения |
|7.  | solution.destroy      | Удаление решения                                    | /api/solution/{solution}                | DELETE        | Нет параметров          |
|8.  | solution.changeStatus | Изменение статуса решения                           | /api/solution/{solution}/change-status  | PUT           | status - статус решения в работе (В процессе, Выполнено, "") |
|9.  | solution.setDeadline  | Установка срока исполнения решения                  | /api/solution/{solution}/set-deadline   | PUT           | deadline - дата в формате ГГГГ-ММ-ДД |
|10. | solution.setExecutor  | Назначить исполнителя/ответственного за решение     | /api/solution/{solution}/set-executor   | PUT           | executor_id - id пользователя |

### Ответы:
#### 1. solution.index
##### Удачная операция:
##### Код: 200
```json
[
    {
        "id": 63,
        "name": "Mock.",
        "user_id": 10,
        "problem_id": 1,
        "in_work": true,
        "status": null,
        "created_at": "2020-06-25T10:10:33.000000Z",
        "updated_at": "2020-06-25T10:10:33.000000Z",
        "deleted_at": null
    },
    {
        "id": 103,
        "name": "fyghgffjkf",
        "user_id": 17,
        "problem_id": 1,
        "in_work": false,
        "status": null,
        "created_at": "2020-09-04T15:19:39.000000Z",
        "updated_at": "2020-09-04T15:19:39.000000Z",
        "deleted_at": null
    },
    ...
]
```
##### Ошибка отсутствия данной проблемы:
##### Код: 404
```json
{
    "message": "Такой проблемы не существует"
}
```

#### 2. solution.store
##### Удачная операция:
##### Код: 201
```json
{
    "name": "Решение 1",
    "user_id": 17,
    "problem_id": 1,
    "updated_at": "2020-09-04T16:22:13.000000Z",
    "created_at": "2020-09-04T16:22:13.000000Z",
    "id": 104
}
```
##### Ошибка отсутствия данной проблемы:
##### Код: 404
```json
{
    "message": "Такой проблемы не существует"
}
```
##### Ошибки валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "Описание решения должно содержать не менее 6 символов"
        ]
    }
}
```

#### 3. solution.in-work
##### Удачная операция:
##### Код: 200
```json
[
    {
        "id": 98,
        "name": "I know who I WAS when I got up very.",
        "user_id": 15,
        "problem_id": 4,
        "in_work": true,
        "status": null,
        "created_at": "2020-07-04T12:34:39.000000Z",
        "updated_at": "2020-07-04T12:34:39.000000Z",
        "deleted_at": null
    },
    {
        "id": 45,
        "name": "When I used to say a word, but slowly followed.",
        "user_id": 5,
        "problem_id": 4,
        "in_work": true,
        "status": null,
        "created_at": "2020-07-14T21:58:30.000000Z",
        "updated_at": "2020-07-14T21:58:30.000000Z",
        "deleted_at": null
    }
]
```
##### Ошибка отсутствия данной проблемы:
##### Код: 404
```json
{
    "message": "Такой проблемы не существует"
}
```

#### 4. solution.show
##### Удачная операция:
##### Код: 200
```json
{
    "id": 1,
    "name": "Cat. 'Do you.",
    "user_id": 7,
    "problem_id": 2,
    "in_work": false,
    "status": null,
    "created_at": "2020-06-07T19:49:23.000000Z",
    "updated_at": "2020-06-07T19:49:23.000000Z",
    "deleted_at": null
}
```
##### Ошибка отсутствия данной проблемы:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```

#### 5. solution.changeInWork
##### Удачная операция:
##### Код: 200
```json
{
    "id": 10,
    "name": "gfhf fdh",
    "user_id": 5,
    "problem_id": 21,
    "in_work": false,
    "status": null,
    "created_at": "2020-08-22T23:11:16.000000Z",
    "updated_at": "2020-09-06T16:15:22.000000Z",
    "deleted_at": null
}
```
##### Попытка изменения статуса "в работе" с false на false:
##### Код: 422
```json
{
    "errors": "Решение не в работе"
}
```
##### Попытка изменения статуса "в работе" для не существующего решения/проблемы:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```

#### 6. solution.update
##### Удачная операция:
##### Код: 200
```json
{
    "id": 10,
    "name": "gfhf fdh",
    "user_id": 5,
    "problem_id": 21,
    "in_work": false,
    "status": null,
    "created_at": "2020-08-22T23:11:16.000000Z",
    "updated_at": "2020-09-06T16:15:22.000000Z",
    "deleted_at": null
}
```
##### Ошибки валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "Описание решения должно содержать не менее 6 символов"
        ]
    }
}
```
##### Ошибка отсутствия данного решения/проблемы:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```

#### 7. solution.destroy
##### Удачная операция:
##### Код: 200
```json
{
    "message": "Решение успешно удалено"
}
```
##### Ошибка отсутствия данного решения/проблемы:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```

#### 8. solution.changeStatus
##### Удачная операция:
##### Код: 200
```json
{
    "id": 2,
    "name": "Don't be all day about.",
    "creator_id": 6,
    "problem_id": 3,
    "in_work": true,
    "status": null,
    "deadline": null,
    "executor_id": null,
    "created_at": "2020-08-19T19:48:07.000000Z",
    "updated_at": "2020-08-19T19:48:07.000000Z",
    "deleted_at": null
}
```
##### Ошибка решение не принято в работу:
##### Код: 422
```json
{
    "errors": "Решение не в работе"
}
```
##### Ошибка валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "status": [
            "Неверный статус"
        ]
    }
}
```
##### Ошибка отсутствия данного решения/проблемы:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```

#### 9. solution.setDeadline
##### Удачная операция:
##### Код: 200
```json
{
    "id": 2,
    "name": "Don't be all day about.",
    "creator_id": 6,
    "problem_id": 3,
    "in_work": true,
    "status": null,
    "deadline": "20.12.2020",
    "executor_id": null,
    "created_at": "2020-08-19T19:48:07.000000Z",
    "updated_at": "2020-09-07T14:20:04.000000Z",
    "deleted_at": null
}
```
##### Ошибка решение не принято в работу:
##### Код: 422
```json
{
    "errors": "Решение не в работе"
}
```
##### Ошибка валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "deadline": [
            "Неверный формат даты",
            "Срок исполнения не может быть раньше текущей даты"
        ]
    }
}
```
##### Ошибка отсутствия данного решения/проблемы:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```

#### 10. solution.setExecutor
##### Удачная операция:
##### Код: 200
```json
{
    "id": 2,
    "name": "Don't be all day about.",
    "creator_id": 6,
    "problem_id": 3,
    "in_work": true,
    "status": null,
    "deadline": "20.12.2020",
    "executor_id": "1",
    "created_at": "2020-08-19T19:48:07.000000Z",
    "updated_at": "2020-09-07T14:20:04.000000Z",
    "deleted_at": null
}
```
##### Ошибка решение не принято в работу:
##### Код: 422
```json
{
    "errors": "Решение не в работе"
}
```
##### Ошибка валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "executor_id": [
            "Такого пользователя не существует"
        ]
    }
}
```
##### Ошибка отсутствия данного решения/проблемы:
##### Код: 404
```json
{
    "message": "Такого решения не существует"
}
```

### Авторизация и регистрация:
|№  | Имя метода | Описание операции               | URL           | Метод запроса | Принимаемые параметры |
|---|------------|---------------------------------|---------------|:-------------:|-----------------------|
|1. | register   | Регистрация нового пользователя | /api/register | POST          | <ol><li>name - имя пользователя</li><li>surname - фамилия пользователя</li><li>father_name - отчество пользователя</li><li>password - пароль</li><li>password_confirmation - повторите пароль</li><li>email - электронная почта</li></ol>      |
|2. | login      | Авторизация пользователя        | /api/login    | POST          | <ol><li>email - электронная почта</li><li>password - пароль</li></ol> |
|3. | logout     | Авторизация пользователя        | /api/logout   | POST          | Нет параметров        |

### Операции над проблемой:
|№  | Имя метода      | Описание операции             | URL                    | Метод запроса | Принимаемые параметры |
|---|-----------------|-------------------------------|------------------------|:-------------:|-----------------------|
|1. | problem.index   | Получение списка всех проблем | /api/problem           | GET / HEAD    | Нет параметров        |
|2. | problem.store   | Создание проблемы             | /api/problem           | POST          | name - имя проблемы   |
|3. | problem.update  | Изменение имеющейся проблемы  | /api/problem/{problem} | PUT           | name - имя проблемы   |
|4. | problem.show    | Получение проблемы            | /api/problem/{problem} | GET / HEAD    | Нет параметров        |
|5. | problem.destroy | Удаление проблемы             | /api/problem/{problem} | DELETE        | Нет параметров        |

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
##### Ошибки валидации:
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
            "id": 1,
            "name": "Гарри",
            "surname": "Куликов",
            "father_name": "Максимовна",
            "email": "artemeva.gennadii@example.org",
            "created_at": "2020-09-10T14:13:25.000000Z",
            "updated_at": "2020-09-10T14:13:25.000000Z"
        },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjM2YzY0YTZiZTIzMzBhMDkyNmJjZTU..."
}
```
##### Ошибки валидации:
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
            "Адрес электронной почты или пароль неправильные"
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

