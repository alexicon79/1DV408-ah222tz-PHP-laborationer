<?php

require_once("controller/Application.php");
require_once("view/ApplicationView.php");

$application = new \controller\Application();
$htmlPage = new \view\ApplicationView();

// should return relevant html
$bodyHtml = $application->invoke();

echo $htmlPage->renderFullPage("Labb 03", $bodyHtml);
