# Магазин

- Использует php 7.3+ как бэкенд (Symfony 5)
- БД MySQL

---

- Сервер должен перенаправлять все запросы на `public/index.html`


# Установка:
Обновляем composer
```bash
composer install
```

Запускаем локальный сайт
```bash
symfony server:start
```

Обновляем yarn модули
```bash
yarn install
```

Запускаем вебпак
```bash
yarn encore dev --watch
```

### Установка БД (var/diplom.sql)
Создаём локальную базу
```bash
php bin/console doctrine:database:create
```
Обновляем схему
```bash
php bin/console doctrine:schema:update
```
Можно залить фикстуры (dev и test окружение)
```bash
doctrine:migrations:migrate
```