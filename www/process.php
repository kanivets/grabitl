<?php

$aCookies = $_COOKIES;

$sHash = $_GET['h'];

$sParams = "hashcode: $sHash, url: $_GET[p]";

$aData = @unserialize(file_get_contents('data.txt'));
if (@!isset($aData[$sHash]))
	die('if (confirm("User is not exist\nProceed to grabitl website?")) window.location.href="http://grabitl/signup.html";');

if (!$aCookies || !isset($aCookies[$sHash]))
	die('if (confirm("Cannot find cookie for your user\nProceed to grabitl website?")) window.location.href="http://grabitl/signup.html?h='.urlencode($sHash).'";');


file_put_contents('log.txt', date('[H:i:s]: ') . $sParams . "\n", FILE_APPEND);
die('console.log("cookies: '.$aCookies.', params: '.$sParams.'");');