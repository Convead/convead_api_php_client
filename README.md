PHP клиент для отправки евентов в convead
-------------------

**Пример инициализации библиотеки**
```php
include_once('ConveadTracker.php');
$app_key     = 'API_KEY'; // APP-ключ вашего аккаунта, можно найти здесь: http://take.ms/Ejv3q
$host        = $_SERVER['HTTP_HOST']; // должен совпадать с вашим доменом, указанным в настройках аккаунта
$visitor_uid = (is_logged_in() ? $current_user_id : false); // Если юзер авторизован, то подставляется его id, иначе - false.
$guest_uid   = (!empty($_COOKIE['convead_guest_uid']) ? $_COOKIE['convead_guest_uid'] : false);

$convead = new ConveadTracker($app_key, $host, $guest_uid, $visitor_uid);
```

**Пример отправки евента update_cart**
```php
$products = $cart->products(); // массив товаров в корзине

$items = array();
foreach ($products as $product) {
  $items[] = array(
    'product_id' => $product['product_id'],
    'qnt' => $product['quantity'],
    'price' => $product['price']
  );
}
$convead->eventUpdateCart($items);
```

**Пример отправки евента purchase**
```php
$order_id   = $order->id; // id заказа
$products   = $order->products(); // массив товаров в заказе
$total_cost = $order->total_cost(); // итоговая стоимость заказа с учетом доставки и скидок

$items = array();
foreach ($products as $product) {
  $items[] = array(
    'product_id' => $product['product_id'],
    'qnt' => $product['quantity'],
    'price' => $product['price']
  );
}
$convead->eventOrder($order_id, $total_cost, $items);
```

**Пример отправки кастомного евента**
```php
$key        = 'callback'; // Ключ кастомного евента
$properties = array('phone'=>$phone, 'first_name'=>$name); // Набор передаваемых данных

$convead->eventCustom($key, $properties);
```
