<?php
require_once __DIR__ . "/php/config.php";
require_once DIR_UTIL . "/sessionManager.php";

//CONTROLLO LO STATO DELL'UTENTE ED IN CASO REDIRECTO
$session = new sessionManager;

if(!$session->validate())
{
    header("location: ./");
    exit;
}

$page_id = 3
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MovieDiary - Impostazioni</title>

        <!-- CSS STYLESHEET -->
        <link rel="stylesheet" href="./css/default.css" type="text/css"/>

        <!-- JS UTILITY -->
        <script src="./js/ajax/ajaxManager.js"></script>
        <script src="./js/ajax/formDriver.js"></script>
        <script src="./js/ajax/DOMManager.js"></script>

    </head>
    <body>
    <?php include DIR_LAYOUT.'nav.php'; ?>

    <section id="settings" class="content">
        <div class="settings-container">
            <div class="settings-block">
                <h2>Reimposta password</h2>
                <span id="settings_pass_result" class="result"></span>

                <form class="settings" method="post" onsubmit="formDriver.settingsPassword(this.elements['password'].value, this.elements['new_password'].value, this.elements['repeat_password'].value); return false;" autocomplete="off">
                    <label for="password">Password:</label>
                    <input id="password" type="password" required>
                    <label for="new_password">Nuova Password:</label>
                    <input id="new_password" type="password" required>
                    <label for="repeat_password">Ripeti la password:</label>
                    <input id="repeat_password" type="password" required>
                    <input type="submit" value="Invia">
                </form>
            </div>
        </div>

        <div class="settings-container">
            <div class="settings-block">
                <h2>Aggiorna Mail</h2>
                <span id="settings_mail_result" class="result"></span>

                <form class="settings" method="post" onsubmit="formDriver.settingsMail(this.elements['mail'].value, this.elements['mail_password'].value); return false;" autocomplete="on">
                    <label for="mail">Nuova email:</label>
                    <input id="mail" type="email">
                    <label for="mail_password">Password:</label>
                    <input id="mail_password" type="password">
                    <input type="submit" value="Invia">
                </form>
            </div>
        </div>
    </section>

    <?php include DIR_LAYOUT.'footer.php';?>
    </body>
</html>