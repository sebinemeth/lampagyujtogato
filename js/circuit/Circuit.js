class Circuit {
    static Dir() {
        return Object.freeze({"UP":8,"RIGHT":4,"DOWN":2,"LEFT":1});
    }
	
	constructor(json) {
		this.points = new Array();
        this.json = json;
        if(json == null || json == undefined)
            throw "Hiányzó vagy hibás pályafájl";
        
        this.table = $("<table>").addClass("drawingTable");
        this.json.conditions = [];
        for(var i=0; i<this.json.rows.length; i++) {
            var row = $("<tr>").addClass("circuitPictureRow").appendTo(this.table);
            for(var j=0; j<this.json.rows[i].length; j++) {
                var type = this.json.rows[i][j].type;
                var cell = $("<td>").addClass("circuitPictureCell "+type).appendTo(row);
                var tdImage = $("<div>").addClass("tdImage").appendTo(cell);
                var css = new Object();
                //handling condition
                var cond = this.json.rows[i][j].condition;
                if(cond != undefined) {
                    var condition = $("<div>").addClass("condition").append(
                        $("<div>").html(cond.value).addClass("conditionValue "+cond.type));
                    cell.append(condition);
                    cond.field = this.json.rows[i][j];
                    cond.isFulfilled = function(i) {
                        try {
                            var current = (this.branch.getU()-this.branch.sumU())/this.branch.sumR()*this.field.sign;
                        }
                        catch(error) {
                            var current = 0;
                        }
                        var realValue;
                        var unit;
                        switch (this.type) {
                            case "u" :
                                realValue = current*this.field.value;
                                unit = "V";
                                break;
                            case "i" :
                                realValue = current;
                                unit = "A";
                                break;
                        }
                        if(Math.abs(this.value - realValue)>1e-4)
                            throw " >> ["+i+"] HIBA : "+this.value+ " "+unit+" helyett "+realValue.toFixed(3).toLocaleString('hu-HU')+" "+unit+"<br>";
                        return " >> ["+i+"] OK : "+realValue.toFixed(3).toLocaleString('hu-HU')+" "+unit+"<br>";
                    };
                    this.json.conditions.push(cond);
                }
                if(type != undefined) {
                    if(type == "wire") {
                        //
                        switch(this.json.rows[i][j].io) {
                            case 3 : 
                                css = {"background-image":"url(graphical_components/wire_l.svg)","transform":"rotate(180deg)"};
                                break;
                            case 5 : 
                                css = {"background-image":"url(graphical_components/wire.svg)","transform":"rotate(90deg)"};
                                break;
                            case 6 : 
                                css = {"background-image":"url(graphical_components/wire_l.svg)","transform":"rotate(90deg)"};
                                break;
                            case 7 : 
                                css = {"background-image":"url(graphical_components/wire_t.svg)","transform":"rotate(90deg)"};
                                break;
                            case 9 : 
                                css = {"background-image":"url(graphical_components/wire_l.svg)","transform":"rotate(270deg)"};
                                break;
                            case 10 : 
                                css = {"background-image":"url(graphical_components/wire.svg)"};
                                break;
                            case 11 : 
                                css = {"background-image":"url(graphical_components/wire_t.svg)","transform":"rotate(180deg)"};
                                break;
                            case 12 : 
                                css = {"background-image":"url(graphical_components/wire_l.svg)"};
                                break;
                            case 13 : 
                                css = {"background-image":"url(graphical_components/wire_t.svg)","transform":"rotate(270deg)"};
                                break;
                            case 14 :
                                css = {"background-image":"url(graphical_components/wire_t.svg)"};
                                break;
                            case 15 : 
                                css = {"background-image":"url(graphical_components/wire_x.svg)"};
                                break;
                            default :
                                css = {"background-image":"url(graphical_components/wire.svg)"};
                        }
                    }
                    else 
                        css = {"background-image":"url(graphical_components/"+type+".svg)","transform":"rotate("+this.json.rows[i][j].rotate+"deg)"};
                    tdImage.css(css);
                    if(type == "task")
                        tdImage.addClass("taskDropper").data("orient");
                    if(type != "wire" && type != "task")
                        cell.append($("<div>").addClass("elemValue").html(this.json.rows[i][j].value));
                }
            }
        }
        console.log(this.json.conditions);
        /*for(var i=0; i<this.json.conditions.length; i++) {
            var condition = $("<div>").addClass("condition").append(
                $("<div>").html(this.json.conditions[i].value).addClass("conditionValue "+this.json.conditions[i].type));
            this.table.find("tr").eq(this.json.conditions[i].y).find("td").eq(this.json.conditions[i].x).append(condition);
            
            this.json.conditions[i].field = this.json.rows[this.json.conditions[i].y][this.json.conditions[i].x];
            this.json.conditions[i].field.condition = this.json.conditions[i];
            this.json.conditions[i].isFulfilled = function(i) {
                try {
                    var current = (this.branch.getU()-this.branch.sumU())/this.branch.sumR()*this.field.sign;
                }
                catch(error) {
                    var current = 0;
                }
                var realValue;
                var unit;
                switch (this.type) {
                    case "v" :
                        realValue = current*this.field.value;
                        unit = "V";
                        break;
                    case "i" :
                        realValue = current;
                        unit = "A";
                        break;
                }
                if(Math.abs(this.value - realValue)>1e-4)
                    throw " >> ["+i+"] HIBA : "+this.value+ " "+unit+" helyett "+realValue.toFixed(3).toLocaleString('hu-HU')+" "+unit+"<br>";
                return " >> ["+i+"] OK : "+realValue.toFixed(3).toLocaleString('hu-HU')+" "+unit+"<br>";
            };
        }*/
        for(var i=0; i<this.json.options.length; i++) {
            var tdImage = $("<div>").addClass("tdImage").css({
                "background-image":"url(graphical_components/"+this.json.options[i].type+".svg)",
                "transform":"rotate("+this.json.options[i].rotate+"deg)"
            });
            var orient = this.json.options[i].rotate%180 == 0 ? "NS" : "WE";
            var dragger = $("<div>").addClass("dragger "+this.json.options[i].type+" orient"+orient).append(tdImage).append(
                $("<div>").addClass("elemValue").html(this.json.options[i].value)
            );
            $('.components').append(dragger);
        }
        
        //
        //return;
        //
        for (var i=0; i<this.json.points.length; i++) {
			this.addPoint(new Point(this.json.points[i].id));
		}
		
		for (var i=0; i<this.json.points.length; i++) {
			this.pointSpread(this.json.points[i]);
		}
        console.log(this);
	}
    
	addPoint(p) {
		this.points.push(p);
	}
    
    pointSpread(jPoint) {
		var xCoor = jPoint.x;
		var yCoor = jPoint.y;
        try {  
		  var wire = this.json.rows[yCoor][xCoor];
        }
        catch(error) {
            throw "Hibás pályafájl";
        }
        this.wireSpread(wire,xCoor,yCoor,jPoint,0,null);
    }
    
    wireSpread(wire,x,y,startJPoint,fromDir,branch) { //letépve a pointspreadről
		var io = wire.io;	
		if ((io & Circuit.Dir().UP) == Circuit.Dir().UP && fromDir != Circuit.Dir().UP) {
			var up = new Branch(branch);
			this.branchCont(up, x, y - 1, Circuit.Dir().DOWN, startJPoint);
		}
		if ((io & Circuit.Dir().RIGHT) == Circuit.Dir().RIGHT && fromDir != Circuit.Dir().RIGHT) {
			var right = new Branch(branch);
			this.branchCont(right, x + 1, y, Circuit.Dir().LEFT, startJPoint);
		}
		if ((io & Circuit.Dir().DOWN) == Circuit.Dir().DOWN && fromDir != Circuit.Dir().DOWN) {
			var down = new Branch(branch);
			this.branchCont(down, x, y + 1, Circuit.Dir().UP, startJPoint);
		}
		if ((io & Circuit.Dir().LEFT) == Circuit.Dir().LEFT && fromDir != Circuit.Dir().LEFT) {
			var left = new Branch(branch);
			this.branchCont(left, x - 1, y, Circuit.Dir().RIGHT, startJPoint);
		}
	}
    
    branchCont(branch, x, y, fromDir, startJPoint) { 
		var field = this.json.rows[y][x];
		var type = field.type;
		var sign;
        
		if (type == "wire") {
			if (this.isPoint(x,y)) { //end
				var startX = startJPoint.x;
				if (startX < x) { //ekkor új ágat adunk az áramkörhöz (amúgy semmit nem csinálunk)
					var leftEnd = this.point(startJPoint.x, startJPoint.y);
					var rightEnd = this.point(x, y);
					branch.setEnds(leftEnd, rightEnd);
                    branch.setConditions();
					leftEnd.addBranch(branch);
					rightEnd.addBranch(branch);
				}
			} else {
				// megyünk tovább, esetleg több irányba is
                this.wireSpread(field,x,y,startJPoint,fromDir,branch);
			}
			return;
		} else if (type == "resistor") {
			var resistor = new Resistor(field.value); //új ellenállás (hozzá is adódik az ághoz)
			branch.addPart(resistor);
		} else if (type == "v_source") {
			sign = Circuit.countSign(fromDir,field.rotate);
			var vSource = new VSource(sign*field.value); 
			branch.addPart(vSource);
		} else if (type == "task") { //lyuk
            var field = this.table.find('tr').eq(y).find('td').eq(x).find('.tdImage');
			var hole = new Hole(field,fromDir);
			branch.addPart(hole);
		} else if (type == "bulb") { //lámpa
			sign = Circuit.countSign(fromDir,field.rotate)*-1;
			var bulb = new Bulb(field.value,sign);
			branch.addPart(bulb);
		} else if (type == "cut") { //szakadás
			var cut = new Cut();
			branch.addPart(cut);
		}
        
        if(field.condition != undefined) {
            field.sign = sign;
            branch.conditions.push(field);
        }
		
		switch (fromDir) { //megyünk tovább ugyanabba az irányba
            case Circuit.Dir().UP:
                this.branchCont(branch, x, y + 1, Circuit.Dir().UP, startJPoint);
                break;
            case Circuit.Dir().RIGHT:
                this.branchCont(branch, x - 1, y, Circuit.Dir().RIGHT, startJPoint);
                break;
            case Circuit.Dir().DOWN:
                this.branchCont(branch, x, y - 1, Circuit.Dir().DOWN, startJPoint);
                break;
            case Circuit.Dir().LEFT:
                this.branchCont(branch, x + 1, y, Circuit.Dir().LEFT, startJPoint);
                break;
		}
	}
    
    isPoint(x, y) {
		for (var i=0; i<this.json.points.length; i++) {
			var px = this.json.points[i].x;
			var py = this.json.points[i].y;
			if (px == x && py == y) {
				return true;
			}
		}
		return false;
	}
    
    point(x, y)	{
		var jP = null;
		for (var i=0; i<this.json.points.length; i++) {
			if (this.json.points[i].x == x && this.json.points[i].y == y) {
				jP=this.json.points[i];
				break;
			}
		}
		var jPID = jP.id;
		for (var i=0; i<this.points.length; i++) {
			if (this.points[i].name == jPID) {
				return this.points[i];
			}
		}
		return null;
	}
	
	countVolts() {
        this.hasEmpty();
        var arr = new Array();
        for (var i=0; i<this.points.length-1; i++) {
			var row = new Array(this.points.length).fill(0);
            for (var k=0; k<this.points[i+1].branches.length; k++){
                try {
                    var branch = this.points[i+1].branches[k];
                    var indexOfOtherEnd = this.points.findIndex(point => point === branch.otherEnd(this.points[i+1]))-1;
                    var r = branch.sumR();
                    var u = branch.sumU(this.points[i+1]);
                    if (r==0 || r==-1) { //then we can count voltage to the other end: U_a-U_b=sum(U_ti)
                        for (var j=0; j<this.points.length-1;j++) {
                            if (j==i) {
                                row[j]=1;
                            } else if (j==indexOfOtherEnd) {
                                row[j]=-1;
                            }
                            else {
                                row[j]=0;
                            }
                        }
                        row[this.points.length-1]=-u;
                        break;
                    }
                    else {
                        if (indexOfOtherEnd!=-1) {
                            row[indexOfOtherEnd]+=(1/r);
                        }
                        row[i]+=(-1/r);
                        row[this.points.length-1]+=(u/r);
                    }
                    //console.log({"r":r,"u":u});
                }
                catch(error) {
                    console.log(" >> Branch dropped");
                }
			}
			arr.push(row);
		}
        var arr2 = arr.slice();
        var arr3 = new Array();
        for(var i=0; i<arr.length; i++) {
            arr3.push(arr2[i][arr2[i].length-1]);
            arr2[i].splice(-1,1);
        }
        
        var coeff = math.matrix(arr2);
        var rightside = math.matrix(arr3);
        var coeff_inv = math.inv(coeff);
        var voltages = math.multiply(coeff_inv,rightside).toArray();
         
        this.points[0].voltage = 0;
        for(var i=1; i<this.points.length; i++) {
            this.points[i].voltage = voltages[i-1];
        }
        
        console.log({"matrix":arr,"csomopontok":voltages});
        
        return voltages;
	}
    
    static countSign (fromDir, rotation) { //kihasználjuk, hogy csak a balról jobbra ágak jók
		if (fromDir == Circuit.Dir().UP) {
            switch (rotation) {
                case 0 :
                    return -1;
                case 180 :
                    return 1;
                default :
                    throw "construction error"+ (new Error().stack);
            }
		} else if (fromDir == Circuit.Dir().DOWN) { 
			return -1*this.countSign(Circuit.Dir().UP, rotation); // :)
		} else if (fromDir == Circuit.Dir().LEFT) {
            switch (rotation) {
                case 270 :
                    return -1;
                case -90 :
                    return -1;
                case 90 :
                    return 1;
                default :
                    throw "construction error"+ (new Error().stack);
            }
		} else { //RIGHT
			return -1*this.countSign(Circuit.Dir().LEFT, rotation); // :)
		}
		//dead code
		throw "semantic error";
	}
    
    checkConditions() {
        var ret = "";
        var failed = false;
        for(var i=0; i<this.json.conditions.length; i++) {
            try {
                ret += this.json.conditions[i].isFulfilled(i);
            }
            catch(error) {
                ret += error;
                failed = true;
            }
        }
        if(failed)
            throw ret;
        return ret;
    }
    
    hasEmpty() {
        $(".taskDropper").each( function( index ){
            if($(this).children().length == 0)
                throw "Az áramkör hiányos. A ["+index+"] indexű elem üres.";
        });
    }
    
    getPicture() {
        return this.table;
    }
    
    initializeDropper() {
        
        $(".dragger").draggable({
            revert: "invalid",
            containment: "document",
            helper: "clone"
        });
        $(".taskDropper.orientNS").droppable({
            accept: ".dragger.orientNS",
            drop: function( event, ui ) {
                $('.components').append($(this).children());
                $(this).html(ui.draggable);
            }
        });
        $(".taskDropper.orientWE").droppable({
            accept: ".dragger.orientWE",
            drop: function( event, ui ) {
                $('.components').append($(this).children());
                $(this).html(ui.draggable);
            }
        });
        $(".components").droppable({
            accept: ".dragger",
            classes: {
                "ui-droppable-active": "active"
            },
            drop: function( event, ui ) {
                $(this).append(ui.draggable);
            }
        });
    }
}
