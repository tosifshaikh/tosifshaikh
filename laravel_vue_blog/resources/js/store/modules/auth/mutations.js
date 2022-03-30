import { SET_USER_TOKEN_DATA_MUTATION } from "../../storeconstants";

export default {
    [SET_USER_TOKEN_DATA_MUTATION](state, payload) {
        let user = {
                token : payload.token,
                email : payload.email,
                userID: payload.userID,
                refreshToken: '',
                expireIn: '',
                fullName : payload.fullName
        }
        state.user = user;
        console.log(state,payload,'mutation')
    }
}
