#  | RUS | - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

#    «Komunikator» – Web-интерфейс для настройки и управления программной IP-АТС «YATE»
#    Copyright (C) 2012-2013, ООО «Телефонные системы»

#    ЭТОТ ФАЙЛ является частью проекта «Komunikator»

#    Сайт проекта «Komunikator»: http://4yate.ru/
#    Служба технической поддержки проекта «Komunikator»: E-mail: support@4yate.ru

#    В проекте «Komunikator» используются:
#      исходные коды проекта «YATE», http://yate.null.ro/pmwiki/
#      исходные коды проекта «FREESENTRAL», http://www.freesentral.com/
#      библиотеки проекта «Sencha Ext JS», http://www.sencha.com/products/extjs

#    Web-приложение «Komunikator» является свободным и открытым программным обеспечением. Тем самым
#  давая пользователю право на распространение и (или) модификацию данного Web-приложения (а также
#  и иные права) согласно условиям GNU General Public License, опубликованной
#  Free Software Foundation, версии 3.

#    В случае отсутствия файла «License» (идущего вместе с исходными кодами программного обеспечения)
#  описывающего условия GNU General Public License версии 3, можно посетить официальный сайт
#  http://www.gnu.org/licenses/ , где опубликованы условия GNU General Public License
#  различных версий (в том числе и версии 3).

#  | ENG | - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

#    "Komunikator" is a web interface for IP-PBX "YATE" configuration and management
#    Copyright (C) 2012-2013, "Telephonnyie sistemy" Ltd.

#    THIS FILE is an integral part of the project "Komunikator"

#    "Komunikator" project site: http://4yate.ru/
#    "Komunikator" technical support e-mail: support@4yate.ru

#    The project "Komunikator" are used:
#      the source code of "YATE" project, http://yate.null.ro/pmwiki/
#      the source code of "FREESENTRAL" project, http://www.freesentral.com/
#      "Sencha Ext JS" project libraries, http://www.sencha.com/products/extjs

#    "Komunikator" web application is a free/libre and open-source software. Therefore it grants user rights
#  for distribution and (or) modification (including other rights) of this programming solution according
#  to GNU General Public License terms and conditions published by Free Software Foundation in version 3.

#    In case the file "License" that describes GNU General Public License terms and conditions,
#  version 3, is missing (initially goes with software source code), you can visit the official site
#  http://www.gnu.org/licenses/ and find terms specified in appropriate GNU General Public License
#  version (version 3 as well).

#  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

#!/bin/bash
data_file='data.tar.gz'

name=komunikator.`date +%s`
temp_path="/tmp/$name"
rm $temp_path -r -f
mkdir $temp_path
data_path=$temp_path/data
mkdir $data_path

p=`pwd`
cd $temp_path
#DB dump
mysqldump -u root -proot kommunikator > $temp_path/DB.sql

#cp data file
source="/var/lib"
mkdir -p $data_path$source
cp -R $source/misc $data_path$source

sqlite_db='/etc/webrtc2sip'
mkdir -p $data_path$sqlite_db
cp $sqlite_db/*.db $data_path$sqlite_db

#tar
rm -f $data_file
tar --exclude-caches -zcvf $p/$data_file .
cd $p
rm $temp_path -r -f
