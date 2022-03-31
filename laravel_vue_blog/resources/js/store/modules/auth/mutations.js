import { SET_USER_TOKEN_DATA_MUTATION } from "../../storeconstants";

export default {
    [SET_USER_TOKEN_DATA_MUTATION](state, payload) {
        console.log(state, payload, 'mutation')
        state.email = payload.email;
        state.userID = payload.userID;
        state.token = payload.token.id;
        state.fullName = payload.fullName;
    }
}
