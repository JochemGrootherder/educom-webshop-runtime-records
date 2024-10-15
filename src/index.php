<?php
session_start();
include __DIR__."/controllers/PageController.php";
include __DIR__."/variables.php";
UpdateVariables();

$Controller = new PageController();
$Controller->ExecuteAction();