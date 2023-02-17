//Display movies by username when user logins.
$(document).ready(function(){
    $.ajax({
        url: '/display',
        type: 'GET',
        dataType: 'json',
        async: true,
        success: function(data,status) {
            for (i = 0; i<data.length; i++){   
                movie = data[i];
                title = movie.Title;
                opinion = movie.Vote;
                voted = $('.movie-list').find(".movie-title:contains("+title+")");
                list = voted.parent();
                if (opinion == 'Like') {
                    vote = list.find(".Like");
                    opp = list.find(".Hate");
                    vote.css("background-color","#00adef");
                    vote.css("color","white");
                    vote.css("border-radius","5px");
                    vote.css("padding","5px");
                    vote.attr("data","vote");
                    vote.attr("data-id","1");
                    opp.attr("data","vote");
                    likes = list.find(".likes");
                    likes.css("color","green");
                } else {
                    vote = list.find(".Hate");
                    opp = list.find(".Like");
                    vote.css("background-color","#00adef");
                    vote.css("color","white");
                    vote.css("border-radius","5px");
                    vote.css("padding","5px");
                    vote.attr("data","vote");
                    vote.attr("data-id","1");
                    opp.attr("data","vote");
                    hates = list.find(".hates");
                    hates.css("color","red");
                } 
            }
        }
    });  
});

//Sort movies according to user's choice.
$("input:radio").on('change', function() {
    var radioValue = $("input:checked").val();
    $.ajax({
        url: '/movie/sort/',
        type: 'POST',
        data: {'Value': radioValue},
        dataType: 'json',
        async: true,
        success: function(data,status) {
            var e = $('<tr><th>Title</th><th>Description</th><th>Posted</th><th>Posted by</th><th>Likes</th><th>Hates</th></tr>');  
            $('.movie-list').css('display','none');
            $('#movie').html('');
            $('#movie').append(e);          
            for(i = 0; i < data.length; i++) {
                movie = data[i];
                var e = $('<tr><td id = "title"></td><td id = "description"></td><td id = "date"></td><td id = "user"></td><td id = "likes"></td><td id = "hates"></td></tr>');
                $('#title',e).html(movie['title']);
                $('#description',e).html(movie['description']);
                date = new Date(movie['date']['date']);
                var dd = String(date.getDate()).padStart(2, '0');
                var mm = String(date.getMonth() + 1).padStart(2, '0'); 
                var yyyy = date.getFullYear();
                date = mm + '/' + dd + '/' + yyyy;
                $('#date',e).html(date);
                $('#user',e).html(movie['user']);
                $('#likes',e).html(movie['likes']);
                $('#hates',e).html(movie['hates']);
                $('#movie').append(e);  
            }  
        }
    })
});

//Movie voting bu user.
$('.Hate, .Like').on('click',function(e){
    vote = $(this).attr("data");
    classe = $(this).attr("class");
    id = $(this).attr("data-id");
    movie = $(this).parent().parent().parent();
    if (classe == "Like") {
        opp = movie.find('.Hate');
        oppvote = opp.attr("data");    
    } else {
        opp = movie.find('.Like');
        oppvote = opp.attr("data");
    }
 
    //User changes his vote.
    if (vote == "vote" && oppvote == "vote" && id == "0") {
        e.preventDefault();
        movie = $(this).parent().parent().parent();
        title = movie.find(".movie-title").text().trim();
        $.ajax({
            url: '/changeVote',
            type: 'POST',
            data: {'Title': title},
            dataType: 'json',
            async: true,
            success: function(data,status) {
                location.reload();   
            }
        }); 
    //User retracts his vote.
    } else if (vote == "vote" && oppvote == "vote" && id == "1") {
        e.preventDefault();
        movie = $(this).parent().parent().parent();
        title = movie.find(".movie-title").text().trim();
        $.ajax({
            url: '/retractVote',
            type: 'POST',
            data: {'Title': title},
            dataType: 'json',
            async: true,
            success: function(data, status) {
                location.reload();
            }
        });   
    }  
});