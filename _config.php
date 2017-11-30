<?php

$config = [];
$config['db_host'] = 'localhost';
$config['db_user'] = 'yacms_user';
$config['db_pass'] = 'yapass'; // 'password';
$config['yacms_db'] = 'yacms_db';

$db = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['yacms_db']);
