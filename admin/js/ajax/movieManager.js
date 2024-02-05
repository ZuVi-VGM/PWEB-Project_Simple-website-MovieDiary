function movieManager(){}

movieManager.DEFAULT_METHOD = "GET";
movieManager.URL_REQUEST = "../php/ajax/movieManager.php";
movieManager.ASYNC_TYPE = true;

movieManager.RESPONSE_SUCCESS = 0;

movieManager.getData = function(type, data, responseFunction)
{
    var url = movieManager.URL_REQUEST + '?request='+type+'&data='+data;

    AjaxManager.performAjaxRequest(movieManager.DEFAULT_METHOD,
        url, movieManager.ASYNC_TYPE,
        null, responseFunction);
};

movieManager.adminSearch = function(title)
{
    movieManager.getData('search', title, movieManager.adminSearchResult);
};

movieManager.adminSearchResult = function(response)
{
    if (response.result === movieManager.RESPONSE_SUCCESS){
        console.log("SUCCESS");
        DOMManager.loadAdminSearchResult(response.data);
        return;
    }

    console.log(response.result + ' ' + response.message);
};