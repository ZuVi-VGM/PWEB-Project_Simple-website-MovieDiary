<?php

class userManager
{
    private $connection;
    function __construct($connection)
    {
        $this->connection = $connection;
    }

    //0 nessun errore, 1 password errata, 404 utente non trovato
    public function login($username, $password)
    {
        $this->connection->open();

        //filtro i dati in input
        $username = $this->connection->sqlInjectionFilter($username);

        //recupero i dati
        $user = $this->connection->query("  SELECT username, password
                                    FROM user
                                    WHERE username = '$username'");

        //success
        $result = 0;

        if($user->num_rows != 0)
        {
            $user = $user->fetch_assoc();
            if (!$this->validate($password, $user['password']))
                $result = 1; //password errata
        } else {
            //utente non esiste
            $result = 404;
        }

        $this->connection->close();
        return $result;
    }

    //0 nessun errore, 1 utente già registrato, 2 password non corrispondono, 500 errore generico
    public function register($username, $password, $password_repeat, $email)
    {
        //controllo che l'utente non sia già registrato, altrimenti 1
        if($this->exist($username))
            return 1;

        //controllo che le password corrispondano, altrimenti 2
        if($password != $password_repeat)
            return 2;

        //hashing
        $password = $this->hash($password);

        //filtro i dati da mandare in ingresso
        $this->connection->open();
        $params = $this->connection->sqlInjectionFilter([$username, $password, $email]);

        //registro il nuovo utente
        $result = $this->connection->query("INSERT
                                        INTO user (username, password, email) 
                                        VALUES ('$params[0]', '$params[1]', '$params[2]')");

        $this->connection->close();
        return ($result) ? 0 : 500;
    }

    //0 nessun errore, 1 password non corrispondono, 2 password errata, 500 errore generico
    public function updatePassword($username, $password, $new_password, $password_repeat)
    {
        //Controllo se le password corrispondono, altrimenti 1
        if($new_password != $password_repeat)
            return 1;

        //controllo se la password è corretta, altrimenti 2
        if($this->login($username, $password) != 0)
            return 2;

        //effettuo l'aggiornamento
        $this->connection->open();
        $new_password = $this->hash($new_password);
        $params = $this->connection->sqlInjectionFilter([$username, $new_password]);

        $result = $this->connection->query("UPDATE user
                                            SET password = '$params[1]'
                                            WHERE username = '$params[0]'");

        $this->connection->close();
        return ($result) ? 0 : 500;
    }

    public function updateMail($username, $password, $mail)
    {
        //controllo se la password è corretta, altrimenti 1
        if($this->login($username, $password) != 0)
            return 1;

        //effettuo l'aggiornamento
        $this->connection->open();
        $params = $this->connection->sqlInjectionFilter([$username, $mail]);

        $result = $this->connection->query("UPDATE user
                                            SET email = '$params[1]'
                                            WHERE username = '$params[0]'");

        $this->connection->close();
        return ($result) ? 0 : 500;
    }

    public function exist($username)
    {
        $this->connection->open();

        $username = $this->connection->sqlInjectionFilter($username);

        $result = $this->connection->query("SELECT username
                                            FROM user
                                            WHERE username = '$username'");

        $this->connection->close();
        return $result->num_rows;
    }

    public function isAdmin($username)
    {
        $this->connection->open();

        //filtro i dati in input
        $username = $this->connection->sqlInjectionFilter($username);

        //recupero i dati
        $result = $this->connection->query("SELECT admin
                                            FROM user
                                            WHERE username = '$username'");


        if($result->num_rows != 0)
            $is_admin = $result->fetch_assoc()['admin'];
        else
            $is_admin = 404; //utente non esiste

        $this->connection->close();
        return $is_admin;
    }

    private function hash($text)
    {
        return password_hash($text, PASSWORD_DEFAULT);
    }

    private function validate($text, $hash)
    {
        return password_verify($text, $hash);
    }

}