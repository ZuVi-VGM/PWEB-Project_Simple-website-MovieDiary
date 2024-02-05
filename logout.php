<?php
/**
 * Created by PhpStorm.
 * User: vitog
 * Date: 26/12/2019
 * Time: 19:35
 */

require_once __DIR__ . "/php/config.php";
require_once DIR_UTIL . "/sessionManager.php";

//CONTROLLO LO STATO DELL'UTENTE ED IN CASO REDIRECTO
$session = new sessionManager;

$session->close();
header("location: ./");