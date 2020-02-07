class Branch {
	constructor(param, rightEnd) {
        this.parts = new Array();
        this.conditions = new Array();
        if(param instanceof Branch) { //copyconstructor
            for(var i=0; i<param.parts.length; i++)
                this.parts.push(param.parts[i]);
            for(var i=0; i<param.conditions.length; i++)
                this.conditions.push(param.conditions[i]);
            this.leftEnd = param.leftEnd;
            this.rightEnd = param.rightEnd;
        }
        else {
            this.leftEnd=param;
            this.rightEnd=rightEnd;
        }
		//leftEnd.addBranch(this);
		//rightEnd.addBranch(this);
	}
	
    setEnds(leftEnd, rightEnd) {
		this.leftEnd=leftEnd;
		this.rightEnd=rightEnd;
	}
    
	addPart(p) {
		this.parts.push(p);
	}
	
	sumR() { 
		var ret = 1e-10;
        this.parts.forEach(function(part, i, array) {
            
            ret+=part.getR();
            
		});
		return ret;
	}
	
	sumU(from) { //counts only telepek
		var ret = 0;
		this.parts.forEach(function(part, i, array) {
            
            ret+=part.getU();
            
		});
		if (from == this.rightEnd) {
			ret*=-1;
		}
		return ret;
	}
    
    getU() {
        return this.rightEnd.voltage-this.leftEnd.voltage;
    }
	
	otherEnd(end) {
		if (end==this.leftEnd)
			return this.rightEnd;
		return this.leftEnd;
	}
    
    setConditions() {
        for(var i=0; i<this.conditions.length;i++) {
            var field = this.conditions[i];
            if(field.condition.branch == undefined)
                field.condition.branch = this;
        }
    }
}