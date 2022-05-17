import { SET_USER_TOKEN_DATA_MUTATION } from "../../storeconstants";

export default {
    [SET_USER_TOKEN_DATA_MUTATION](state, payload) {
        state.email = payload.email;
        state.userID = payload.userID;
        state.token = payload.token;
        state.fullName = payload.fullName;
    },
    setDeleteState(state, payload) {
        state.email = payload.email;
        state.userID = payload.userID;
        state.token = payload.token;
        state.fullName = payload.fullName;
    }

}
