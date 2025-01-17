#!/usr/bin/php -q
<?php

/*
*  | RUS | - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

*    «Komunikator» – Web-интерфейс для настройки и управления программной IP-АТС «YATE»
*    Copyright (C) 2012-2013, ООО «Телефонные системы»

*    ЭТОТ ФАЙЛ является частью проекта «Komunikator»

*    Сайт проекта «Komunikator»: http://4yate.ru/
*    Служба технической поддержки проекта «Komunikator»: E-mail: support@4yate.ru

*    В проекте «Komunikator» используются:
*      исходные коды проекта «YATE», http://yate.null.ro/pmwiki/
*      исходные коды проекта «FREESENTRAL», http://www.freesentral.com/
*      библиотеки проекта «Sencha Ext JS», http://www.sencha.com/products/extjs

*    Web-приложение «Komunikator» является свободным и открытым программным обеспечением. Тем самым
*  давая пользователю право на распространение и (или) модификацию данного Web-приложения (а также
*  и иные права) согласно условиям GNU General Public License, опубликованной
*  Free Software Foundation, версии 3.

*    В случае отсутствия файла «License» (идущего вместе с исходными кодами программного обеспечения)
*  описывающего условия GNU General Public License версии 3, можно посетить официальный сайт
*  http://www.gnu.org/licenses/ , где опубликованы условия GNU General Public License
*  различных версий (в том числе и версии 3).

*  | ENG | - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

*    "Komunikator" is a web interface for IP-PBX "YATE" configuration and management
*    Copyright (C) 2012-2013, "Telephonnyie sistemy" Ltd.

*    THIS FILE is an integral part of the project "Komunikator"

*    "Komunikator" project site: http://4yate.ru/
*    "Komunikator" technical support e-mail: support@4yate.ru

*    The project "Komunikator" are used:
*      the source code of "YATE" project, http://yate.null.ro/pmwiki/
*      the source code of "FREESENTRAL" project, http://www.freesentral.com/
*      "Sencha Ext JS" project libraries, http://www.sencha.com/products/extjs

*    "Komunikator" web application is a free/libre and open-source software. Therefore it grants user rights
*  for distribution and (or) modification (including other rights) of this programming solution according
*  to GNU General Public License terms and conditions published by Free Software Foundation in version 3.

*    In case the file "License" that describes GNU General Public License terms and conditions,
*  version 3, is missing (initially goes with software source code), you can visit the official site
*  http://www.gnu.org/licenses/ and find terms specified in appropriate GNU General Public License
*  version (version 3 as well).

*  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
*/

?><?php

function getValueFromNtnSettings($param, $default) {
    $query = "SELECT value FROM ntn_settings WHERE param = '$param'";
    $res = query_to_array($query);
    
    $value = $default;
    
    if (count($res)) {
        $value = $res[0]['value'];
    }
    
    return $value;
}


require_once('lib_queries.php');
require_once('lib_smtp.inc.php');

set_time_limit(600);

function debug($msg) {
    Yate::Debug('send_voicem.php: ' . $msg);
}


$filename = $argv[1];

if (!is_file($filename)) die();

$dir = dirname($filename);
$user = substr($dir, -3);

$args = basename($filename, '.mp3');
$args_arr = explode('-', $args);

if (count($args_arr) < 4) die();

$caller = $args_arr[3];
$ftime = $args_arr[1] . ' ' . $args_arr[2];

$query = "SELECT address FROM extensions WHERE extension = '$user'";
$res = query_to_array($query);

$address = $res[0]["address"];


/*
$address   - адрес получателя письма
$filename  - путь и имя аудиофайла

$caller    - телефонный номер звонившего
$ftime     - дата и время совершения вызова
*/


$sda_SUBJECT = $ftime . ' ПРОПУЩЕННЫЙ ВХ. вызов ОТ номера ' . $caller;

send_mail(getValueFromNtnSettings('from', ''), getValueFromNtnSettings('password', ''), getValueFromNtnSettings('fromname', ''), $address, $sda_SUBJECT, null, $filename);

?>