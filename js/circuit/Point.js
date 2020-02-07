class Point {
    constructor(char) {
        this.name = char;
        this.branches = new Array();
    }

	addBranch(b) {
		this.branches.push(b);
	}
}
