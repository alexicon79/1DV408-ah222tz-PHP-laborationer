<?php
/**
 * Laboration 1 - Uppgift 3
 * TODO: Implementera egen inloggningsmodul
 */

require_once("src/view/HTMLPage.php");
require_once("src/view/LoginForm.php");
require_once("src/model/User.php");

$loginPage = new \view\HTMLPage();
$loginForm = new \view\LoginForm();
$loginFormHTML = $loginForm->getLoginForm();

$myUser = new \model\User("Alex", "12345");

echo $loginPage->getPage("Login", "<h1>Produkter</h1><p>User: $myUser->name</p> <p>Password: $myUser->pass</p> $loginFormHTML");