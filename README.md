# Univer Api
___
RESTful API на Laravel для управления студентами, классами и лекциями с возможностью формирования учебных планов.
API позволяет получать, создавать и обновлять данные о студентах, классах и лекциях, отслеживать пройденные темы и управлять составом учебных планов.
Все операции выполняются через RESTful-интерфейс с валидацией, строгой типизацией.
___

## Документация
#### Формат ответа

```json
{
  "status_code": 200,
  "message": "ok",
  "data": {}
}
```

### 1. Студенты
___
```url
GET /api/students
```
**Описание:** список всех студентов

**Ответ:**
```json
[
  { "id": 1, "name": "Иван Иванов" },
  { "id": 2, "name": "Мария Петрова" }
]
```
___
```url
GET /api/students/{id}
```
**Описание:** инфо о студенте (имя, email, класс, пройденные лекции)

**Ответ:**
```json
{
  "name": "Иван Иванов",
  "email": "ivan@example.com",
  "class": {
    "id": 3,
    "name": "10-A"
  },
  "lectures": [
    { "id": 1, "title": "Математика" },
    { "id": 2, "title": "Физика" }
  ]
}
```
___
```url
POST /api/students
```
**Описание:** создать студента

**Body**
```json
{
  "name": "Иван Иванов",
  "email": "ivan@example.com",
  "class_id": 3
}
```
___
```url
PUT /api/students/{id}
```
**Описание:** обновить студента

**Body**
```json
{
  "name": "Иван Иванов",
  "class_id": 3
}
```
___
```url
DELETE /api/students/{id}
```
**Описание:** удалить студента
___

### 2. Классы
___
```url
GET /api/classes
```
**Описание:** список всех классов

**Ответ:**
```json
[
    { "id": 1, "name": "9-A" },
    { "id": 2, "name": "10-B" }
]
```
___
```url
GET /api/classes/{id}
```
**Описание:** инфо о классе и его студентах

**Ответ:**
```json
{
    "class_id": 1,
    "class_name": "10-B",
    "students": [
        { "id": 5, "name": "Иван" },
        { "id": 8, "name": "Ольга" }
    ]
}
```
___
```url
GET /api/classes/{id}/lectures
```
**Описание:** учебный план класса: лекции с порядком и статусом (completed:true - пройдена)

**Ответ:**
```json
{
    "class_id": 3,
    "class_name": "10-B",
    "lectures": [
        { "id": 1, "title": "Алгебра", "order": 0, "completed": true },
        { "id": 2, "title": "Геометрия", "order": 1, "completed": false }
    ]
}
```
___
```url
PUT /api/classes/{id}/lectures
```
**Описание:** обновить учебный план класса

**Body:**
```json
{
    "lectures": [
        { "id": 1, "order": 0, "completed": true },
        { "id": 4, "order": 1, "completed": false }
    ]
}
```
___
```url
POST /api/classes
```
**Описание:** создать учебный класс

**Body:**
```json
{ 
    "name": "11-А"
}
```
___
```url
PUT /api/classes/{id}
```
**Описание:** обновить название класса

**Body:**
```json
{ 
    "name": "11-А"
}
```
___
```url
DELETE /api/classes/{id}
```
**Описание:** удалить класс
___

### 3. Лекции
___
```url
GET /api/lectures
```
**Описание:** список всех лекций

**Ответ:**
```json
[
    { "id": 1, "title": "Алгебра" },
    { "id": 2, "title": "Физика" }
]
```
___
```url
GET /api/lectures/{id}
```
**Описание:** детали лекции (заголовок, описание, какие классы прослушали, какие студенты прослушали)

**Ответ:**
```json
{
    "id": 1,
    "title": "Алгебра",
    "description": "Основы уравнений",
    "classes": [
        {
            "class_id": 3,
            "class_name": "10-B",
            "students": [
                { "id": 5, "name": "Иван" },
                { "id": 8, "name": "Ольга" }
            ]
        }
    ]
}
```
___
```url
POST /api/lectures
```
**Описание:** создать лекцию

**Body:**
```json
{
  "title": "Химия",
  "description": "Основы химических реакций"
}
```
___
```url
PUT /api/lectures/{id}
```
**Описание:** обновить лекцию

**Body:**
```json
{
  "title": "Химия",
  "description": "Основы химических реакций"
}
```
___
```url
DELETE /api/lectures/{id}
```
**Описание:** удалить лекцию
___

### Развернуть проект:
1. Клонировать проект
```bash
git clone https://github.com/VladislavTeteryatnikov/univer_api.git
```
2. Перейти в папку с проектом
```bash
cd univer_api
```
3. Создать .env и скопировать в него .env.example
```bash
cp .env.example .env
```
4. При необходимости настроить подключение к БД в файле .env
5. Установить зависимости (автоматически будет создан ключ, создана БД, накатятся миграции и сидеры)
```bash
composer install
```
6. Запустить сервер
```bash
php artisan serve
```
7. Открыть в браузере endpoint, например:
   http://localhost:8000/api/students
