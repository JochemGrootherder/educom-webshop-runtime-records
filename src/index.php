<?php
session_start();
include __DIR__."/controllers/PageController.php";
include __DIR__."/variables.php";
UpdateAllowedPages();

$Controller = new PageController();
$Controller->ExecuteAction();