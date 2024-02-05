function movieManager(){}

movieManager.DEFAULT_METHOD = "GET";
movieManager.URL_REQUEST = "./php/ajax/movieManager.php";
movieManager.ASYNC_TYPE = true;

movieManager.RESPONSE_SUCCESS = 0;

movieManager.getData = function(type, data, responseFunction)
{
    var url = movieManager.URL_REQUEST + '?request='+type+'&data='+data;

    AjaxManager.performAjaxRequest(movieManager.DEFAULT_METHOD,
        url, movieManager.ASYNC_TYPE,
        null, responseFunction);
};

movieManager.loadSlider = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.createSlider(response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
    DOMManager.generateError();
};

movieManager.loadLatestMovies = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.createMovieList('last_movies', response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
};

movieManager.loadUserMovies = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.createMovieList('watch_later', response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
};

movieManager.loadHomepage = function(loadlatest, loadusermovies)
{
    movieManager.getData('getlatest', loadlatest, movieManager.loadLatestMovies);
    movieManager.getData('getusermovies', loadusermovies, movieManager.loadUserMovies);
};

movieManager.loadMovie = function(id)
{
    movieManager.getData('loadmovie', id, movieManager.movieHandler);
    movieManager.getData('loadcomments', id, movieManager.commentsHandler)
};

movieManager.movieHandler = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.generateCard(response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
    document.location.href = './';
};

movieManager.commentsHandler = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.loadComments(response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
    document.location.href = './';
};

movieManager.deleteComment = function(id)
{
    var data = "action=deleteComment&id="+id;
    var url = movieManager.URL_REQUEST;
    var responseFunction = movieManager.reloadMovie;

    AjaxManager.performAjaxRequest('POST',
        url, movieManager.ASYNC_TYPE,
        data, responseFunction);
};

movieManager.setWatchLater = function(id)
{
    var data = "action=setWatchLater&id="+id;
    var url = movieManager.URL_REQUEST;
    var responseFunction = movieManager.reloadMovie;

    AjaxManager.performAjaxRequest('POST',
        url, movieManager.ASYNC_TYPE,
        data, responseFunction);
};

movieManager.unsetWatchLater = function(id)
{
    var data = "action=unsetWatchLater&id="+id;
    var url = movieManager.URL_REQUEST;
    var responseFunction = movieManager.reloadMovie;

    AjaxManager.performAjaxRequest('POST',
        url, movieManager.ASYNC_TYPE,
        data, responseFunction);
};

movieManager.reloadMovie = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        document.location.reload();
        return;
    }

    console.log(response.result + ' ' + response.message);
    alert('ERRORE');
};

movieManager.setWatched = function(id)
{
    var data = "action=setWatched&id="+id;
    var url = movieManager.URL_REQUEST;
    var responseFunction = movieManager.reloadMovie;

    AjaxManager.performAjaxRequest('POST',
        url, movieManager.ASYNC_TYPE,
        data, responseFunction);
};

movieManager.unsetWatched = function(id)
{
    var data = "action=unsetWatched&id="+id;
    var url = movieManager.URL_REQUEST;
    var responseFunction = movieManager.reloadMovie;

    AjaxManager.performAjaxRequest('POST',
        url, movieManager.ASYNC_TYPE,
        data, responseFunction);
};

movieManager.setVote = function(id, vote)
{
    var data = "action=setVote&id="+id+"&vote="+vote;
    var url = movieManager.URL_REQUEST;
    var responseFunction = movieManager.reloadMovie;

    AjaxManager.performAjaxRequest('POST',
        url, movieManager.ASYNC_TYPE,
        data, responseFunction);
};

movieManager.toggleFavorite = function(id, favorite)
{
    var data = "action=toggleFavorite&id="+id+"&favorite="+favorite;
    var url = movieManager.URL_REQUEST;
    var responseFunction = movieManager.reloadMovie;

    AjaxManager.performAjaxRequest('POST',
        url, movieManager.ASYNC_TYPE,
        data, responseFunction);
};

movieManager.insertComment = function(id, text)
{
    var data = "action=insertComment&id="+id+"&text="+text;
    var url = movieManager.URL_REQUEST;
    var responseFunction = movieManager.reloadMovie;

    AjaxManager.performAjaxRequest('POST',
        url, movieManager.ASYNC_TYPE,
        data, responseFunction);
};

movieManager.loadExplore = function(loadlatest, loadusermovies)
{
    movieManager.getData('getfavorite', loadlatest, movieManager.loadFavoriteMovies);
    movieManager.getData('getwatchedmovies', loadusermovies, movieManager.loadWatchedMovies);
};

movieManager.loadFavoriteMovies = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.createMovieList('favorite_movies', response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
};

movieManager.loadWatchedMovies = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.createMovieList('watched_movies', response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
};

movieManager.search = function(data)
{
    movieManager.getData('search', data, movieManager.searchResult)
};

movieManager.searchResult = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.loadSearchResult(response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
};

movieManager.researchSubmit = function(title)
{
    movieManager.getData('search', title, movieManager.searchSubmitResult);
};

movieManager.searchSubmitResult = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        if(response.data.length > 1 || response.data[0].id === -1)
            DOMManager.loadSearchResult(response.data);
        else
            document.location.href = 'movie.php?id='+response.data[0].id;

        return;
    }

    console.log(response.result + ' ' + response.message);
};