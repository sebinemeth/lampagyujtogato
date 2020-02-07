<!doctype html>
<html>
    <head>
        <title>CircuIt drawer</title>
        <meta charset="utf-8">
        <script src="js/libs/jquery-1.8.2.js"></script>
        <style>
            #bigtable {
                margin: 0px auto;
            }
            td {
                vertical-align: top;
            }
            .components {
                max-width: 150px;
            }
            .components img {
                width: 50px;
                margin: 10px;
            }
            .palya {
                border: solid 1px black;
                border-spacing: 0;
                border-collapse: collapse;
            }
            .palya td {
                padding: 0px;
                width: 50px;
                height: 50px;
                min-width: 50px;
                min-height: 50px;
                border: solid 1px silver;
                position: relative;
            }
            .palya td img {
                padding: 0px;
                margin: 0px;
                display: block;
                width: 50px;
                height: 50px;
            }
            .selected {
                background-color: silver;
            }
            .task {
                background-color: rgb(55,122,187);
                border-radius: 10px;
            }
            .point {
                top: 0;
                left: 0;
                margin: 15px;
                display: block;
                width: 20px;
                height: 20px;
                position: absolute;
                z-index: 2;
                background-color: rgb(55,122,187);
                border-radius: 25px;
                float: left;
            }
            .val {
                position: absolute;
                bottom: 0;
                font-size: 8pt;
                padding: 2px;
                background-color: rgba(0,0,0,0.2);
                min-width: 25%;
                height: 25%;
                text-align: center;
            }
            .output {
                max-height: 500px;
                max-width: 300px;
                overflow: auto;
                padding: 10px;
                background-color: silver;
                text-align: left;
            }
            input {
                width: 65px;
                margin: 0px 10px;
            }
            .option {
                margin: 10px;
                padding: 15px;
                background-color: silver;
            }
            .optionThumb {
                width: 100%;
                text-align: center;
            }
            .optionThumb img {
                width: 50px;
                margin: 10px;
            }
            fieldset {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <table id="bigtable">
            <tr>
                <td>
                    <fieldset>
                        <legend>Elemek</legend>
                        <div class="components">
                            <img type="resistor" src="graphical_components/resistor.svg?v=<?=time();?>">
                            <img type="v_source" src="graphical_components/v_source.svg?v=<?=time();?>">
                            <img type="c_source" src="graphical_components/c_source.svg?v=<?=time();?>">
                            <img type="inductor" src="graphical_components/inductor.svg?v=<?=time();?>">
                            <img type="capacitor" src="graphical_components/capacitor.svg?v=<?=time();?>">
                            <img type="wire" src="graphical_components/wire.svg?v=<?=time();?>">
                            <img type="wire_l" src="graphical_components/wire_l.svg?v=<?=time();?>">
                            <img type="wire_t" src="graphical_components/wire_t.svg?v=<?=time();?>">
                            <img type="wire_x" src="graphical_components/wire_x.svg?v=<?=time();?>">
                            <img type="task" src="graphical_components/task.svg?v=<?=time();?>" class="task">
                            <img type="bulb" src="graphical_components/bulb.svg?v=<?=time();?>">
                            <img type="cut" src="graphical_components/cut.svg?v=<?=time();?>">
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Módosítás</legend>
                        <div class="buttons">
                            <button onclick="remove()">Mező törlése</button><br>
                            <button onclick="clear_table()">Tábla ürítése</button><br>
                            <button onclick="add_point()">Új csomópont</button><br>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Részletek</legend>
                        <div class="details">
                            <table>
                                <tr><th>Type:</th><td><input type="text" disabled class="details_type"></td></tr>
                                <tr><th>Value:</th><td><input type="number" disabled class="details_value"></td></tr>
                                <tr><th>Rotate:</th><td><input type="number" class="details_rot" min="0" max="270" step="90"></td></tr>
                                <tr><th colspan="2"><hr>Condition</th></tr>
                                <tr><th>Type:</th><td><input type="text" disabled class="details_cond_type" length="1"></td></tr>
                                <tr><th>Value:</th><td><input type="number" disabled class="details_cond_value"></td></tr>
                                <tr><td colspan="2"><button class="details_cond_new">Új condition</button></td></tr>
                                <tr><td colspan="2"><button class="details_cond_delete">Törlés</button></td></tr>
                            </table>
                        </div>
                    </fieldset>
                </td>
                <td>
                    <fieldset>
                        <legend>Áramkör</legend>
                        <table class="palya">  
                        </table>
                    </fieldset>
                </td>
                <td>
                    <fieldset>
                        <legend>Lehetőségek</legend>
                        <div class="options"></div>
                        <button onclick="$('.options').append(optionObject());">Új lehetőség</button>
                    </fieldset>
                </td>
                <td>
                    <fieldset>
                        <legend>Kimenet</legend>
                        <div>
                            <button onclick="export_json()">Json exportálása</button>
                            <button onclick="$('.output').html('')">Kimenet ürítése</button>
                            <pre class="output"></pre>
                        </div>
                        <input class="filename" type="text" value="mycircuit" placeholder="fájlnév">.json 
                        <button onclick="download($('.filename').val(),$('.output').html());">letöltése</button>
                    </fieldset>
                </td>
            </tr>
        </table>
    </body>
    <script>
        var selected;
        $(document).ready(function(){
            clear_table();
            $(".components img").click(function(){
                set_comp($(this).attr("type"));
            });
            $(".details_value").change(function(){
                set_val($(this).val());
            });
            $(".details_rot").change(function(){
                rotate($(this).val());
            });
            $(".details_cond_new").click(function(){
                add_condition("i",0);
            });
            $(".details_cond_delete").click(function(){
                delete_cond();
            });
            $(".details_cond_type .details_cond_value").change(function(){
                set_cond($(".details_cond_type").val(),$(".details_cond_value").val());
            });
            $(document).keydown(function(e){
                //alert(e.keyCode);
                if(selected != null) {
                    if(e.keyCode == 37)
                        rotate(parseInt(selected.attr("rotate"))-90);
                    if(e.keyCode == 38)
                        set_val(parseInt(selected.attr("value"))+1);
                    if(e.keyCode == 39)
                        rotate(parseInt(selected.attr("rotate"))+90);
                    if(e.keyCode == 40)
                        set_val(parseInt(selected.attr("value"))-1);
                }
            });
            for(var i=0; i<3; i++)
                $(".options").append(optionObject());
        });
        
        function set_val(val) {
            if(selected != null && selected.attr("value") != undefined) {
                val = val < 0 ? 0.001 : val;
                selected.attr("value",val).find(".val").html(val);
                display_details();
            }
        }
        
        function clear_table() {
            $(".palya").empty();
            for(var i=0; i<14; i++) {
                var row = $('<tr>');
                for(var j=0; j<15; j++)
                    row.append('<td>');
                $(".palya").append(row);
            }
            $(".palya td").click(function(){
                deselect();
                select($(this));
            });
            deselect();
        }
        
        function set_comp(type) {
            if(selected != null) {
                selected.empty();
                selected.attr("type",type);
                if(type != "task")
                    selected.attr("rotate",0);
                else
                    selected.removeAttr("rotate");
                if(type != "wire" && type != "wire_l" && type != "wire_t" && type != "wire_x" && type != "task" && type != "cut")
                    selected.attr("value",10).append('<div class="val">'+10);
                else
                    selected.removeAttr("value");
                selected.append("<img src='graphical_components/"+type+".svg?v=<?=time();?>' class='"+type+"'>");
                display_details();
            }
        }
        
        function display_details() {
            $(".details_type").val(selected.attr("type"));
            if(selected.attr("rotate") == undefined || selected.attr("rotate") == "")
                $(".details_rot").prop("disabled",true).val("");
            else
                $(".details_rot").prop("disabled",false).val(selected.attr("rotate"));
            
            if(selected.attr("value") == undefined || selected.attr("value") == "")
                $(".details_value").prop("disabled",true).val("");
            else
                $(".details_value").prop("disabled",false).val(selected.attr("value"));
            var cond = selected.find('.condition');
            if(cond.size() > 0) {
                $(".details_cond_type").prop("disabled",false).val(cond.attr("type"));
                $(".details_cond_value").prop("disabled",false).val(cond.attr("value"));
                $(".details_cond_new").prop("disabled",true);
                $(".details_cond_delete").prop("disabled",false);
            }
            else {
                $(".details_cond_type").prop("disabled",true).val("");
                $(".details_cond_value").prop("disabled",true).val("");
                $(".details_cond_new").prop("disabled",false);
                $(".details_cond_delete").prop("disabled",true);
            }
        }
        
        function clear_details() {
            $(".details_type").prop("disabled",true).val("");
            $(".details_value").prop("disabled",true).val("");
            $(".details_rot").prop("disabled",true).val("");
        }
        
        function select(object) {
            selected = object;
            object.addClass("selected");
            display_details();
        }
        
        function deselect() {
            if(selected != null)
                selected.removeClass("selected");
            selected = null;
            clear_details();
        }
        
        function remove() {
            if(selected != null)
                selected.empty().removeAttr("type").removeAttr("value").removeAttr("rotate");
            clear_details();
        }
        
        function rotate(deg) {
            if(selected != null && selected.attr("rotate") != undefined) {
                deg = deg < 0 ? (deg + 360)%360 : deg%360; 
                selected.find("img").css("transform","rotate("+deg+"deg)");
                selected.attr("rotate",deg);
                display_details();
            }
        }
        
        function add_point() {
            if(selected != null)
                $("<div>").addClass("point").appendTo(selected);
        }
        
        function add_condition(type,value) {
            if(selected != null && selected.find('.condition').size() == 0) {
                $("<div>").addClass("condition").attr({"type":type, "value":value}).appendTo(selected);
                display_details();
            }
        }
        
        function delete_cond() {
            if(selected != null && selected.find('.condition').size() == 0) {
                selected.find('.condition').remove();
                display_details();
            }
        }
        
        function set_cond(type, value) {
            selected.find(".condition").attr({"type":type, "value":value});
        }
        
        
        
        
        
        
        
        function export_json() {
            var circ = new Object();
            var bigArray = new Array();
            $.each($(".palya tr"),function(index,row){
                var rowArray = new Array();
                $.each(row.children,function(i,cell){
                    rowArray.push(cell);
                });
                bigArray.push(rowArray);
            });
            truncate(bigArray);
            circ.points = getPoints(bigArray);
            jsonize(bigArray);
            circ.rows = bigArray;
            circ.options = getOptions();
            //sample cond
            circ.conditions = new Array();
            var c = new Object();
            c.comment = "Sample condition";
            c.type = "i";
            c.x = 1;
            c.y = 0;
            c.value = 10;
            circ.conditions.push(c);
            //end
            $('.output').html(JSON.stringify(circ,null,4));
        }
        
        function truncate(array) {
            while(emptyRow(array[0]) && array.length > 0)
                array.splice(0,1);
            while(emptyRow(array[array.length-1]) && array.length > 0)
                array.splice(array.length-1,1);
            while(emptyColumn(array,0) && array.length > 0)
                spliceColumn(array,0);
            if(array.length == 0)
                return;
            while(emptyColumn(array,array[0].length-1) && array.length > 0)
                spliceColumn(array,array[0].length-1);
        }
        
        function emptyRow(row){
            if(row == undefined)
                return true;
            for(var i=0;i<row.length; i++)
                if(row[i].childElementCount > 0)
                    return false;
            return true;
        }
        
        function emptyColumn(array,col_no) {
            for(var i=0;i<array.length;i++)
                if(array[i].length > 0 && array[i][col_no].childElementCount > 0)
                    return false;
            return true;
        }
        
        function spliceColumn(array,col_no) {
            for(var i=0;i<array.length;i++)
                array[i].splice(col_no,1);
        }
        
        function getPoints(array) {
            var alphabet = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"];
            var points = new Array();
            var n = 0;
            for(var i=0;i<array.length;i++)
                for(var j=0;j<array[i].length;j++)
                    if(array[i][j].childElementCount > 0 && array[i][j].lastChild.localName == "div")
                        points.push({"id" : alphabet[n++], "x" : j, "y" : i});
            return points;
        }
        
        function getOptions() {
            var options = new Array();
            $(".option").each(function(i,item){
                var option = new Object();
                option.type = $(item).find(".optionType").val();
                option.value = parseInt($(item).find(".optionValue").val());
                option.rotate = parseInt($(item).find(".optionRotate").val());
                if(option.type != "empty")
                    options.push(option);
            });
            return options;
        }
        
        function jsonize(array) {
            for(var i=0;i<array.length;i++)
                for(var j=0;j<array[i].length;j++) {
                    if(array[i][j].childElementCount == 0) {
                        array[i][j] = {};
                        continue;
                    }
                    var obj = new Object();
                    obj.type = array[i][j].attributes["type"].value;
                    if(array[i][j].attributes["rotate"] != undefined)
                        obj.rotate = parseInt(array[i][j].attributes["rotate"].value);
                    if(array[i][j].attributes["value"] != undefined)
                        obj.value = parseInt(array[i][j].attributes["value"].value);
                    
                    var type = obj.type;
                    if(type.substr(0,4) == "wire") {
                        var rotate = obj.rotate/90;
                        var wireObj = {"type":"wire"};
                        /*
                        
                        wire: four bits = the four input/output on field borders: io=[4th][3rd][2nd][1st]
                             [4th]
                             +---+
                        [1st]|   |[3rd]
                             +---+
                             [2nd]
                        */
                        
                        switch (type) { //without rotation
                            case "wire_l" :
                                wireObj.io = 12; //1100
                                break;
                            case "wire_t" :
                                wireObj.io = 14; //1110
                                break;
                            case "wire_x" :
                                wireObj.io = 15; //1111
                                break;
                            default :
                                wireObj.io = 10; //1010
                        }
                        for(var k=0; k<rotate; k++) {
                            var carry = (wireObj.io % 2 == 0) ? 0 : 8;
                            var new_io = Math.floor(wireObj.io / 2) + carry;
                            wireObj.io = new_io;
                        }
                        obj = wireObj;
                    }
                    array[i][j] = obj;   
                }
                
        }
        
        function optionObject() {
            return $("<div>").addClass("option").append(
                    $("<div>").addClass("optionThumb")
                ).append(
                $("<select>").addClass("optionType").append(
                    $("<option value='empty'>üres</option>")
                ).append(
                    $("<option value='resistor'>ellenállás</option>")
                ).append(
                    $("<option value='v_source'>telep</option>")
                ).change(function(){
                    $(this).parent().find("input").val("0");
                    if($(this).val() == "empty") {
                        $(this).parent().find("input").prop("disabled",true);
                        $(".optionThumb").empty();
                    }
                    else {
                        $(this).parent().find("input").prop("disabled",false);
                        $(this).parent().find(".optionThumb").html("<img src='graphical_components/"+$(this).val()+".svg'>");
                    }
                })
            ).append(
                $("<input type='number' value='0' placeholder='érték' disabled>").addClass("optionValue")
            ).append(
                $("<input type='number' value='0' placeholder='forgatás' disabled min='0' max='270' step='90'>").addClass("optionRotate").change(function(){
                    $(this).parent().find(".optionThumb img").css("transform","rotate("+$(this).val()+"deg)")
                })
            );
        }
        
        function download(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename+'.json');

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }

        function load(json) {
            clear_table();
            var x0 = Math.floor((15 - json['rows'][0].length - 1) / 2);
            var y0 = Math.floor((14 - json['rows'].length - 1) / 2);
            
            for(var i=0; i<json['rows'].length; i++)
                for(var j=0; j<json['rows'][i].length; j++) {
                    deselect();
                    select($('.palya tr').eq(i+y0).find('td').eq(j+x0));
                    if(json.rows[i][j].type != undefined)
                        set_comp(json.rows[i][j].type);
                    if(json.rows[i][j].rotate != undefined)
                        rotate(json.rows[i][j].rotate);
                    if(json.rows[i][j].value != undefined)
                        set_val(json.rows[i][j].value);
                    if(json.rows[i][j].type == "wire")
                        switch(json.rows[i][j].io) {
                            case 0b0011 :
                                set_comp("wire_l");
                                rotate(180);
                                break;
                            case 0b0101 :
                                set_comp("wire");
                                rotate(90);
                                break;
                            case 0b0110 :
                                set_comp("wire_l");
                                rotate(90);
                                break;
                            case 0b0111 :
                                set_comp("wire_t");
                                rotate(90);
                                break;
                            case 0b1001 :
                                set_comp("wire_l");
                                rotate(270);
                                break;
                            case 0b1010 :
                                set_comp("wire");
                                rotate(0);
                                break;
                            case 0b1011 :
                                set_comp("wire_t");
                                rotate(180);
                                break;
                            case 0b1100 :
                                set_comp("wire_l");
                                rotate(0);
                                break;
                            case 0b1101 :
                                set_comp("wire_t");
                                rotate(270);
                                break;
                            case 0b1110 :
                                set_comp("wire_t");
                                rotate(0);
                                break;
                            case 0b1111 :
                                set_comp("wire_x");
                                rotate(0);
                                break;
                        }
                }
            
            for(var i=0; i<json.points.length; i++) {
                deselect();
                select($('.palya tr').eq(json.points[i].y+y0).find('td').eq(json.points[i].x+x0));
                add_point();
            }
        }
        
    </script>
</html>