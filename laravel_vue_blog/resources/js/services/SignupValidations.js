import Validations from "./Validations";
export default class SignupValidations {
    constructor(email,password) {
        this.email = email;
        this.password = password;
    }
    checkValidations() {
        let errors = [];
        if (!Validations.checkEmail(this.email)) {
            errors.push({ name : 'email', msg : 'Invalid Email' });
        }
        if (!Validations.minLength(this.password, 6)) {
            errors.push({ name : 'password', msg: 'Password should be of 6 characters' });
        }
        return errors;
    }
    static getErrorMessageFromCode(errorResponse) {
        switch (errorResponse.status) {
            case 401:
                    return errorResponse.data.msg
                break;
            case 422:
                    return errorResponse.data.errors
                break;
            default:
                break;
        }
    }
}
