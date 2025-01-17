<?php

/*
 *  | RUS | - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

 *    «Komunikator» – Web-интерфейс для настройки и управления программной IP-АТС «YATE»
 *    Copyright (C) 2012-2013, ООО «Телефонные системы»

 *    ЭТОТ ФАЙЛ является частью проекта «Komunikator»

 *    Сайт проекта «Komunikator»: http://komunikator.ru/
 *    Служба технической поддержки проекта «Komunikator»: E-mail: support@komunikator.ru

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

 *    "Komunikator" project site: http://komunikator.ru/
 *    "Komunikator" technical support e-mail: support@komunikator.ru

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
?><?

need_user();

$data = json_decode($HTTP_RAW_POST_DATA);
$rows = array();
$values = array();

if ($data && !is_array($data))
    $data = array($data);
foreach ($data as $row) {
    $values = array();
    foreach ($row as $key => $value)
        if ($key == 'id')
            $id = $key;
        else
        if ($key == 'button_code') {
            
        } else if ($key == 'callthrough_time') {
            $time = $value;
            $values[$key] = $value;
        }
        else
            $values[$key] = "'$value'";
    $settings = '[{"1":{"field1":"condition_return_user","field2":true,"field3":"","field4":"","field5":"Рады вас видеть снова! Мы готовы перезвонить Вам за ' . $time
            . ' секунд и ответить на все Ваши вопросы!","id":"1"}},{"2":{"field1":"condition_exit","field2":false,"field3":"","field4":"","field5":"Не нашли, что искали? Возникли вопросы? Давайте мы Вам перезвоним за ' . $time
            . ' секунд.","id":"2"}},{"3":{"field1":"condition_pages","field2":false,"field3":"количество страниц:","field4":"3","field5":"Не нашли, что искали? Возникли вопросы? Давайте мы Вам перезвоним за ' . $time
            . ' секунд.","id":"3"}},{"4":{"field1":"condition_time","field2":true,"field3":"Время, секунда:","field4":"40","field5":"Не нашли, что искали? Возникли вопросы? Давайте мы Вам перезвоним за ' . $time
            . ' секунд.","id":"4"}},{"5":{"field1":"condition_scroll","field2":true,"field3":"","field4":"","field5":"Не нашли, что искали? Возникли вопросы? Давайте мы Вам перезвоним за ' . $time
            . ' секунд.","id":"5"}},{"6":{"field1":"condition_certain_page","field2":true,"field3":"URL:","field4":"http://example.ru/","field5":"Интересуетесь ХХХ? Давайте мы Вам перезвоним за ' . $time
            . ' секунд и расскажем подробнее!","id":"6"}},{"7":{"field1":"color","field2":false,"field3":"","field4":"3399FF","field5":"","id":"7"}},{"8":{"field1":"color_after","field2":false,"field3":"","field4":"FF8519","field5":"","id":"8"}},'.
            '{"9":{"field1":"condition_prefix","field2":false,"field3":"","field4":"001","field5":"","id":"9"}}]';

    $val = addslashes($settings);
    $values['settings'] = "'$val'";
    $rows[] = $values;
}

require_once("create.php");
?>