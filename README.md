yii2-coordinator
============================

Координатор для yii2 шардинга, поддерживает любые уровни координации в любой последовательности (функцией, virtual bucket).

Установка
-------------------
 
Установка с помощью пакета composer `"axiles89/yii2-coordinator": "*"`


Пример использования
------------

Сконфигурируйте компонент `coordinator` по примеру:

```php
    'components' => [
        'coordinator' => [
            'class' => 'axiles89\coordinator\CoordinatorComponent',
            'component' => [
                [
                    'class' => 'axiles89\coordinator\FunctionCoordinator',
                    'function' => function($i) {
                        return $i % 4;
                    }
                ],
                [
                    'class' => 'axiles89\coordinator\RedisCoordinator',
                    'hashName' => 'sharding',
                    'connect' => [
                        'class' => 'yii\redis\Connection',
                        'hostname' => '127.0.0.1',
                        'port' => 6379,
                        'database' => 4,
                    ]
                ],
                [
                    'class' => 'axiles89\coordinator\DbCoordinator',
                    'table' => [
                        'name' => 'sharding',
                        'columnSearch' => 'bucket_id',
                        'columnResult' => 'shard_id'
                    ],
                    'connect' =>[
                        'class' => 'yii\db\Connection',
                        'dsn' => 'mysql:host=localhost;dbname=yii2basic',
                        'username' => 'root',
                        'password' => 'dm1989',
                        'charset' => 'utf8',
                    ]
                ]
            ]
        ],
    ]
```

Где `component` - это массив разных типов координаторов, которые будут вызываться последовательно, принимая на вход
результаты работы предыдущего. Затем вызывайте соответсвующий метод получения шардов:

```php
...
$coordinator = \Yii::$app->coordinator;
$shardDb = $coordinator->getShard($db, $keyShard);
...
```
Где `$db` - массив имен компонентов баз данных, которые участвуют в шардинге. Пример: `db1, db2, db3`, 
`$keyShard` - номер или массив значений ключа шардинга. Метод возвращает имена нужных шардов или пустой массив, если шарды не найдены.
### FunctionCoordinator

Координатор, где номер шарда получается с помощью функции, которая задается параметром `function` как `callable`,
на вход подается значение ключа:

```php
...
'function' => function($i) {
    return $i % 4;
}
...
```

### DbCoordinator

Координатор через базу данных с помощью таблицы, где `connect` - конфиг для подключения к нужной бд. Таблица должна обязательно иметь 
поле со значением ключа и поле со значением номера компонента базы данных (`1` соответствует компоненту `db1`). 
Префикс `db` можно сменить на любой другой, который используется в вашем проекте:

```php
...
'table' => [
    'name' => 'sharding',
    'columnSearch' => 'bucket_id',
    'columnResult' => 'shard_id'
  ],
...  
```

### RedisCoordinator

Координатор через `redis`. Работает через хеши, поэтому предварительно необходимо настроить нужный хеш с именем, которое
соответсвует значению заданному в `hashName`. Ключами в хеше должны выступать значения ключа шардинга, а значениями номера 
компонентов `db`:

```php
...
 'hashName' => 'sharding'
...  
```
