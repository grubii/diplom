# Магазин

- Использует php 7.3+ как бэкенд (Symfony 5)
- БД MySQL

---

- Сервер должен перенаправлять все запросы на `public/index.html`


# Установка:

- php bin/console doctrine:database:create <br>
- php bin/console doctrine:schema:update <br>
- symfony server:start <br>
- yarn encore dev --watch <br>



### Установка БД (var/diplom.sql)
```bash
php bin/console doctrine:database:create
```
Можно залить фикстуры (dev и test окружение)
```bash
php bin/console doctrine:fixtures:load
```