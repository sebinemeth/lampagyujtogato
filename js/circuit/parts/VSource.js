class VSource extends Part {

    //U: left to right

	constructor(u) {
        super();
		this.u = u;
	}
    getR() {
        return 0;//1e-20;
    }
    getU() {
        return this.u;
    }
}
