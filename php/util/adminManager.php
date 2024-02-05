<?php

class adminManager
{
    private $connection;

    function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getAdmins()
    {
        $this->connection->open();

        $admins = $this->connection->query("SELECT username
                                            FROM user
                                            WHERE admin = '1'");

        $this->connection->close();
        return $admins;
    }

    public function setAdmin($user, $value)
    {
        $this->connection->open();
        $value = $this->connection->sqlInjectionFilter($value);
        $user = $this->connection->sqlInjectionFilter($user);

        $update = $this->connection->query("UPDATE user
                                            SET admin = '$value'
                                            WHERE username = '$user'");

        $this->connection->close();

        return ($update) ? 0 : 500;
    }

    public function insertMovie($title, $description, $image)
    {
        $this->connection->open();
        $title = utf8_decode((strip_tags($title)));
        $description = utf8_decode(strip_tags($description));
        $image = strip_tags($image);
        $params = $this->connection->sqlInjectionFilter([$title, $description, $image]);

        $insert = $this->connection->query("INSERT
                                            INTO movie
                                            (title, description, image)
                                            VALUES
                                            ('$params[0]', '$params[1]', '$params[2]')");

        $this->connection->close();
        return ($insert) ? 0 : 500;
    }

    public function updateMovie($id, $title, $description, $image)
    {
        $this->connection->open();
        $title = utf8_decode((strip_tags($title)));
        $description = utf8_decode(strip_tags($description));
        $image = strip_tags($image);
        $params = $this->connection->sqlInjectionFilter([$id, $title, $description, $image]);

        $update = $this->connection->query("UPDATE movie
                                            SET title = '$params[1]', description = '$params[2]', image = '$params[3]'
                                            WHERE id = '$params[0]'");

        $this->connection->close();

        return ($update) ? 0 : 500;
    }

    public function deleteMovie($id)
    {
        $this->connection->open();
        $id = $this->connection->sqlInjectionFilter($id);

        $update = $this->connection->query("DELETE
                                            FROM movie
                                            WHERE id = '$id'");

        $this->connection->close();
        return ($update) ? 0 : 500;
    }
}