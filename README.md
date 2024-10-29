<h1>Invoice Payment Module</h1>

<h3>Установка</h3>

Установите пакет через Composer:
```
composer require invoice-llc/payment-laravel:dev-master
```

Создайте файл invoice.php в папке **config** со следующим содержанием:
```php
<?php
return [
    "api_key" => env('INVOICE_API_KEY',"Ваш API ключ"),
    "login" => env('INVOICE_LOGIN',"логин от личного кабинета Invoice"),
    "default_terminal_name" => env('INVOICE_TERMINAL_NAME',"Название терминала"),
];
```

<h3>Создание контроллера уведомлений</h3>

1.Создайте контроллер и унаследуйте класс AbstractNotificationController

```php
<?php

class InvoiceController extends AbstractNotificationController {

        //orderID - ID заказа в вашей системе

        function onPay($orderId, $amount)
        {
             //При успешной оплате
        }
    
        function onFail($orderId)
        {
            //При неудачной оплате
        }
    
        function onRefund($orderId)
        {
            //При возврате средств
        }
}
```

2.В личном кабинете Invoice(Настройки->Уведомления->Добавить) добавьте уведомление с типом **WebHook**
и адресом, который вы задали в конфиге(например: %url%/notify)

<h3>Создание платежей</h3>

```php
<?php

use \invoice\payment\InvoicePaymentManager;

$invoice = new InvoicePaymentManager();

$items = [
    //Название, цена за 1шт, кол-во, итоговая цена
    new ITEM('Какой-то предмет', 110 , 2, 220)
];
//ID заказа, цена, товары
$payment = $invoice->createPayment('ID заказа в вашей системе', 220, $items);

echo($payment->payment_url);
```

<h3>Получение статуса платежа</h3>

```php
<?php

$invoice = new InvoicePaymentManager();

$payment = $invoice->getPayment('ID заказа в вашей системе');

echo($payment->payment_url);
```

<h3>Создание возврата</h3>

```php
<?php

$invoice = new InvoicePaymentManager();

//ID заказа в вашей системе, сумма возврата, причина
$refundInfo = $invoice->createRefund('ID заказа в вашей системе', 10, 'Причина');

```
