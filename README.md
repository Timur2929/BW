## Установка

1. Обновить PHP зависимости (Composer):
```bash
composer update
```

2. Установите зависимости:
```bash
npm i
```

3. Скопируйте файл конфигурации:
```bash
cp .env.example .env
```

4. Сгенерируйте ключ приложения:
```bash
php artisan key:generate
```

5. Настройте подключение к базе данных в файле .env:
```
DB_CONNECTION=mysql
DB_HOST=db.web.edu
DB_PORT=3306
DB_DATABASE=22051_shop
DB_USERNAME=22051
DB_PASSWORD=your_password
```

6. Обновите миграции и заполните базу данных начальными данными:
```bash
php artisan migrate:fresh --seed
```

7. Создайте символическую ссылку для хранения файлов:
```bash
php artisan storage:link
```

8. Запустите сборку фронтенда:
```bash
npm run dev
```

9. Запустите локальный сервер:
```bash
php artisan serve
```

10. Установить vue-иконки:
```bash
npm install @heroicons/vue
```


## Роли пользователей

- Гость (неавторизованный пользователь)
- Пользователь
- Администратор

## Галлерея Информационной системы

