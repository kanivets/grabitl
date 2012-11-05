<?php

include_once 'functions.php';
$sPost = str_replace("\n", " ", var_export($_GET, true));

if (!isset($_GET['u']))
	die('if (confirm("No user id is provided...\nIf you want to use this bookmarklet, please register at GrabIt-L.\nDo you want to open GrabIt-L site (this page will be closed)?")) document.location.href="http://grabitl.akqa.pp.ua/";');

if (GetAlreadyLogined() != $_GET['u']) 
	die('if (confirm("To grab this page you need enter as \'' . $_GET['u'] . '\'...\nDo you want to open GrabIt-L site and proceed (this page will be closed)?")) document.location.href="http://grabitl.akqa.pp.ua/index.php?u='.$_GET['u'].'&p='.$_GET['p'].'&t='.$_GET['t'].'";');

AddPageFromUser($_GET['u'], $_GET['p'], $_GET['t']);
die('if (confirm("Saved!\nDo you want look at your list at GrabIt-L?")) document.location.href="http://grabitl.akqa.pp.ua/";');