<?php
/*
 * 05/07/2014
 * Example usage of LemonAtheme.php
 *
 */

require_once('./LemonAtheme.php');

$t = new LemonAtheme();
$t->setServerInfo('127.0.0.1', 8080, '/xmlrpc');
$t->setUserIP('202.155.0.10');

// register account
//$x = $t->registerNick('test2', 'pass123', 'lemon@dodol.com');

// check account is valid or not (return true if valid, false if invalid)
$x = $t->checkAccount('test', 'pass123');

var_dump($x);
