<?php
/**
 * Created by PhpStorm.
 * User: vitog
 * Date: 24/12/2019
 * Time: 14:31
 */

class ajaxResponse
{
    public $result;
    public $message;
    public $data;

    function __construct($result = 500, $message = "Something went wrong.", $data = null)
    {
        $this->result = $result;
        $this->message = $message;
        $this->data = $data;
    }
}

class movie
{
    public $id;
    public $title;
    public $description;
    public $image;

    function __construct($id, $title, $description, $image)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
    }
}

class movieCard
{
    public $movie;
    public $watch_later;
    public $favorite;
    public $avg;
    public $vote;
    public $watched;

    function __construct($movie, $watch_later, $favorite, $avg, $vote, $watched)
    {

       $this->movie = $movie;
       $this->watch_later = $watch_later;
       $this->favorite = $favorite;
       $this->avg = $avg;
       $this->vote = $vote;
       $this->watched = $watched;
    }
}

class comment
{
    public $id;
    public $user;
    public $text;
    public $pub_date;

    function __construct($id, $user, $text, $pub_date)
    {
        $this->id = $id;
        $this->user = $user;
        $this->text = $text;
        $this->pub_date = $pub_date;
    }
}
//Altre strutture dati