PHP клиент для отправки запросом в convead API
-------------------

Подробная информация о API доступна на странице https://convead.io/api-doc

**Пример инициализации библиотеки**
```php
include_once('ConveadApi.php');
$access_token = 'ACCESS_TOKEN'; // Токен доступа к API, уникальный для каждого зарегистрированного пользователя Convead
$app_key      = 'API_KEY'; // APP-ключ вашего аккаунта, можно найти здесь: http://take.ms/Ejv3q

$api = new ConveadApi($access_token);
```

**Пример поиска контакта по email**
```php
$visitor    = array(
  'email'=>'mail@example.net'
);

$api->request("/api/v1/accounts/{app_key}/visitors", 'GET' $visitor);
```
