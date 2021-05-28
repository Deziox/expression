<?php
$cfg['blowfish_secret'] = $_ENV["PHPMYADMIN_BLOWFISH_SECRET"];

$i = 0;
$i++;

$cfg['Servers'][$i]['auth_type'] = 'cookie';
$db_url = parse_url(getenv("JAWSDB_URL"));
$hostname = $db_url["host"];
$dbuser = $db_url["user"];
$dbpass = $db_url["pass"];
$database = ltrim($db_url['path'],'/');

$cfg['Servers'][$i]['host'] = $hostname;
$cfg['Servers'][$i]['user'] = $dbuser;
$cfg['Servers'][$i]['pass'] = $dbpass;
$cfg['Servers'][$i]['database'] = $database;
$cfg['Servers'][$i]['compress'] = false;
$cfg['Servers'][$i]['AllowNoPassword'] = true;