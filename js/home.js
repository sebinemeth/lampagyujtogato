var lessons;
var callBackLesson;
function load_lessons(n) {
    $(".mainTitle").html("Főmenü");
    window.history.pushState("", "", './');
    
    $.getJSON("game_backend/get_lesson_list.php",function(data){
        $(".levelSelector").empty();
        lessons = data;
        var sum_xp = 0;
        $.each(data,function(index,item){
            if(item.lesson_number == n)
                callBackLesson = item;
            if(n != undefined)
                return;
            var locked = item.locked;
            var lesson = $("<div>").addClass("lessonDiv");
            if (locked)
                lesson.addClass("locked");
            lesson.append($("<div>"+item.lesson_number+"</div>").addClass("lessonNumber"));
            lesson.append($("<div>"+item.title+"</div>").addClass("lessonTitle"));
            if(parseInt(item.lesson_number) == 0)
                lesson.click(function(){
                    showTheory(item.json);
                });
            else if(!locked)
                lesson.click(function(){
                    select_lesson(item);
                });
            $(".levelSelector").append(lesson);
            sum_xp += item.xp;
        });
        stat([{"label":"Eddigi összes XP-d","value":sum_xp}]);
        if(callBackLesson != undefined)
            select_lesson(callBackLesson);
        callBackLesson = undefined;
    }).error(function(msg){
        console.log(msg);
    });
}

function select_lesson(lesson) {
    $(".mainTitle").html(lesson.lesson_number+". "+lesson.title);
    window.history.pushState("", "", './?lesson='+lesson.lesson_number);
    $.post("game_backend/get_example_list.php", {"lesson_number":lesson.lesson_number}, function(data){
        $(".levelSelector").empty().append("<div class='backButtonDiv'><button class='backButton' onclick='load_lessons()' class='button'>Vissza</button></div>");
        var theory = $("<div>").addClass("exampleDiv theory");
        theory.append($("<div>0</div>").addClass("exampleNumber"));
        theory.append($("<div>elmélet</div>").addClass("exampleTitle"));
        theory.click(function(){
            showTheory(lesson.json);
        });
        $(".levelSelector").append(theory);        
        
        $.each(data,function(index,item){
            var locked = item.locked;
            var example = $("<div>").addClass("exampleDiv");
            if (locked)
                example.addClass("locked");
            example.append($("<div>"+item.example_number+"</div>").addClass("exampleNumber"));
            example.append($("<div>példa</div>").addClass("exampleTitle"));
            if(!locked) {
                var ratingDiv = $("<div>").addClass("exampleRating");
                var rating = item.rating; //Math.floor(Math.random() * 4);
                for(var i=0; i<3; i++)
                    i>=rating ?
                        ratingDiv.append($('<img src="img/star_empty.svg">')) :
                        ratingDiv.append($('<img src="img/star_full.svg">'));
                example.click(function(){
                    $.redirect('./?page=game', {'lesson_number': item.lesson_number, 'example_number': item.example_number});
                });
                example.append(ratingDiv);
            }
            $(".levelSelector").append(example);
        });
        stat([{"label":"Ezen a szinten szerzett XP","value":lesson.xp},{"label":"Összesen szerzendő","value":lesson.req_xp}]);
    },"json").error(function(msg){
        console.log(msg);
    });
}

function stat(stats) {
    $('.statContent').empty();
    for(var i=0; i<stats.length; i++)
        $('.statContent').append(
            $('<div>').append(
                $('<div>').addClass('statPointLabel').html(stats[i].label)
            ).append(
                $('<div>').addClass('statPoint').html(stats[i].value)
            )
        );
}