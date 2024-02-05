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

if(isset($_POST['setAdmin']))
{
    $error = 1;
    if(isset($_POST['user']) && $user->exist($_POST['user']) == 1)
        if ($admin->setAdmin($_POST['user'], ($user->isAdmin($_POST['user'])) == 1 ? 0 : 1) == 0)
            $error = 0;
}

$page_id = 1;
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MovieDiary - Admin</title>

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
                <h2>Aggiungi o rimuovi amministratori</h2>
                <?php if($error == 1): ?>
                <p class="result">Si è verificato un errore</p>
                <?php endif ?>
                <form method="post" class="settings" autocomplete="off">
                    <label for="user">Nome Utente:</label>
                    <input id="user" name="user" type="text" required>
                    <input name="setAdmin" type="submit" value="Invia">
                </form>

                <h2>
                    Lista degli amministratori
                </h2>
                <div class="admin-container">
                    <?php
                        $admins = $admin->getAdmins();

                        if($admins->num_rows == 0)
                        {
                            echo '<div class="admin"><p>Nessun Amministratore</p></div>';
                        } else {
                            while($row = $admins->fetch_assoc())
                            {
                                echo '<div class="admin"><p>'.$row['username'].'</p></div>';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php';?>
    </body>
</html>