<?php
/**
 * Created by PhpStorm.
 * User: vitog
 * Date: 24/12/2019
 * Time: 16:56
 */

require_once __DIR__ . "/../config.php";
require_once DIR_UTIL . "/dbManager.php";
require_once DIR_UTIL . "/sessionManager.php";
require_once DIR_UTIL . "/userManager.php";
require_once DIR_AJAX_UTIL ."/ajaxResponse.php";

$session = new sessionManager;
$response = new ajaxResponse;

if(isset($_POST['action']))
{
    if(!$session->validate())
    {
        switch($_POST['action']) {
            case 'login' :
                login();
                break;
            case 'register':
                register();
                break;
            default:
                echo json_encode($response);
        }
    } else {
        switch($_POST['action']){
            case 'settingspw':
                settings_pw();
                break;
            case 'settingsmail':
                settings_mail();
                break;
            default:
                echo json_encode($response);
        }
    }
}  else {
    echo json_encode($response);
}

function login()
{
    global $response, $session;

    //controllo su username e password
    if($_POST['username'] != null && $_POST['password'] != null) {
        //effettuo il login dell'utente e prendo la risposta
        $userManager = new userManager(new dbManager);

        //SWITCHO I RISULTATI DI STO LOGIN
        $response->result = $userManager->login($_POST['username'], $_POST['password']);
        if($response->result == 0)
        {
            //Login valido, effettuo il login!
            $session->login($_POST['username'], $userManager->isAdmin($_POST['username']));
            $response->message = 'OK';
        } else {
            $response->message = ($response->result == 1) ? 'Password errata!' : 'L\'utente non esiste!';
        }
    } else {
        $response->result = -1;
        $response->message = 'Compila tutti i campi!';
    }
    echo json_encode($response);
}

function register()
{
    global $response, $session;

    //controllo che siano stati compilati tutti i campi
    if($_POST['username'] != null && $_POST['password'] != null && $_POST['password_repeat'] != null && $_POST['mail'] != null)
    {
        //controlli sulla validità del nome, della password e della mail
        if (!preg_match("/^[a-zA-Z0-9]*$/",$_POST['username']))
        {
            //nome
            $response->result = 3;
            $response->message = 'Username non valido. Sono permessi solo lettere e numeri.';
        } elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
            //mail
            $response->result = 4;
            $response->message = 'Indirizzo email non valido.';
        } elseif(strlen($_POST['password']) < 5) {
            //password
            $response->result = 5;
            $response->message = 'Password non valida. Deve essere lunga almeno 5 caratteri.';
        } else {
            //registro l'utente e controllo i risultati
            $userManager = new userManager(new dbManager);

            $response->result = $userManager->register($_POST['username'], $_POST['password'], $_POST['password_repeat'], $_POST['mail']);

            if($response->result == 0){
                //Registrazione valida, effettuo il login!
                $session->login($_POST['username']);
                $response->message = 'OK';
            } else {
                if($response->result != 500)
                    $response->message = ($response->result == 1) ? 'Utente già registrato' : 'Le password non coincidono.';
            }
        }


    } else {
        $response->result = -1;
        $response->message = 'Compila tutti i campi';
    }

    echo json_encode($response);
}

function settings_pw()
{
    global $response;

    //controllo se i campi sono tutti compilati
    if($_POST['password'] != null && $_POST['password_repeat'] != null && $_POST['new_password'] != null) {

        //controllo la validità della nuova password
        if(strlen($_POST['new_password']) < 5) {
            //password
            $response->result = 3;
            $response->message = 'Password non valida. Deve essere lunga almeno 5 caratteri.';
        } else {
            //Provo ad aggiornare i dati e controllo i risultati
            $userManager = new userManager(new dbManager);

            $response->result = $userManager->updatePassword($_SESSION['user'], $_POST['password'], $_POST['new_password'], $_POST['password_repeat']);

            if($response->result == 0){
                $response->message = 'OK';
            } else {
                $response->message = ($response->result == 1) ? 'Le password non corrispondono' : 'La password è errata';
            }
        }
    } else {
        $response->result = -1;
        $response->message = 'Compila tutti i campi!';
    }
    echo json_encode($response);
}

function settings_mail()
{
    global $response;

    //controllo se i campi sono tutti compilati
    if($_POST['password'] != null && $_POST['mail'] != null) {

        //controllo la validità della nuova mail
        if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
            //password
            $response->result = 2;
            $response->message = 'Indirizzo email non valido.';
        } else {
            //Provo ad aggiornare i dati e controllo i risultati
            $userManager = new userManager(new dbManager);

            $response->result = $userManager->updateMail($_SESSION['user'], $_POST['password'], $_POST['mail']);

            if($response->result == 0){
                $response->message = 'OK';
            } else {
                $response->message = 'La password è errata';
            }
        }
    } else {
        $response->result = -1;
        $response->message = 'Compila tutti i campi!';
    }
    echo json_encode($response);
}

function insertComment()
{

}