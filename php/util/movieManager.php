<?php
/**
 * Created by PhpStorm.
 * User: vitog
 * Date: 30/12/2019
 * Time: 21:07
 */

class movieManager
{
    private $connection;

    function __construct($connection){
        $this->connection = $connection;
    }

    public function getLatest($n)
    {
        $this->connection->open();

        $n = $this->connection->sqlInjectionFilter($n);

        $movies = $this->connection->query("SELECT id, title, description, image
                                            FROM movie
                                            ORDER BY added DESC
                                            LIMIT $n");

        $this->connection->close();

        if($movies->num_rows == 0)
            return 1;

        return $movies;
    }

    public function getMovieById($id)
    {
        $this->connection->open();

        $id = $this->connection->sqlInjectionFilter($id);

        $movies = $this->connection->query("SELECT id, title, description, image
                                            FROM movie
                                            WHERE id = '$id'");

        $this->connection->close();

        if($movies->num_rows == 0)
            return 1;

        return $movies->fetch_assoc();
    }

    public function getUserMovies($username, $watch_later = 1, $n = 10, $watched = 0)
    {
        $this->connection->open();
        $n = $this->connection->sqlInjectionFilter($n);
        $username = $this->connection->sqlInjectionFilter($username);
        $watched = $this->connection->sqlInjectionFilter($watched);
        $watch_later = $this->connection->sqlInjectionFilter($watch_later);

        $movies = $this->connection->query("SELECT m.id, m.title, m.description, m.image 
                                            FROM movie m
                                            INNER JOIN (SELECT movie_id, added
                                                        FROM user_movies
                                                        WHERE user = '$username' AND watched = '$watched' AND watch_later = '$watch_later') as tw
                                            ON m.id =  tw.movie_id 
                                            ORDER BY tw.added DESC
                                            LIMIT $n");

        $this->connection->close();

        if($movies->num_rows == 0)
            return 1;

        return $movies;
    }

    public function getWatchedMovies($username,  $n = 10)
    {
        $this->connection->open();
        $n = $this->connection->sqlInjectionFilter($n);
        $username = $this->connection->sqlInjectionFilter($username);

        $movies = $this->connection->query("SELECT m.id, m.title, m.description, m.image 
                                            FROM movie m
                                            INNER JOIN (SELECT movie_id, added
                                                        FROM user_movies
                                                        WHERE user = '$username' AND watched = '1') as tw
                                            ON m.id =  tw.movie_id 
                                            ORDER BY tw.added DESC
                                            LIMIT $n");

        $this->connection->close();

        if($movies->num_rows == 0)
            return 1;

        return $movies;
    }

    public function getFavorite($username,  $n = 10)
    {
        $this->connection->open();
        $n = $this->connection->sqlInjectionFilter($n);
        $username = $this->connection->sqlInjectionFilter($username);

        $movies = $this->connection->query("SELECT m.id, m.title, m.description, m.image 
                                            FROM movie m
                                            INNER JOIN (SELECT movie_id, added
                                                        FROM user_movies
                                                        WHERE user = '$username' AND watched = '1' AND favorite = '1') as tw
                                            ON m.id =  tw.movie_id 
                                            ORDER BY tw.added DESC
                                            LIMIT $n");

        $this->connection->close();

        if($movies->num_rows == 0)
            return 1;

        return $movies;
    }

    public function exist($id)
    {
        $this->connection->open();
        $id = $this->connection->sqlInjectionFilter($id);

        $exist = $this->connection->query("SELECT *
                                            FROM movie
                                            WHERE id = '$id'");
        $this->connection->close();

        return $exist->num_rows;
    }

    public function getMovieCard($id, $user)
    {
       $this->connection->open();
       $id = $this->connection->sqlInjectionFilter($id);
       $user = $this->connection->sqlInjectionFilter($user);

       $result = $this->connection->query("SELECT m.id, m.title, m.description, m.image, IF(ISNULL(um.watch_later), 0, um.watch_later) as watch_later, IF(ISNULL(um.watched), 0, um.watched) as watched, IF(ISNULL(um.favorite), 0, um.favorite) as favorite, IF(ISNULL(um.vote), 0, um.vote) as vote, (SELECT IFNULL(FLOOR(AVG(vote)), 'Nessun voto') FROM user_movies WHERE movie_id = '$id' AND watched = '1' AND vote > 0) as avg
                                            FROM movie m 
                                            LEFT OUTER JOIN 
                                            (SELECT * FROM user_movies WHERE movie_id = '$id' AND user = '$user') as um 
                                            ON m.id = um.movie_id WHERE m.id = '$id'");

       $this->connection->close();

        if($result->num_rows == 0)
            return 1;

        return $result;
    }

    public function getMovieComments($id)
    {
        $this->connection->open();
        $id = $this->connection->sqlInjectionFilter($id);

        $result = $this->connection->query("SELECT *
                                            FROM movie_comments
                                            WHERE movie_id = '$id'");

        $this->connection->close();

        if($result->num_rows == 0)
            return 1;

        return $result;
    }

    public function getCommentUser($id)
    {
        $this->connection->open();
        $id = $this->connection->sqlInjectionFilter($id);

        $result = $this->connection->query("SELECT user
                                            FROM movie_comments
                                            WHERE id = '$id'");

        $this->connection->close();

        if($result->num_rows > 0)
        {
            return $result->fetch_assoc()['user'];
        } else {
            return -1;
        }
    }

    public function insertComment($id, $user, $text)
    {
        $this->connection->open();
        $text = strip_tags($text);
        $text = utf8_decode($text);
        $params = $this->connection->sqlInjectionFilter([$id, $user, $text]);

        $insert = $this->connection->query("INSERT
                                            INTO movie_comments
                                            (movie_id, user, text)
                                            VALUES
                                            ('$params[0]', '$params[1]', '$params[2]')");

        $this->connection->close();
        return ($insert) ? 0 : 500;
    }

    public function deleteComment($id)
    {
        $this->connection->open();
        $id = $this->connection->sqlInjectionFilter($id);

        $update = $this->connection->query("DELETE
                                            FROM movie_comments
                                            WHERE id = '$id'");

        $this->connection->close();
        return ($update) ? 0 : 500;
    }

    public function setWatchLater($id, $user)
    {
        $this->connection->open();
        $params = $this->connection->sqlInjectionFilter([$id, $user]);

        $result = $this->connection->query("SELECT *
                                            FROM user_movies
                                            WHERE movie_id = '$params[0]' AND user = '$params[1]'");

        if($result->num_rows == 0)
        {
            $insert = $this->connection->query("INSERT 
                                                INTO user_movies
                                                (user, movie_id, watch_later)
                                                VALUES ('$params[1]', '$params[0]', '1')");
            $this->connection->close();
            return ($insert) ? 0 : 500;
        } else {
            $update = $this->connection->query("UPDATE user_movies
                                                SET watch_later = '1'
                                                WHERE user = '$params[1]' AND movie_id = '$params[0]'");

            $this->connection->close();
            return ($update) ? 0 : 500;
        }
    }

    public function unsetWatchLater($id, $user)
    {
        $this->connection->open();
        $params = $this->connection->sqlInjectionFilter([$id, $user]);

        $update = $this->connection->query("UPDATE user_movies
                                            SET watch_later = '0'
                                            WHERE user = '$params[1]' AND movie_id = '$params[0]'");

        $this->connection->close();
        return ($update) ? 0 : 500;
    }

    public function setWatched($id, $user)
    {
        $this->connection->open();
        $params = $this->connection->sqlInjectionFilter([$id, $user]);

        $result = $this->connection->query("SELECT *
                                            FROM user_movies
                                            WHERE movie_id = '$params[0]' AND user = '$params[1]'");

        if($result->num_rows == 0)
        {
            $insert = $this->connection->query("INSERT 
                                                INTO user_movies
                                                (user, movie_id, watched)
                                                VALUES ('$params[1]', '$params[0]', '1')");
            $this->connection->close();
            return ($insert) ? 0 : 500;
        } else {
            $update = $this->connection->query("UPDATE user_movies
                                                SET watched = '1'
                                                WHERE user = '$params[1]' AND movie_id = '$params[0]'");

            $this->connection->close();
            return ($update) ? 0 : 500;
        }
    }

    public function unsetWatched($id, $user)
    {
        $this->connection->open();
        $params = $this->connection->sqlInjectionFilter([$id, $user]);

        $update = $this->connection->query("UPDATE user_movies
                                            SET watched = '0'
                                            WHERE user = '$params[1]' AND movie_id = '$params[0]'");

        $this->connection->close();
        return ($update) ? 0 : 500;
    }

    public function setVote($id, $user, $vote)
    {
        $this->connection->open();
        $params = $this->connection->sqlInjectionFilter([$id, $user, $vote]);

        $update = $this->connection->query("UPDATE user_movies
                                            SET vote = '$params[2]'
                                            WHERE user = '$params[1]' AND movie_id = '$params[0]'");

        $this->connection->close();
        return ($update) ? 0 : 500;
    }

    public function setFavorite($id, $user, $favorite)
    {
        $this->connection->open();
        $params = $this->connection->sqlInjectionFilter([$id, $user, $favorite]);

        $update = $this->connection->query("UPDATE user_movies
                                            SET favorite = '$params[2]'
                                            WHERE user = '$params[1]' AND movie_id = '$params[0]'");

        $this->connection->close();
        return ($update) ? 0 : 500;
    }

    public function search($movie)
    {
        $this->connection->open();
        $movie = $this->connection->sqlInjectionFilter($movie);

        $result = $this->connection->query("SELECT m.id, m.title, m.description, m.image
                                            FROM movie m 
                                            WHERE m.title LIKE '%$movie%'");

        $this->connection->close();

        if($result->num_rows == 0)
            return 1;

        return $result;
    }
}