class User {
    constructor() {
        this.session = get_session('clerk_id')
        this.exist = row_count('clerk',`id = '${this.session}'`)
    }

    getUser(id){
        return {
            'count':row_count('clerk',`id = '${this.session}'`),
            'result':get_row('clerk',`id = '${this.session}'`)
        }
    }

}