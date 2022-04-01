import { GET_USER_TOKEN_GETTER, IS_USER_AUTHENTICATE_GETTER } from "../../storeconstants";

export default {
    [GET_USER_TOKEN_GETTER]: state => {
        console.log(state,'GET_USER_TOKEN_GETTER')
        return state.token;
    },
    [IS_USER_AUTHENTICATE_GETTER](state) {
        console.log(state,'IS_USER_AUTHENTICATE_GETTER')
        return state.token;
    }
}
