<?php

require_once("controllers/LoginController.php");
require_once("views/ApplicationView.php");

$application = new \controller\Application();
$htmlPage = new \view\ApplicationView();

// should return relevant html
$html = $application->invoke();

echo $htmlPage->render("Labb 03", $html);