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

if(!$session->validate())
{
    header("location: ./");
    exit;
}

$page_id = 1;

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MovieDiary - Homepage</title>

        <!-- CSS STYLESHEET -->
        <link rel="stylesheet" href="./css/default.css" type="text/css"/>

        <!-- JS UTILITY -->
        <script src="./js/ajax/ajaxManager.js"></script>
        <script src="./js/ajax/movieManager.js?2"></script>
        <script src="./js/ajax/DOMManager.js?2"></script>

    </head>
    <body onload="movieManager.loadHomepage(10, 10)">
    <?php include DIR_LAYOUT.'nav.php'; ?>

    <section id="latest" class="content">
        <h2>Ultimi arrivati</h2>
        <div id="last_movies" class="movies_container">
            <!-- LOADED WITH AJAX -->
        </div>
        <a class="slider_button left" onclick="DOMManager.scroll('last_movies', -document.body.clientWidth)">&#10094;</a>
        <a class="slider_button right" onclick="DOMManager.scroll('last_movies', document.body.clientWidth)">&#10095;</a>
    </section>

    <section id="to_watch" class="content">
        <h2>Guarda più tardi</h2>
        <div id="watch_later" class="movies_container">
            <!-- AJAX -->
            <a href="explore.php" class="movie movielink">
            <figure class="moviefigure">
                    <img alt="Trova un film." src="./images/plus.png" class="movie_img">
                    <figcaption>Trova film da aggiungere</figcaption>
            </figure>
            </a>
        </div>
        <a class="slider_button left" onclick="DOMManager.scroll('watch_later', -document.body.clientWidth)">&#10094;</a>
        <a class="slider_button right" onclick="DOMManager.scroll('watch_later', document.body.clientWidth)">&#10095;</a>
    </section>
    <?php include DIR_LAYOUT.'footer.php';?>
    </body>
</html>