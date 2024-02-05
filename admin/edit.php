<?php

require_once __DIR__ . "/../php/config.php";
require_once DIR_UTIL . "/sessionManager.php";
require_once DIR_UTIL ."/dbManager.php";
require_once DIR_UTIL ."/userManager.php";
require_once DIR_UTIL ."/adminManager.php";
require_once DIR_UTIL ."/movieManager.php";

//CONTROLLO LO STATO DELL'UTENTE ED IN CASO REDIRECTO
$session = new sessionManager;
$db = new dbManager;
$user = new userManager($db);
$admin = new adminManager($db);
$movie = new movieManager($db);

if (!$session->validate() || $user->isadmin($_SESSION['user']) != 1) {
    header("location: ../index.php");
    exit;
}

$error = 0;

if(isset($_POST['updateMovie']))
{
    $error = 1;
    if($movie->exist($_POST['id']) && isset($_POST['title']) && $_POST['title'] != '' && isset($_POST['description'])  && $_POST['description'] != '' && isset($_POST['image']) && $_POST['image'] != '')
        if ($admin->updateMovie($_POST['id'], $_POST['title'], $_POST['description'], $_POST['image']) == 0)
            $error = 2;
} elseif(isset($_GET['deleteMovie'])) {
    $error = 1;
    if($movie->exist($_GET['deleteMovie']))
        if ($admin->deleteMovie($_GET['deleteMovie']) == 0)
            $error = 0;
} elseif(isset($_GET['getFilm'])) {
    $error = 1;
    if($movie->exist($_GET['getFilm']))
    {
        $data = $movie->getMovieById($_GET['getFilm']);
        if ($data != 1)
            $error = 0;
    }
}

$page_id = 3;
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MovieDiary - Gestisci Film</title>

        <!-- CSS STYLESHEET -->
        <link rel="stylesheet" href="../css/admin.css" type="text/css"/>

        <!-- JS UTILITY -->
        <script src="../js/ajax/ajaxManager.js"></script>
        <script src="./js/ajax/movieManager.js?1"></script>
        <script src="../js/ajax/DOMManager.js?1"></script>
    </head>
    <body>
    <?php include 'nav.php';?>

    <section id="content" class="content">
        <div class="container">
            <div class="container-box">
            <?php if($error == 1): ?>
                <p class="result">Si è verificato un errore</p>
            <?php elseif($error == 2): ?>
                <p class="result">Film aggiornato con successo</p>
            <?php endif ?>
            <h2>Ricerca</h2>
                <div id="search-box" class="search_box">
                    <input autocomplete="off" id="searchQuery" name="searchQuery" placeholder="Cerca" class="search-text" type="text" onkeyup="movieManager.adminSearch(this.value);">
                </div>
                <div id="search-result" class="search-result">
                </div>

                <?php if(isset($data) && $error == 0): ?>
                <h2>Modifica Film <a class="red icon" href="?deleteMovie=<?php echo $data['id'];?>">&#128465;</a></h2>
                <form method="post" action="edit.php" class="settings">
                    <input name="id" type="hidden" value="<?php echo $data['id'];?>">
                    <label for="titolo">Titolo: </label>
                    <input name="title" id="titolo" type="text" value="<?php echo utf8_encode($data['title']); ?>">
                    <label for="locandina">Locandina:</label>
                    <input name="image" id="locandina" type="text" value="<?php echo $data['image']; ?>">
                    <label for="text">Inserisci la descrizione:</label>
                    <textarea id="text" name="description"><?php echo utf8_encode($data['description']); ?></textarea>
                    <input name="updateMovie" type="submit" value="Aggiorna">
                </form>

                <?php endif ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php';?>
    </body>
</html>