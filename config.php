<?php

$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));

foreach($cleardb_url as $value){
    echo $value . "<br>";
}

$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);

$active_group = 'default';
$query_builder = TRUE;

