# Installation

1. add `@app/config/db-local.php` with appropriate credentials
1. install dependencies - `composer install`
1. init yii2 rbac + yii2-usuario migrations - `./yii migrate --migrationPath=@yii/rbac/migrations`
1. create default admin user `./yii user/create admin@mail.ru admin && ./yii user/confirm admin`
1. create default manager `./yii user/create manager@mail.ru manager`
1. init application's rbac `./yii rbac/init`
1. run apllication's migrations `./yii migrate`

## one-liner

```
./yii migrate --migrationPath=@yii/rbac/migrations ; \
./yii user/create admin@admin.ru admin 123456 ; \
./yii user/create manager@manager.ru manager 123456 ; \
./yii rbac/init ; \
./yii migrate
```