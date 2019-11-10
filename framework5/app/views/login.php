<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <link rel="stylesheet" type="text/css" media="screen" href="public/css/main.css" />
</head>
<body>
<?php
    include "menu.php";
?>
<div id="content">
    <div id="loginForm">
        <form name="login" action="<?= $config['site']['root']?>/compruebaLogin" method="post">
            <input type="text" class="inputAuth" name="user" placeholder="Nombre de usuario" required/>
            <input type="password" class="inputAuth" name="password" placeholder="Password" required/>
            <button id="authButton" type="submit">OK</button>
        </form>
    </div>
</div>    
</body>
</html>