<?php
require_once __DIR__ . "/php/config.php";
require_once DIR_UTIL . "/sessionManager.php";

//CONTROLLO LO STATO DELL'UTENTE ED IN CASO REDIRECTO
$session = new sessionManager;

if($session->validate())
{
    header("location: home.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MovieDiary - Accedi</title>

        <!-- CSS STYLESHEET -->
        <link rel="stylesheet" href="./css/default.css" type="text/css"/>

        <!-- JS UTILITY -->
        <script src="./js/ajax/ajaxManager.js"></script>
        <script src="./js/ajax/formDriver.js?fd"></script>
        <script src="./js/ajax/movieManager.js?7"></script>
        <script src="./js/ajax/DOMManager.js"></script>
    </head>
    <body onload="movieManager.getData('getlatest', 3, movieManager.loadSlider)">
        <section id="login_content">
            <div id="logo" class="login_item">
                <h5>MovieDiary</h5>
            </div>
            <div id="login_form" class="login_item">
                <span id="login_result" class="result"></span>
                <form class="flex_form" name="login" method="post" onsubmit="formDriver.login(this.elements['username'].value, this.elements['password'].value); return false;" autocomplete="on">

                    <div class="flex_form_item">
                        <label for="login_username">Username:</label>
                        <input id="login_username" name="username" type="text" required>
                    </div>
                    <div class="flex_form_item">
                        <label for="login_password">Password:</label>
                        <input id="login_password" name="password" type="password" autocomplete="off" required>
                    </div>
                    <div class="flex_form_item">
                        <input type="submit" value="Accedi">
                    </div>
                </form>
            </div>
        </section>
        <section id="signup_content">

            <div id="latest_movies">
               <!-- LOADED WITH AJAX -->
            </div>

            <div id="registration_form">
                <h2>REGISTRATI</h2>
                <span id="reg_result" class="result"></span>

                <form name="reg" method="post" onsubmit="formDriver.register(this.elements['username'].value, this.elements['password'].value, this.elements['password_repeat'].value, this.elements['mail'].value); return false;" autocomplete="on" style="width:95%">
                    <label for="reg_username">Username:</label>
                    <input id="reg_username" name="username" type="text" required>
                    <label for="reg_password">Password:</label>
                    <input id="reg_password" name="password" type="password" autocomplete="off" required>
                    <label for="reg_pwrepeat">Ripeti la Password:</label>
                    <input id="reg_pwrepeat" name="password_repeat" type="password" autocomplete="off" required>
                    <label for="reg_mail">Email:</label>
                    <input id="reg_mail" name="mail" type="email" required>
                    <input type="submit" name="reg" value="Registrati">
                </form>
            </div>
        </section>
        <?php include DIR_LAYOUT.'footer.php';?>
    </body>
</html>