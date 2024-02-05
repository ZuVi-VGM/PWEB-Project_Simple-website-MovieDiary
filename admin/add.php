<?php

require_once __DIR__ . "/../php/config.php";
require_once DIR_UTIL . "/sessionManager.php";
require_once DIR_UTIL ."/dbManager.php";
require_once DIR_UTIL ."/userManager.php";
require_once DIR_UTIL ."/adminManager.php";

//CONTROLLO LO STATO DELL'UTENTE ED IN CASO REDIRECTO
$session = new sessionManager;
$db = new dbManager;
$user = new userManager($db);
$admin = new adminManager($db);

if (!$session->validate() || $user->isadmin($_SESSION['user']) != 1) {
    header("location: ../index.php");
    exit;
}

$error = 0;

if(isset($_POST['insertMovie']))
{
    $error = 1;
    if(isset($_POST['title']) && $_POST['title'] != '' && isset($_POST['description'])  && $_POST['description'] != '' && isset($_POST['image']) && $_POST['image'] != '')
        if ($admin->insertMovie($_POST['title'], $_POST['description'], $_POST['image']) == 0)
            $error = 2;
}

$page_id = 2;
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MovieDiary - Inserisci Film</title>

        <!-- CSS STYLESHEET -->
        <link rel="stylesheet" href="../css/admin.css" type="text/css"/>

        <!-- JS UTILITY -->
        <script src="../js/ajax/DOMManager.js"></script>
    </head>
    <body>
    <?php include 'nav.php';?>

    <section id="content" class="content">
        <div class="container">
            <div class="container-box">
                <h2>Inserisci un nuovo film</h2>
                <?php if($error == 1): ?>
                    <p class="result">Si è verificato un errore</p>
                <?php elseif($error == 2):?>
                    <p class="result">Film aggiunto con successo</p>
                <?php endif ?>
                <form method="POST" class="settings" autocomplete="off">
                    <label for="titolo">Titolo:</label>
                    <input name="title" id="titolo" type="text" required>
                    <label for="locandina">Locandina:</label>
                    <input name="image" id="locandina" type="text" required>
                    <label for="text">Inserisci la descrizione:</label>
                    <textarea name="description" id="text" minlength="10" required></textarea>
                    <input name="insertMovie" value="Inserisci" type="submit">
                </form>
            </div>
        </div>
    </section>

    <?php include 'footer.php';?>
    </body>
</html>

