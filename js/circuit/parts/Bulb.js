class Bulb extends Part {
    
    constructor(r,sign) {
        super();
        this.r = r;
        this.sign = sign;
    }
    
    getR() {
        return this.r;
    }
    getU() {
        return 0;
    }
}