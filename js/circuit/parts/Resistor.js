class Resistor extends Part {
	constructor(r) {
        super();
		this.r=r;
	}
    getR() {
        return this.r;
    }
    getU() {
        return 0;
    }
}