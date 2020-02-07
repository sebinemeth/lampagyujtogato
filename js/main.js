function showTheory(data) {
    try {
        var body = "";
        for(var i=0; i<data.body.length; i++)
            body += "<"+data.body[i].type+">"+data.body[i].content+"</"+data.body[i].type+">";
        alertBox({
            "title": data.title,
            "body": body,
            "css": {
                "width": "60%"
            }
        });
    } catch (error) {
        alertBox({
            "type": "error",
            "title": "Hiba az elmélet betöltésénél",
            "body": error.toString()
        });
    }
}

function alertBox(data) {
    var alertButtons =
        $('<div>').addClass('alertButtons').append(
            $('<button>OK</button>').addClass('alertButton').click(function() {
                $('.alertFader').last().remove();
            })
        );
    if (data.buttons != undefined)
        for(var i=0; i<data.buttons.length; i++) {
            var buttonFn = data.buttons[i].run;
            alertButtons.append(
                $('<button>').html(data.buttons[i].label).addClass('alertButton').click(function() {
                    buttonFn();
                })
            );
        }
    if (data.css == undefined)
        data.css = {};
    var alertBox = $('<div>').addClass('alertBox').addClass(data.type).append(
        $('<h3>').addClass('alertTitle').html(data.title)
    ).append(
        $('<p>').addClass('alertBody').html(data.body)
    ).append(
        alertButtons
    ).css(data.css);
    
    if (data.draggable != false)
        alertBox.draggable();
    
    $('body').append(
        $('<div>').addClass('alertFader').append(
            alertBox
        ).css({
            "height":$(document).innerHeight()
        })
    );
    //MathJax.Hub.Typeset()
}