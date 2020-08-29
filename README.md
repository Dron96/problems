# API для Problems

## Стек
1. PHP 7.4.3
2. Laravel 7.21
3. PostgreSQL 12.3

## Описание:

### Авторизация и регистрация:
|№  | Имя метода | Описание операции               | URL           | Метод запроса | Принимаемые параметры |
|---|------------|---------------------------------|---------------|:-------------:|-----------------------|
|1. | register   | Регистрация нового пользователя | /api/register | POST          | <ol><li>name - имя пользователя</li><li>password - пароль</li><li>password confirmation - повторите пароль</li><li>email - электронная почта</li></ol>      |
|2. | login      | Авторизация пользователя        | /api/login    | POST          | <ol><li>name - имя пользователя</li><li>password - пароль</li></ol> |

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
    "user": {
        "name": "user22235",
        "email": "dfnhjjk@d.ru",
        "updated_at": "2020-08-29T14:24:21.000000Z",
        "created_at": "2020-08-29T14:24:21.000000Z",
        "id": 6
    },
    "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZTAxZmFmNGVjZjgwMDE2ZDYzNDVjY2ViNWUzNjYxYmI0YWY5YzdiMTRmMGU1YjRkOWI0MzFmMjgyMGMxNTIwMzhlNGVmMTRhODVjNTE0OTgiLCJpYXQiOjE1OTg3MTEwNjEsIm5iZiI6MTU5ODcxMTA2MSwiZXhwIjoxNjMwMjQ3MDYxLCJzdWIiOiI2Iiwic2NvcGVzIjpbXX0.X2aCfLAnUJ94a2uUZplabnb-GdMFrKID4kkTOC1AS_P_YPNhK6knb7VAeDp6uQe4w0kUJmkPnj8tx2eGmHOROL0AKGn2rW79hQ6CIgWGWgskp7c1B4jpLhZthHfORqLHlCYFQc0-uGUIdKY02ieP_gmW7c7rXlZFuF1efyOeErzKhOL0nop3vRycp5a2d0hNo9-p7h8Lsm6QR2ABKKC3gvbM26ZLQEokUTQliUxuBe-V-jqQqQnN45pdsLuA39DdzFWsI3BIP0NCSwDhy8dW95Lhmy8q0Z40qlCtnqbaEwJPPF1HRh3bvioXaIfcE9jdtvpQqnIcfEPqdmN6_3SFtATmGkkU_xt7k1Kc867TWFIDTU9vX4Qobfje_kExO24t-SspbUj2Nqq5C5NfxzgkOld7KTFtNyIe4G22rKha6F1WQuinBbUuVVdnO4gyL5_j0AXvRZYdwgN-azwcECvX0SU287SlBC__BFLyc0Py-yJabFtKPYmlPg4BADfm8d7-dpw8Qr85Bl4FkAEKk-SKWlGlgy0NYTOsf18GepOwU2EVffpQI7NnW-y8CeTy37fzRBaN3UH5tPGYghhSxdVZ6fVPVoYXpPpQ-vrJCemyYdaamhr6kCv52c7Mlew8M3RamkMOLowgPzM0Kckyhr1_KMWW1WcPSJB5KVmENJ0f7eM"
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
        "id": 5,
        "name": "user2223",
        "email": "dfnhjjk__@d.ru",
        "email_verified_at": null,
        "created_at": "2020-08-29T10:00:19.000000Z",
        "updated_at": "2020-08-29T10:00:19.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWM2NWQ4YmI1NmEwNWM1ZmM0YWYxMzQwODk2NWFhOTk0ZGY0NzdjY2I1NDJjYThmZGY4YTg5YjE3OGRmOWU1YmYxNjg1NDhhMmMyMDNmNjUiLCJpYXQiOjE1OTg2OTk0MjMsIm5iZiI6MTU5ODY5OTQyMywiZXhwIjoxNjMwMjM1NDIzLCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.JPrcCGyAeg3uxAyHBMZbWG3Eqdd7GT15BTmwRaSEoS970Qs74JWVvUVWBPDphoMQAXBUvlBnzQOkidpKYq8tjATCdVkYmNHm13pSagyXggXrewd_io3Nlj_fZqddDuhdC7oNytTeATsx3lAbmOybJDKhahpewkGetvhzgmE76nmZ-lAcIEyW2GK3EFX5XtYszX9fn42edEsKpUmNM3mii_hIiyAJpQkCcDsbee49rUE6Ofd1ChS_0f9wkxgmd2aWZe0k0IriS8aRY2T2jfmLIN1a13Q4AC1u_6ktuZn_X-voIcPpJY_V2_WLTjAoH1Zbt8UOa6_aTk5tzMYKmd5VOnPFfXWyv_coqM39nYFGzk7Z9JmCGvLAluD6doT4TQZTkntBDmemBQtigesxyYNEdKe_QmtvZvnZ-qiffseDM6Ju6ANSCeNyft_QjNTevsX0fOjynwdmKaWF7WsbBO1r89dEBZjeygIScs4IFTUjTA0V7EzeX8KRFeH9udqI7O34NDn8IPzA2-ziAGhHFkaL6TR062aMamhUhPaM1Kn_LaSTO8gYZRZoJ_xj3bIxk2kB_OUdSRV9rSMRa19eMVzrg3JPMLkm1ewy3Njb0WnmwQoZsKXzn0cGwVPTBfJA7BOgiq2y8OHNPMPUPFpxCFcydncQ4R6RDzhLNr8rWpOiju0"
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
##### Ошибка:
##### Код: 404
```json

```
#### 5. problem.destroy
##### Удачная операция:
##### Код: 200
```json
{
    "message": "Проблема успешно удалена"
}
```
##### Ошибка:
##### Код: 404
```json

```

