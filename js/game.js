var system;
var defTime = 60;
var gameOn = false;

function load_game(lesson,example) {
    $.post("game_backend/get_example.php", {"lesson":lesson,"example":example}, function(data){
        console.log(data);
        if(data.error != undefined) {
            $('.secondaryTitle').html("");
            $('.mainTitle').html("Nem elérhető példa");    
            alertBox({
                "type": "error",
                "title": "Nem elérhető példa",
                "body": "Ezt a példát még nem oldottad fel."
            });
            return;
        }
        $('.secondaryTitle').html(data.lesson_number+". "+data.title);
        $('.mainTitle').html(data.example_number+". példa");    
        try {
            system = new Circuit(data.json);
            system.data = data;
            $('.drawingTable').replaceWith(system.getPicture());
            system.initializeDropper();
            gameOn = true;
            timer();
        }
        catch(error) {
            alertBox({
                "type": "error",
                "title": "Sikertelen betöltés",
                "body": error
            });
            console.log(error);
        }
    },"json").error(function(data){
        console.log(data);
    });
}

function run() {
    lightsOn(false);
    try {
        var result = system.countVolts();
        var log = system.checkConditions();
        lightsOn(true);
        publish(system.data.lesson_number,system.data.example_number,3);
        alertBox({
                "type": "success",
                "title": "Sikeres lefutás",
                "body": "<code>"+log+"</code><br>Minden feltétel teljesült",
                "buttons": [
                    {
                        "label":"Következő példa",
                        "run":function(){
                            nextExample();
                        }
                    }
                ]
            });
    }
    catch(error) {
        alertBox({
            "type": "error",
            "title": "Hiba a lefutáskor",
            "body": "<code>"+error+"</code>"
        });
        console.log(error);
    }
}

function timer() {
    var time = parseInt($(".minute").html())*60+parseInt($(".second").html());
    time++;
    var min = Math.floor(time/60);
    var sec = time%60;
    $(".minute").html((min < 10 ? "0" : "")+min);
    $(".second").html((sec < 10 ? "0" : "")+sec);
    $(".loadingBar").animate({
        "width": 100*(1-time/defTime)+"%"
    },0,"linear");
    if(gameOn)
        setTimeout(function() {
            timer();
        },1000);
}

function lightsOn(on) {
    if(on)
        $(".circuitPictureCell.bulb").addClass('lit');
    else
        $(".circuitPictureCell.bulb").removeClass('lit');
}

function help() {
    alertBox({
        "title": "Segítség",
        "body": "A segítségkérés funkció még nem elérhető"
    });
}

function learn() {
    showTheory(system.data.theory_json);
}

function publish(lesson,example,msg) {
    $.post("game_backend/set_result.php", {"lesson_number":lesson,"example_number":example,"msg":msg}, function(data){
        console.log(data);
    });
}

function nextExample() {
    $.post("game_backend/get_example_list.php", {"lesson_number":system.data.lesson_number}, function(data){
        var nextExists = false;
        $.each(data,function(index,item){
            if(item.example_number == system.data.example_number + 1)
                nextExists = true;
        });
        if(nextExists)
            $.redirect('./?page=game', {'lesson_number': system.data.lesson_number, 'example_number': system.data.example_number+1});
        else
            $.redirect('./',{"lesson":system.data.lesson_number},"get");
    },"json").error(function(msg){
        console.log(msg);
    });
}