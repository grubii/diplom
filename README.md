# Symfony ONLINE SHOP

- Use php 7.3+ for backend (Symfony 5)
- Database MySQL

---

- server push all query's on `public/index.html`


# INSTALL:
update composer
```bash
composer install
```

up server with symfony
```bash
symfony server:start
```

update yarn modules
```bash
yarn install
```

up webpuck
```bash
yarn encore dev --watch
```

### install database (var/diplom.sql)
Make local database
```bash
php bin/console doctrine:database:create
```
Update schema
```bash
php bin/console doctrine:schema:update
```
fixtures
```bash
doctrine:migrations:migrate
```
