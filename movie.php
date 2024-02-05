<?php
/**
 * Created by PhpStorm.
 * User: vitog
 * Date: 26/12/2019
 * Time: 19:33
 */

require_once __DIR__ . "/php/config.php";
require_once DIR_UTIL . "/sessionManager.php";

//CONTROLLO LO STATO DELL'UTENTE ED IN CASO REDIRECTO
$session = new sessionManager;

if(!$session->validate() || !isset($_GET['id']))
{
    header("location: ./");
    exit;
}

$page_id = 0;
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MovieDiary - Scheda</title>

        <!-- CSS STYLESHEET -->
        <link rel="stylesheet" href="./css/default.css" type="text/css"/>

        <!-- JS UTILITY -->
        <script src="./js/ajax/ajaxManager.js"></script>
        <script src="./js/ajax/movieManager.js?2"></script>
        <script src="./js/ajax/DOMManager.js?2"></script>

    </head>
    <body onload="movieManager.loadMovie('<?php echo $_GET['id'];?>');">
    <?php include DIR_LAYOUT.'nav.php'; ?>

    <section id="movie-card" class="content">
        <div class="movie-card-container">
            <figure id="poster" class="poster">

            </figure>
            <div id="movie-description" class="movie-description">

            </div>
        </div>
    </section>

    <section id="comments">
        <div class="comments-container">
            <div id="comments-block" class="comments-block">
                <h2>Commenti</h2>

            </div>

            <div class="create-comment">
                <form method="post" onsubmit="movieManager.insertComment('<?php echo $_GET['id']; ?>', this.elements['comment-text'].value); return false;" autocomplete="off">
                    <label for="comment-text">Inserisci un commento:</label>
                    <textarea id="comment-text" name="comment-text" maxlength="750"  minlength="10" required></textarea>
                    <input type="submit" value="Invia">
                </form>
            </div>
        </div>
    </section>

    <?php include DIR_LAYOUT.'footer.php';?>
    </body>
</html>

