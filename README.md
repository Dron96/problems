# API для Problems

### Стек
1. PHP 7.4.3
2. Laravel 7.21
3. PostgreSQL 12.3

### Описание:

#### Операции над проблемой:
|№  | Имя метода      | Описание операции             | URL               | Метод запроса | Параметры           |
|---|-----------------|-------------------------------|-------------------|:-------------:|---------------------|
|1. | problem.index   | Получение списка всех проблем | /api/problem      | GET / HEAD    | Нет параметров      |
|2. | problem.store   | Создание проблемы             | /api/problem      | POST          | name - имя проблемы |
|3. | problem.update  | Изменение имеющейся проблемы  | /api/problem/{id} | PUT           | name - имя проблемы |
|4. | problem.show    | Получение проблемы            | /api/problem/{id} | GET / HEAD    | Нет параметров      |
|5. | problem.destroy | Удаление проблемы             | /api/problem/{id} | DELETE        | Нет параметров      |

#### Ответы:
|№  | Имя метода      | Ответ |
|---|-----------------|----------------------------------------------------|
|1. | problem.index   | ```json{"message": "The given data was invalid.","errors": {"name": ["Название проблемы должно быть не менее 6 символов"]}}``` |
|2. | problem.store   | |
|3. | problem.update  | |
|4. | problem.show    | |
|5. | problem.destroy | |
