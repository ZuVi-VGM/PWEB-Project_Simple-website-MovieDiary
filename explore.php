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

$page_id = 2;

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MovieDiary - Esplora</title>

        <!-- CSS STYLESHEET -->
        <link rel="stylesheet" href="./css/default.css" type="text/css"/>

        <!-- JS UTILITY -->
        <script src="./js/ajax/ajaxManager.js"></script>
        <script src="./js/ajax/movieManager.js?3"></script>
        <script src="./js/ajax/DOMManager.js?124"></script>

    </head>
    <body onload="movieManager.loadExplore(10, 10)">
    <?php include DIR_LAYOUT.'nav.php'; ?>

    <section id="search" class="content">
        <h2>Seleziona un film da modificare</h2>
        <div id="search-box">
            <form class="search_box" autocomplete="off" onsubmit="movieManager.researchSubmit(this.elements['search'].value); return false;">
                <input name="search" placeholder="Cerca" class="search-text" type="text" onkeyup="movieManager.search(this.value);">
                <input class="search-button icon" type="submit" value="&#128269;">
            </form>

        </div>
        <div id="search-result" class="search-result">

        </div>
    </section>

    <section id="favorite" class="content">
        <h2>I tuoi preferiti</h2>
        <div id="favorite_movies" class="movies_container">
            <!-- LOADED WITH AJAX -->

        </div>
        <a class="slider_button left" onclick="DOMManager.scroll('favorite_movies', -document.body.clientWidth)">&#10094;</a>
        <a class="slider_button right" onclick="DOMManager.scroll('favorite_movies', document.body.clientWidth)">&#10095;</a>
    </section>

    <section id="watched" class="content">
        <h2>Film che hai guardato</h2>
        <div id="watched_movies" class="movies_container">
            <!-- AJAX -->

        </div>
        <a class="slider_button left" onclick="DOMManager.scroll('watched_movies', -document.body.clientWidth)">&#10094;</a>
        <a class="slider_button right" onclick="DOMManager.scroll('watched_movies', document.body.clientWidth)">&#10095;</a>
    </section>
    <?php include DIR_LAYOUT.'footer.php';?>
    </body>
</html>