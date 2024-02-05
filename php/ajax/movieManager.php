<?php
/**
 * Created by PhpStorm.
 * User: vitog
 * Date: 30/12/2019
 * Time: 20:57
 */

require_once __DIR__."/../config.php";
require_once DIR_UTIL."/dbManager.php";
require_once DIR_UTIL."/movieManager.php";
require_once DIR_UTIL."/sessionManager.php";
require_once DIR_UTIL."/userManager.php";
require_once DIR_AJAX_UTIL."/ajaxResponse.php";

$response = new ajaxResponse;

if(isset($_GET['request'])) {
    switch ($_GET['request']) {
        case 'getlatest':
            getLatest();
            break;
        case 'getusermovies':
            getUserMovies();
            break;
        case 'loadmovie':
            loadMovie();
            break;
        case 'loadcomments':
            loadComments();
            break;
        case 'getfavorite':
            getFavorite();
            break;
        case 'getwatchedmovies':
            getWatchedMovies();
            break;
        case 'search':
            search();
            break;
    }
} else if(isset($_POST['action'])){
    switch($_POST['action']){
        case 'setWatchLater':
            setWatchLater();
            break;
        case 'unsetWatchLater':
            unsetWatchLater();
            break;
        case 'setWatched':
            setWatched();
            break;
        case 'unsetWatched':
            unsetWatched();
            break;
        case 'setVote':
            setVote();
            break;
        case 'toggleFavorite':
            toggleFavorite();
            break;
        case 'deleteComment':
            deleteComment();
            break;
        case 'insertComment':
            insertComment();
            break;
    }
} else {
    echo json_encode($response);
}


function getLatest()
{
    global $response;

    if(isset($_GET['data']) && is_numeric($_GET['data']))
    {
        $movie = new movieManager(new dbManager);

        $result = $movie->getLatest($_GET['data']);

        if($result !== 1)
        {
           $i = 0;
           while($row = $result->fetch_assoc())
           {
               $response->data[$i] = new movie($row['id'], $row['title'], utf8_encode($row['description']), $row['image']);
               ++$i;
           }

           $response->result = 0;
           $response->message = 'SUCCESS';
        }
    }

    echo json_encode($response);
}

function getUserMovies()
{
    global $response;

    //check if session is active
    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_GET['data']) && is_numeric($_GET['data'])) {
            $movie = new movieManager(new dbManager);

            $result = $movie->getUserMovies($_SESSION['user'], 1, $_GET['data']);

            if ($result !== 1) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $response->data[$i] = new movie($row['id'], $row['title'], utf8_encode($row['description']), $row['image']);
                    ++$i;
                }

                $response->result = 0;
                $response->message = 'SUCCESS';
            }
        }
    }

    echo json_encode($response);
}

function loadMovie()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_GET['data']) && is_numeric($_GET['data'])) {
            $movie = new movieManager(new dbManager);

            //il film esiste?
            if($movie->exist($_GET['data']) > 0)
            {
                $result = $movie->getMovieCard($_GET['data'], $_SESSION['user']);

                if ($result !== 1)
                {
                    $row = $result->fetch_assoc();
                    $movie = new movie($row['id'], $row['title'], utf8_encode($row['description']), $row['image']);
                    $response->data = new movieCard($movie, $row['watch_later'], $row['favorite'], $row['avg'], $row['vote'], $row['watched']);

                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }
            }
        }
    }

    echo json_encode($response);
}

function loadComments()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_GET['data']) && is_numeric($_GET['data'])) {
            $dbman = new dbManager;
            $movie = new movieManager($dbman);

            if($movie->exist($_GET['data']) > 0)
            {
                $result = $movie->getMovieComments($_GET['data']);

                if ($result !== 1)
                {
                    $user = new userManager($dbman);
                    $response->data['sess_user'] = $_SESSION['user'];
                    $response->data['is_admin'] = $user->isadmin($_SESSION['user']);
                    $i = 0;
                    while($row = $result->fetch_assoc())
                    {
                        $response->data['comments'][$i] = new comment($row['id'], $row['user'], utf8_encode($row['text']), $row['pub_date']);
                        ++$i;
                    }

                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }   else {
                    $response->data['comments'] = [];
                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }
            }
        }
    }

    echo json_encode($response);
}

function insertComment()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['text']) && strlen($_POST['text']) <= 750 && strlen($_POST['text']) >= 10) {
            $dbman = new dbManager;
            $movie = new movieManager($dbman);
            if($movie->exist($_POST['id']) > 0)
            {
                $result = $movie->insertComment($_POST['id'], $_SESSION['user'], $_POST['text']);

                if ($result !== 1) {
                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }
            }
        }
    }

    echo json_encode($response);
}

function deleteComment()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $dbman = new dbManager;
            $movie = new movieManager($dbman);
            $user = new userManager($dbman);


            if($movie->getCommentUser($_POST['id']) == $_SESSION['user'] || $user->isAdmin($_SESSION['user']) == 1)
            {
                $result = $movie->deleteComment($_POST['id']);

                if ($result !== 1)
                {
                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }
            }
        }
    }

    echo json_encode($response);
}

function setWatchLater()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $movie = new movieManager(new dbManager);

            //il film esiste?
            if($movie->exist($_POST['id']) > 0)
            {
                $result = $movie->setWatchLater($_POST['id'], $_SESSION['user']);

                if ($result !== 1)
                {
                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }
            }
        }
    }

    echo json_encode($response);
}

function unsetWatchLater()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $movie = new movieManager(new dbManager);

            //il film esiste?
            if($movie->exist($_POST['id']) > 0)
            {
                $result = $movie->unsetWatchLater($_POST['id'], $_SESSION['user']);

                if ($result !== 1)
                {
                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }
            }
        }
    }

    echo json_encode($response);
}

function setWatched()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $movie = new movieManager(new dbManager);

            //il film esiste?
            if($movie->exist($_POST['id']) > 0)
            {
                $result = $movie->setWatched($_POST['id'], $_SESSION['user']);

                if ($result !== 1)
                {
                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }
            }
        }
    }

    echo json_encode($response);
}

function unsetWatched()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $movie = new movieManager(new dbManager);

            //il film esiste?
            if($movie->exist($_POST['id']) > 0)
            {
                $result = $movie->unsetWatched($_POST['id'], $_SESSION['user']);

                if ($result !== 1)
                {
                    $response->result = 0;
                    $response->message = 'SUCCESS';
                }
            }
        }
    }

    echo json_encode($response);
}

function setVote()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            if(isset($_POST['vote']) && is_numeric($_POST['vote']))
            {
                if($_POST['vote'] >= 0 && $_POST['vote'] <= 5)
                {
                    $movie = new movieManager(new dbManager);

                    //il film esiste?
                    if ($movie->exist($_POST['id']) > 0) {
                        $result = $movie->setVote($_POST['id'], $_SESSION['user'], $_POST['vote']);

                        if ($result !== 1) {
                            $response->result = 0;
                            $response->message = 'SUCCESS';
                        }
                    }
                }
            }
        }
    }

    echo json_encode($response);
}


function toggleFavorite()
{
    global $response;

    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            if(isset($_POST['favorite']) && is_numeric($_POST['favorite']))
            {
                $movie = new movieManager(new dbManager);

                $favorite = ($_POST['favorite'] == 1) ? 0 : 1;
                //il film esiste?
                if ($movie->exist($_POST['id']) > 0) {
                    $result = $movie->setFavorite($_POST['id'], $_SESSION['user'], $favorite);

                    if ($result !== 1) {
                        $response->result = 0;
                        $response->message = 'SUCCESS';
                    }
                }

            }
        }
    }

    echo json_encode($response);
}

function getFavorite()
{
    global $response;

    //check if session is active
    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_GET['data']) && is_numeric($_GET['data'])) {
            $movie = new movieManager(new dbManager);

            $result = $movie->getFavorite($_SESSION['user'], $_GET['data']);

            if ($result !== 1) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $response->data[$i] = new movie($row['id'], $row['title'], utf8_encode($row['description']), $row['image']);
                    ++$i;
                }

            } else {
                $response->data[0] = new movie(-1, 'Nessun film preferito', 'Nessun film guardato', './images/x.png');
            }
            $response->result = 0;
            $response->message = 'SUCCESS';
        }
    }

    echo json_encode($response);
}

function getWatchedMovies()
{
    global $response;

    //check if session is active
    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_GET['data']) && is_numeric($_GET['data'])) {
            $movie = new movieManager(new dbManager);

            $result = $movie->getWatchedMovies($_SESSION['user'], $_GET['data']);

            if ($result !== 1) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $response->data[$i] = new movie($row['id'], $row['title'], utf8_encode($row['description']), $row['image']);
                    ++$i;
                }
            } else {
                $response->data[0] = new movie(-1, 'Nessun film guardato', 'Nessun film guardato', './images/x.png');
            }
            $response->result = 0;
            $response->message = 'SUCCESS';
        }
    }

    echo json_encode($response);
}

function search()
{
    global $response;

    //check if session is active
    $session = new sessionManager;

    if($session->validate())
    {
        if (isset($_GET['data']) && $_GET['data'] != '') {
            $movie = new movieManager(new dbManager);

            $result = $movie->search($_GET['data']);

            if ($result !== 1) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $response->data[$i] = new movie($row['id'], $row['title'], utf8_encode($row['description']), $row['image']);
                    ++$i;
                }
            } else {
                $response->data[0] = new movie(-1, 'Nessun risultato', 'Nessun risultato', '');
            }
            $response->result = 0;
            $response->message = 'SUCCESS';
        }
    }

    echo json_encode($response);
}