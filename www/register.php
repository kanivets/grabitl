<?php

$aData = @unserialize(file_get_contents('data.txt'));
if (!$aData) $aData = array();

if ($aData[$_GET['login']]) die('{result: 0; reason: "already existed"}');

$sHash = $_GET['login'];//sprintf("%05d", count($aData) + 1);

$aData[$sHash] = array(
	'login' => $_GET['login'],
	'pass' => md5($_GET['pass'])
);

file_put_contents('data.txt', serialize($aData));

die("{result: 1, hash: \"$sHash\"}");