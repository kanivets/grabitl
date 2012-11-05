<?php
$gaDB = null;
function LoadDB() {	
	global $gaDB;
	if ($gaDB == null) {	
		$gaDB = @unserialize(@file_get_contents('data.txt'));
		if (!$gaDB) $gaDB = array();	
	}	
	return $gaDB;
}

function GetUserDataFromDB($sLogin) {
	$aDB = LoadDB();
	
	return isset($aDB[$sLogin]) ? $aDB[$sLogin] : false; 
}

function SetUserDataToDB($sLogin, $aData) {	
	global $gaDB;
	$gaDB = LoadDB();	
	$gaDB[$sLogin] = $aData;
	
	$sData = @serialize($gaDB);
	file_put_contents('data.txt', $sData);
}

function IsAlreadyLogined() {
	return isset($_COOKIE['current_user']) ? true : false;
}
function GetAlreadyLogined() {
	return isset($_COOKIE['current_user']) ? $_COOKIE['current_user'] : false;
}

function IsUserCanLogin($sLogin, $sPass) {
	$aUser = GetUserDataFromDB($sLogin);
	if (!$aUser) return false;
	
	return $aUser['pass'] == $sPass;
}

function Login($sLogin, $sPass) {
	if (!IsUserCanLogin($sLogin, $sPass))
		return false;
	
	setcookie("current_user", $sLogin, time() + 60 * 60 * 24 * 5);  /* expire in 5 days */	
	return true;
}

function Logout() {	
	setcookie("current_user", '', time() - 1);  /* expire in 5 days */	
	return true;
}

function Register($sLogin, $sPass) {
	if (GetUserDataFromDB($sLogin)) return false;
	
	$aUserData = array(
		'pass' => $sPass,
		'pages' => array()
	);
	SetUserDataToDB($sLogin, $aUserData);
	
	setcookie("current_user", $sLogin, time() + 60 * 60 * 24 * 5);  /* expire in 5 days */	
	return true;
}

function AddPageFromUser($sUser, $sPage, $sTitle) {
	global $gaDB;
	$gaDB = LoadDB();
	$gaDB[$sUser]['pages'][] = array('url' => $sPage, 'title' => $sTitle, 'time' => date('d M Y, H:i:s'));
	$sData = @serialize($gaDB);	
	file_put_contents('data.txt', $sData);
}

function DeletePage($sUser, $nPageIndex) {
	global $gaDB;
	$gaDB = LoadDB();
	unset($gaDB[$sUser]['pages'][$nPageIndex]);
	$sData = @serialize($gaDB);	
	file_put_contents('data.txt', $sData);
}