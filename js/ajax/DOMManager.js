function DOMManager(){}

DOMManager.slideIndex = 1;
DOMManager.slider = null;

DOMManager.createSlider = function(data)
{
    //carico i dati ottenuti nel DOM e genero uno slider
    var container = document.getElementById('latest_movies');
    if(container === null || data === null || data.length <= 0)
        return;

    //costruisco lo slider
    var mov_slider = document.createElement('div');
    mov_slider.setAttribute('class', 'slideshow-container');

    var dots_container = document.createElement('div');
    dots_container.setAttribute('class', 'dots-container');

    for(var i = 0; i < data.length; i++)
    {
        var slide = DOMManager.addSlide(data[i], i+1, data.length);
        var dot = document.createElement('span');
        dot.setAttribute('class', 'dot');
        dot.setAttribute('onclick', 'DOMManager.currentSlide('+(i+1)+')');

        mov_slider.appendChild(slide);
        dots_container.appendChild(dot);
    }

    /* Prev button */
    var prev = document.createElement('a');
    prev.setAttribute('class', 'prev');
    prev.setAttribute('onclick', 'DOMManager.plusSlides(-1)');
    prev.textContent = "\u276E";
    mov_slider.appendChild(prev);

    /* Next button */
    var next = document.createElement('a');
    next.setAttribute('class', 'next');
    next.setAttribute('onclick', 'DOMManager.plusSlides(1)');
    next.textContent = "\u276F";
    mov_slider.appendChild(next);

    container.appendChild(mov_slider);
    container.appendChild(dots_container);

    DOMManager.startSlider();
};

DOMManager.addSlide = function(currentData, i, length)
{
    var slide = document.createElement('div');
    slide.setAttribute('class', 'mySlides fade');

    var numberText = document.createElement('div');
    numberText.setAttribute('class', 'numbertext');
    numberText.textContent = i + '/' +length;

    slide.appendChild(numberText);

    var image = new Image();
    image.alt = currentData.description;
    image.src = currentData.image;
    image.setAttribute('class', 'slider_img');

    slide.appendChild(image);

    return slide;
};

DOMManager.startSlider = function()
{
    DOMManager.showSlides();
    DOMManager.restartInterval();
};

DOMManager.restartInterval = function()
{
    DOMManager.slider = setInterval(function()
    {
        DOMManager.showSlides(DOMManager.slideIndex += 1);
    }, 5000)
};

DOMManager.plusSlides = function(n) {
    DOMManager.showSlides(DOMManager.slideIndex += n);
    clearInterval(DOMManager.slider);
    DOMManager.restartInterval();
};

DOMManager.currentSlide = function(n) {
    DOMManager.showSlides(DOMManager.slideIndex = n);
    clearInterval(DOMManager.slider);
    DOMManager.restartInterval();
};

DOMManager.showSlides = function(n = 1)
{
    //gestisco lo slider
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {DOMManager.slideIndex = 1}
    if (n < 1) {DOMManager.slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[DOMManager.slideIndex-1].style.display = "flex";
    dots[DOMManager.slideIndex-1].className += " active";
};

DOMManager.generateError = function()
{
    var container = document.getElementById('latest_movies');
    if(container === null)
        return;

    //costruisco lo slider
    var mov_slider = document.createElement('div');
    mov_slider.setAttribute('class', 'slideshow-container');

    var slide = document.createElement('div');
    slide.setAttribute('class', 'mySlides fade');
    slide.textContent = "Impossibile caricare il contenuto.";

    mov_slider.appendChild(slide);

    /* Prev button */
    var prev = document.createElement('a');
    prev.setAttribute('class', 'prev');
    prev.textContent = "\u276E";
    mov_slider.appendChild(prev);

    /* Next button */
    var next = document.createElement('a');
    next.setAttribute('class', 'next');
    next.textContent = "\u276F";
    mov_slider.appendChild(next);

    container.appendChild(mov_slider);

    DOMManager.showSlides();
};

DOMManager.scroll = function(id, value)
{
    var elmnt = document.getElementById(id);

    var options = {
        left: elmnt.scrollLeft + value,
        top: 0,
        behavior: 'smooth'
    };

    elmnt.scroll(options);
};

DOMManager.toggleMenu = function(){
    var elements = document.getElementsByClassName('item');

    for(var i = 0; i < elements.length; ++i)
        elements[i].classList.toggle('show');
};

DOMManager.createMovieList = function(id, data){
    var container = document.getElementById(id);
    if(container === null || data === null || data.length <= 0)
        return;

    for(var i = 0; i < data.length; ++i)
    {
        var movie = DOMManager.createMovie(data[i]);
        container.appendChild(movie);
    }
};

DOMManager.createMovie = function(currentData){
    var a = document.createElement("a");
    if(currentData.id !== -1)
        a.setAttribute('href', 'movie.php?id=' + currentData.id);
    a.setAttribute('class', 'movie movielink');

    var figure = document.createElement("figure");
    figure.setAttribute('class', 'moviefigure');
    var caption = document.createElement('figcaption');
    caption.textContent = currentData.title;

    var image = new Image();
    image.alt = currentData.description;
    image.src = currentData.image;
    image.setAttribute('class', 'movie_img');

    figure.appendChild(image);
    figure.appendChild(caption);
    a.appendChild(figure);


    return a;
};

DOMManager.toggleFavorite = function(object)
{
    object.classList.toggle('selected');
};

/* MOVIE CARD */
DOMManager.generateCard = function(data)
{
    /* POSTER */
    var poster = document.getElementById('poster');

    var image = new Image();
    image.alt = data.movie.title;
    image.src = data.movie.image;
    image.setAttribute('class', 'poster_img');

    poster.appendChild(image);

    var caption = document.createElement('figcaption');
    caption.textContent = data.movie.title;

    poster.appendChild(caption);

    /* CARD */
    var card = document.getElementById('movie-description');

    var h2 = document.createElement('h2');
    h2.textContent = data.movie.title;
    var favorite = document.createElement('a');

    favorite.setAttribute('class', 'icon favorite');

    if(data.watched === '1') {
        if (data.favorite === '1')
            favorite.classList.add('selected');

        favorite.setAttribute('onclick', 'movieManager.toggleFavorite('+data.movie.id+','+data.favorite+')');
        favorite.textContent = ' \u2605';
    }

    h2.appendChild(favorite);
    card.appendChild(h2);

    var description = document.createElement('p');
    description.textContent = data.movie.description;

    card.appendChild(description);

    var avg = document.createElement('p');
    avg.setAttribute('class', 'avg');
    avg.textContent = 'Voto medio: ' + data.avg;

    card.appendChild(avg);

    /* VOTO */
    /* MOSTRARE SOLO SE E' STATO VISTO */
    if(data.watched === '1')
    {
        var star_container = document.createElement('div');
        star_container.setAttribute('class', 'stars-container');

        for (i = 5; i > 0; --i) {
            var star = document.createElement('a');
            star.setAttribute('class', 'stars');
            if (i == data.vote)
                star.classList.add('selected');
            star.setAttribute('onclick', 'movieManager.setVote('+data.movie.id+','+i+')');
            star.textContent = '\u2605';

            star_container.appendChild(star);
        }

        card.appendChild(star_container);

        var watched = document.createElement('button');
        watched.setAttribute('class', 'red icon');
        watched.setAttribute('onclick', 'movieManager.unsetWatched(\''+data.movie.id+'\')');
        var text = document.createTextNode('Rimuovi dai film guardati \u2717');
        watched.appendChild(text);
        card.appendChild(watched);

        /* BOTTONE "NON GUARDATO" */
    } else {

        /* BOTTONI GUARDATO O GUARDA PIU' TARDI */
        if(data.watch_later === '0') {
            var watchlater = document.createElement('button');
            watchlater.setAttribute('class', 'red icon');
            watchlater.setAttribute('onclick', 'movieManager.setWatchLater(\''+data.movie.id+'\')');
            var wltext = document.createTextNode('Guarda più tardi \u{1F550}');
            watchlater.appendChild(wltext);
            card.appendChild(watchlater);
        } else {
            var removewatchlater = document.createElement('button');
            removewatchlater.setAttribute('class', 'red icon');
            removewatchlater.setAttribute('onclick', 'movieManager.unsetWatchLater(\''+data.movie.id+'\')');
            var rwltext = document.createTextNode('Rimuovi dai film da guardare \u2717');
            removewatchlater.appendChild(rwltext);
            card.appendChild(removewatchlater);
        }

        var setwatched = document.createElement('button');
        setwatched.setAttribute('class', 'green icon');
        setwatched.setAttribute('onclick', 'movieManager.setWatched(\''+data.movie.id+'\')');
        var swtext = document.createTextNode('Seleziona come già visto \u2713');
        setwatched.appendChild(swtext);

        card.appendChild(setwatched);
    }
};

DOMManager.loadComments = function(data)
{
    var comments = document.getElementById('comments-block');

    var comment, commentp;
    if(data.comments.length === 0)
    {
        comment = document.createElement('div');
        comment.setAttribute('class', 'comment');
        commentp = document.createElement('p');
        commentp.textContent = 'Nessun commento';
        comment.appendChild(commentp);
        comments.appendChild(comment);
    } else {

        for (i = 0; i < data.comments.length; ++i) {
            comment = document.createElement('div');
            comment.setAttribute('class', 'comment');

            if(data.is_admin === '1' || data.comments[i].user === data.sess_user)
            {
                var a = document.createElement('a');
                a.setAttribute('class', 'remove-comment icon');
                a.setAttribute('onclick', 'movieManager.deleteComment(\''+data.comments[i].id+'\')');
                a.textContent = '\u{1F5D1}'

                comment.appendChild(a);
            }

            var writer = document.createElement('p');
            writer.setAttribute('class', 'writer');
            writer.textContent = data.comments[i].user;
            comment.appendChild(writer);
            commentp = document.createElement('p');
            commentp.textContent = data.comments[i].text;
            comment.appendChild(commentp);
            var pubdate = document.createElement('p');
            pubdate.setAttribute('class', 'pub-date');
            pubdate.textContent = data.comments[i].pub_date;
            comment.appendChild(pubdate);

            comments.appendChild(comment);
        }
    }
};

DOMManager.loadSearchResult = function(data)
{
    var container = document.getElementById('search-result');

    while(container.firstChild)
    {
        container.removeChild(container.firstChild);
    }

    if(data.length !== 0)
    {
        for(i = 0; i < data.length; ++i)
        {
            if(data[i].id !== -1){
                var a = document.createElement('a');
                a.setAttribute('href', 'movie.php?id='+data[i].id);
                a.setAttribute('class', 'result');
                a.textContent = data[i].title;

                container.appendChild(a);
            } else {
                var p = document.createElement('p');
                p.setAttribute('class', 'result');
                p.textContent = 'Nessun Risultato';

                container.appendChild(p);
            }
        }
    }
};

DOMManager.loadAdminSearchResult = function(data)
{
    var container = document.getElementById('search-result');

    while(container.firstChild)
    {
        container.removeChild(container.firstChild);
    }

    if(data.length !== 0)
    {
        for(i = 0; i < data.length; ++i)
        {
            if(data[i].id !== -1){
                var a = document.createElement('a');
                a.setAttribute('href', '?getFilm='+data[i].id);
                a.setAttribute('class', 'result');
                a.textContent = data[i].title;

                container.appendChild(a);
            } else {
                var p = document.createElement('p');
                p.setAttribute('class', 'result');
                p.textContent = 'Nessun Risultato';

                container.appendChild(p);
            }
        }
    }
};
