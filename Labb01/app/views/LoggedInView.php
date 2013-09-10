<?php
require_once("models/LoginModel.php");
$model = new  \model\LoginModel();
$currentUser = $model->getValidUser();

echo "
<h1>$currentUser är inloggad</h1>
<p>Inloggning lyckades</p>
<p><a href='?logout'>Logga ut</a></p>
";