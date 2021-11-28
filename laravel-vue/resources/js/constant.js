const MyAppConstants = {
    constObj : {
        TIME : 5000
    },
    message : {
        Error : 'Some Error Occured',
        login : {

        }
    },
    getConst: function (text) {
        return this.constObj[text];
    }
}
export default MyAppConstants;
