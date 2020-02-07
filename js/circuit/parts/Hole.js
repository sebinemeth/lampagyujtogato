class Hole extends Part {
    constructor(field,fromDir) {
        super();
		this.field = field;
        this.fromDir = fromDir;
        this.part = undefined;
        var orient;
        switch(fromDir) {
            case 1 :
                orient = "WE";
                break;
            case 4 :
                orient = "WE";
                break;
            default:
                orient = "NS";
        }
        this.field.data("hole",this);
        this.field.addClass("orient"+orient);
	}
    
    isEmpty() {
        return this.field.children().length == 0;
    }
    getR() {
        //if(!this.part)
            this.assocPart();
        return this.part.getR();
    }
    getU() {
        //if(!this.part)
            this.assocPart();
        return this.part.getU();
    }
    isA(type) {
        return this.field.find(".dragger").hasClass(type);
    }
    getVal() {
        var ret = parseFloat(this.field.find(".elemValue").html());
        return ret;
    }
    getSign() {
        var tr = this.field.find('.tdImage').css('transform');
        var values = tr.split('(')[1];
        values = values.split(')')[0];
        values = values.split(',');
        var a = parseFloat(values[0]);
        var b = parseFloat(values[1]);
        var c = parseFloat(values[2]);
        var d = parseFloat(values[3]);
        
        var fi = Math.asin(b)*180/Math.PI;
        return Circuit.countSign(this.fromDir,fi);
    }
    assocPart() {
        if(this.isA("resistor"))
            this.part = new Resistor(this.getVal());
        if(this.isA("v_source"))
            this.part = new VSource(this.getVal()*this.getSign());
        //console.log("assoced");
    }
}