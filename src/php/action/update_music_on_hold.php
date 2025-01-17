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

$table_name = 'music_on_hold';
$data = json_decode($HTTP_RAW_POST_DATA);
$rows = array();
$values = array();

if ($data && !is_array($data))
    $data = array($data);
foreach ($data as $row) {
    $values = array();
    foreach ($row as $key => $value)
        if ($key == 'playlist')
            $playlist = ($value == null) ? 'null' : $value;
        else {
            if ($key == 'id')
                $music_on_hold_id = $value;
            $values[$key] = "'$value'";
        }
    $rows[] = $values;
}
$id_name = 'music_on_hold_id';
if ($playlist)
    $need_out = false;
include ("update.php");

if (!$playlist)
    return;

$sql =
        <<<EOD
	SELECT playlist_item_id,g.playlist_id FROM playlist_items gm 
	left join playlists g on g.playlist = '$playlist'  
	where gm.music_on_hold_id = '$music_on_hold_id'			
EOD;

$rows = array();

$result = compact_array(query_to_array($sql));
if (!is_array($result['data']))
    echo out(array('success' => false, 'message' => $result));
$row = $result['data'][0];

if ($row) {
    $id_name = 'playlist_item_id';
    $rows[] = array('id' => $row[0], 'playlist_id' => $row[1]);
    if ($playlist != 'null') {
        $action = 'update_playlist_items';
        include ("update.php");
    } else {
        $action = 'destroy_playlist_items';
        include ("destroy.php");
    }
} else {
    $rows[] = array('music_on_hold_id' => "'$music_on_hold_id'", 'playlist_id' => " (SELECT playlist_id FROM playlists WHERE playlists.playlist = '$playlist') ");
    $action = 'create_playlist_items';
    include ("create.php");
}
?>