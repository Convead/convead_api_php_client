PHP клиент для отправки запросом в convead API
-------------------

Подробная информация о API доступна на странице https://convead.io/api-doc

**Пример инициализации библиотеки**
```php
include_once('ConveadApi.php');
$access_token = 'ACCESS_TOKEN'; // Токен доступа к API, уникальный для каждого зарегистрированного пользователя Convead
$app_key      = 'API_KEY'; // APP-ключ вашего аккаунта, можно найти здесь: http://take.ms/Ejv3q

$api = new ConveadApi($access_token, $app_key);
```

**Пример создания заказа (передает информацию о совершенной покупке)**
```php
$order_id   = $order->id; // id заказа
$products   = $order->products(); // массив товаров в заказе
$revenue    = $order->total_cost(); // итоговая стоимость заказа с учетом доставки и скидок
$state      = $order->state(); // Статус заказа
$visitor    = array(
  'uid'=>'123',
  'email'=>'mail@example.net'
); // информация о покупателе

$items = array();
foreach ($products as $product) {
  $items[] = array(
    'product_id' => $product['product_id'],
    'qnt' => $product['quantity'],
    'price' => $product['price']
  );
}
$api->orderPurchase($order_id, $state, $revenue, $items, $visitor);
```

**Пример изменения статуса заказа (передает текущее содержимое заказа)**
```php
$order_id   = $order->id; // id заказа
$state      = $order->state(); // Статус заказа

$convead->orderUpdate($order_id, $state);
```

**Пример удаления заказа**
```php
$order_id   = $order->id; // id заказа

$convead->orderUpdate($order_id);
```
