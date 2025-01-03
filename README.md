pcb-dingtalk
================================

## 安装

```sh
composer require pcb-plus/pcb-dingtalk
```

## 用例

```php
$client = new PcbPlus\PcbDingtalk\Client([
    'app_id' => 'xxxxxxxxxxxxxxxxxxxx',
    'app_secret' => 'xxxxxxx_xxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
]);

$service = new PcbPlus\PcbDingtalk\Services\UserService($client);

$user = $service->getUserByPhoneNumber($phone);
```
