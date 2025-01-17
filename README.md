### Komunikator PBX - Открытая и свободная АТС на базе [Yate PBX](http://www.yate.ro/products.php)

Основные функции АТС включают следующие:
- Маршрутизация и переадресация вызовов
- Автосекретарь
- Запись разговоров
- Модули интеграции с веб-сайтом ("Перезвоните мне", "Звонок с сайта")

#### Лицензия: 
GNU GPLv3

#### Инсталляция
[Файл-сценарий](https://komunikator.ru/repos/IP-PBX_deploy.sh) для автоматической установки проекта или [готовый образ виртуальной машины](https://komunikator.ru/repos/komunikator.ova) можно загрузить с нашей [домашней страницы](https://komunikator.ru/ip_ats)

#### Самостоятельная сборка deb-пакета:
Для полноценной работы проекта и всех его модулей необходимы:
- Apache 2.22
- MySQL
- PHP
- PHP Pear
- sox
- madplay
- lame

После установки всех зависимостей необходимо выполнить в консоли
```sh
  debuild clean
  debuild -b
  dpkg -i kommunikator_1.1-all.deb
  apt-get install -f
````

#### Конфигурация системы
Более подробное описание АТС, а также инструкции по её настройке можно найти в [нашем блоге](https://komunikator.ru/news/index.php?tags=%D0%BD%D0%B0%D1%81%D1%82%D1%80%D0%BE%D0%B9%D0%BA%D0%B0+Komunikator)
