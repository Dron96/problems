# API для Problems

## Стек
1. PHP 7.4.3
2. Laravel 7.21
3. PostgreSQL 12.3

## Описание:

### Авторизация и регистрация:
|№  | Имя метода | Описание операции               | URL           | Метод запроса | Принимаемые параметры |
|---|------------|---------------------------------|---------------|:-------------:|-----------------------|
|1. | [register](#1.register)   | Регистрация нового пользователя | /api/register | POST          | <ol><li>name - имя пользователя</li><li>surname - фамилия пользователя</li><li>father_name - отчество пользователя</li><li>password - пароль</li><li>password_confirmation - повторите пароль</li><li>email - электронная почта</li></ol>      |
|2. | [login](#-2.-login)      | Авторизация пользователя        | /api/login    | POST          | <ol><li>email - электронная почта</li><li>password - пароль</li></ol> |
|3. | [logout](####-3.-logout)     | Авторизация пользователя        | /api/logout   | POST          | Нет параметров        |

### Операции над проблемой:
|№  | Имя метода      | Описание операции             | URL                    | Метод запроса | Принимаемые параметры |
|---|-----------------|-------------------------------|------------------------|:-------------:|-----------------------|
|1. | [problem.index](#1.-problem.index)   | Получение списка всех проблем | /api/problem           | GET / HEAD    | Нет параметров        |
|2. | [problem.store](#2.-problem.store)   | Создание проблемы             | /api/problem           | POST          | <ol><li>name - название проблемы</li><li>description - описание</li><li>possible_solution - возможное решение</li></ol> |
|3. | [problem.update](#3.-problem.update)  | Изменение названия проблемы  | /api/problem/{problem} | PUT           | name - имя проблемы   |
|4. | [problem.show](#4.-problem.show)    | Получение проблемы            | /api/problem/{problem} | GET / HEAD    | Нет параметров        |
|5. | [problem.destroy](#5.-problem.destroy) | Удаление проблемы             | /api/problem/{problem} | DELETE        | Нет параметров        |
|6. | [problem.likeProblem](#6.-problem.likeProblem) | Поставить/убрать лайк проблеме   | /api/problem/{problem}/like | POST | Нет параметров        |
|7. | [problem.sendToGroup](#7.-problem.sendToGroup) | Направление проблемы в подразделения | /api/problem/{problem}/send-to-group | POST | group_ids - массив id подразделений |
|8. | [problem.setExperience](#8.-problem.setExperience) | Задать/изменить опыт | /api/problem/{problem}/{problem}/set-experience | PUT | experience - опыт |
|9. | [problem.setResult](#9.-problem.setResult) | Задать/изменить результат | /api/problem/{problem}/set-result | PUT | result - результат |
|10. | [problem.setPossibleSolution](#10.-problem.setPossibleSolution) | Изменение возможного решения проблемы | /api/problem/{problem}/set-possible-solution | PUT | possible_solution - возможное решение |
|11. | [problem.setDescription](#11.-problem.setDescription) | Изменение описания проблемы | /api/problem/{problem}/set-description | PUT | description - описание проблемы |
|12. | [problem.setImportance](#12.-problem.setImportance) | Изменение важности проблемы | /api/problem/{problem}/set-importance | PUT | importance - важность проблемы |
|13. | [problem.setProgress](#13.-problem.setProgress) | Изменение прогресса решения проблемы | /api/problem/{problem}/set-progress | PUT | progress - прогресс решения |
|14. | [problem.setUrgency](#14.-problem.setUrgency) | Изменение срочности проблемы | /api/problem/{problem}/set-urgency | PUT | urgency - срочность решения |
|15. | [problem.sendForConfirmation](#15.-problem.sendForConfirmation) | Направить проблему заказчику для подтверждения решения  | /api/problem/{problem}/send-for-confirmation | PUT | Нет параметров |
|16. | [problem.rejectSolution](#16.-problem.rejectSolution) | Отклонить решение проблемы  | /api/problem/{problem}/reject-solution | PUT | Нет параметров |
|17. | [problem.confirmSolution](#17.-problem.confirmSolution) | Подтвердить решение проблемы  | /api/problem/{problem}/confirm-solution | PUT | Нет параметров |



## Ответы:

### Авторизация и регистрация:
#### 1.register
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

#### 2. login
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

#### 3. logout
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
        "updated_at": "2020-08-25T18:01:20.000000Z",
        "likes_count": 1,
        "is_liked": true,
    },
    {
        "id": 30,
        "name": "123456",
        "created_at": "2020-08-25T17:42:03.000000Z",
        "updated_at": "2020-08-25T17:42:03.000000Z",
        "likes_count": 0,
        "is_liked": false,
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
    "id": 33,
    "likes_count": 1,
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
    "updated_at": "2020-08-26T03:40:24.000000Z",
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
    "updated_at": "2020-08-25T07:27:49.000000Z",
    "likes_count": 1,
    "is_liked": true,
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

#### 6. problem.likeProblem
##### Удачная операция:
##### Код: 200
```json
{
    "message": "Успешно"
}
```

#### 7. problem.sendToGroup
##### Удачная операция:
##### Код: 200
```json
[
    {
        "id": 1,
        "name": "ООО ВостокСантехИнфо",
        "short_name": "Corrupti.",
        "leader_id": 2,
        "created_at": "2020-08-22T04:19:19.000000Z",
        "updated_at": "2020-08-22T04:19:19.000000Z",
        "deleted_at": null,
        "pivot": {
            "problem_id": 30,
            "group_id": 1
        }
    },
    {
        "id": 2,
        "name": "ОАО ЖелДор",
        "short_name": "Qui qui.",
        "leader_id": 4,
        "created_at": "2020-07-22T12:18:51.000000Z",
        "updated_at": "2020-07-22T12:18:51.000000Z",
        "deleted_at": null,
        "pivot": {
            "problem_id": 30,
            "group_id": 2
        }
    },
    {
        "id": 3,
        "name": "МКК ОблСантехМоторМашина",
        "short_name": "Omnis.",
        "leader_id": 4,
        "created_at": "2020-08-21T19:09:48.000000Z",
        "updated_at": "2020-08-21T19:09:48.000000Z",
        "deleted_at": null,
        "pivot": {
            "problem_id": 30,
            "group_id": 3
        }
    }
]
```
##### Подразделение не существует:
##### Код: 422
```json
{
    "error": "Выбрано не существующее подразделение"
}
```

### Операции над подразделениями:
|№   | Имя метода                | Описание операции                         | URL                                     | Метод запроса | Принимаемые параметры   |
|----|---------------------------|-------------------------------------------|-----------------------------------------|:-------------:|-------------------------|
|1.  | group.index               | Получение списка всех подразделений       | /api/group                              | GET / HEAD    | Нет параметров          |
|2.  | group.store               | Создание подразделения                    | /api/group                              | POST          | <ol><li>name - полное название подразделения</li><li>short_name - краткое название подразделения</li><li>leader_id - id начальника подразделения</li></ol>|
|3.  | group.show                | Получение подразделения                   | /api/group/{group}                      | GET / HEAD    | Нет параметров          |
|4.  | group.update              | Изменение полного названия подразделения  | /api/group/{group}                      | PUT           | name - полное название подразделени |
|5.  | group.updateShortName     | Изменение краткого названия подразделения | /api/group/{group}/change-short-name    | PUT           | short_name - краткое название подразделения |
|6.  | group.destroy             | Удаление подразделения                    | /api/group/{group}                      | DELETE        | Нет параметров          |
|7.  | group.addUser             | Добавление сотрудника в подразделение     | /api/group/{group}/user/{user}          | PUT           | Нет параметров          |
|8.  | group.getLeader           | Получить начальника подразделения         | /api/group/{group}/leader               | GET / HEAD    | Нет параметров          |
|9.  | group.getUsers            | Получить список сотрудников подразделения | /api/group/{group}/user                 | GET / HEAD    | Нет параметров          |
|10. | group.removeUserFromGroup | Удалить сотрудника из подразделения       | /api/group/{group}/remove-user/{user}   | PUT           | Нет параметров          |
|11. | group.changeLeader        | Сменить начальника подразделения          | /api/group/{group}/change-leader/{user} | PUT           | Нет параметров          |


### Ответы:
#### 1. group.index
##### Удачная операция:
##### Код: 200
```json
[
    {
        "id": 6,
        "name": "МКК ЖелДорCиб",
        "short_name": "Non eos.",
        "leader_id": 4,
        "created_at": "2020-07-18T16:12:01.000000Z",
        "updated_at": "2020-07-18T16:12:01.000000Z",
        "deleted_at": null
    },
    {
        "id": 2,
        "name": "МКК РыбИнфо",
        "short_name": "Voluptate.",
        "leader_id": 1,
        "created_at": "2020-08-26T09:30:48.000000Z",
        "updated_at": "2020-08-26T09:30:48.000000Z",
        "deleted_at": null
    },
    ...
```

#### 2. group.store
##### Удачная операция:
##### Код: 201
```json
{
    "name": "Группа12",
    "short_name": "Гр",
    "leader_id": "2",
    "updated_at": "2020-09-21T15:36:46.000000Z",
    "created_at": "2020-09-21T15:36:46.000000Z",
    "id": 7
}
```
##### Ошибки валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "leader_id": [
            "Такого пользователя не существует"
        ]
    }
}
```

#### 3. group.show
##### Удачная операция:
##### Код: 200
```json
{
    "id": 2,
    "name": "МКК РыбИнфо",
    "short_name": "Voluptate.",
    "leader_id": 1,
    "created_at": "2020-08-26T09:30:48.000000Z",
    "updated_at": "2020-08-26T09:30:48.000000Z",
    "deleted_at": null
}
```
##### Ошибка отсутствия данного подразделения:
##### Код: 404
```json
{
    "message": "Такого подразделения не существует"
}
```

#### 4. group.update
##### Удачная операция:
##### Код: 200
```json
{
    "id": 3,
    "name": "Privet1",
    "short_name": "Et non.",
    "leader_id": 4,
    "created_at": "2020-06-25T08:41:27.000000Z",
    "updated_at": "2020-09-21T15:40:50.000000Z",
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
            "Подразделение с таким названием (полностью) существует"
        ]
    }
}
```
##### Ошибка отсутствия данного подразделения:
##### Код: 404
```json
{
    "message": "Такого подразделения не существует"
}
```
#### 5. group.updateShortName
##### Удачная операция:
##### Код: 200
```json
{
    "id": 3,
    "name": "Privet1",
    "short_name": "Et non.",
    "leader_id": 4,
    "created_at": "2020-06-25T08:41:27.000000Z",
    "updated_at": "2020-09-21T15:40:50.000000Z",
    "deleted_at": null
}
```
##### Ошибки валидации:
##### Код: 422
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "short_name": [
            "Подразделение с таким названием (сокращенно) существует"
        ]
    }
}
```
##### Ошибка отсутствия данного подразделения:
##### Код: 404
```json
{
    "message": "Такого подразделения не существует"
}
```

#### 6. group.destroy
##### Удачная операция:
##### Код: 200
```json
{
    "message": "Подразделение успешно удалено"
}
```
##### Ошибка отсутствия данного подразделения:
##### Код: 404
```json
{
    "message": "Такого подразделения не существует"
}
```

#### 7. group.addUser
##### Удачная операция:
##### Код: 200
```json
{
    "id": 5,
    "name": "Всеволод",
    "surname": "Тарасовa",
    "father_name": "Ивановна",
    "email": "trofim82@example.com",
    "created_at": null,
    "updated_at": "2020-09-21T15:45:15.000000Z",
    "group_id": 2
}
```
##### Ошибка отсутствия данного подразделения/пользователя:
##### Код: 404
```json
{
    "message": "Такого подразделения не существует"
}
```

#### 8. group.getLeader
##### Удачная операция:
##### Код: 200
```json
[
    {
        "id": 3,
        "name": "Игнат",
        "surname": "Крюков",
        "father_name": "Максимович",
        "email": "bpotapov@example.net",
        "created_at": "2020-09-20T07:04:29.000000Z",
        "updated_at": "2020-09-20T07:04:29.000000Z",
        "group_id": null
    }
]
```
##### Ошибка отсутствия данного подразделения:
##### Код: 404
```json
{
    "message": "Такого подразделения не существует"
}
```

#### 9. group.getUsers
##### Удачная операция:
##### Код: 200
```json
[
    {
        "id": 12,
        "name": "Инесса",
        "surname": "Гущин",
        "father_name": "Александровна",
        "email": "kirill.pestova@example.com",
        "created_at": null,
        "updated_at": null,
        "group_id": 2
    },
    {
        "id": 21,
        "name": "Андрей",
        "surname": "Гущине",
        "father_name": "Анатольевич",
        "email": "gfhfh1fa@g.ru",
        "created_at": "2020-09-20T07:17:00.000000Z",
        "updated_at": "2020-09-20T07:17:32.000000Z",
        "group_id": 2
    },
    ...
```
##### Ошибка отсутствия данного подразделения:
##### Код: 404
```json
{
    "message": "Такого подразделения не существует"
}
```

#### 10. group.removeUserFromGroup
##### Удачная операция:
##### Код: 200
```json
{
    "message": "Пользователь успешно удален из подразделения"
}
```
##### Ошибка отсутствия данного подразделения/пользователя:
##### Код: 404
```json
{
    "message": "Такого подразделения не существует"
}
```

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
