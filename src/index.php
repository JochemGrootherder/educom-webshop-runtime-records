<?php
session_start();
include "controllers/PageController.php";
include "variables.php";
UpdateAllowedPages();

$Controller = new PageController();
$Controller->ExecuteAction();