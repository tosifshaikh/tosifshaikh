import { SET_AUTO_LOGOUT_MUTATION, SET_USER_TOKEN_DATA_MUTATION } from '../../storeconstants';

export default {
    [SET_USER_TOKEN_DATA_MUTATION](state, payload) {
        state.email = payload.email;
        state.userID = payload.userID;
        state.token = payload.token;
        state.fullName = payload.fullName;
        state.autoLogout = false;
        state.expireIn = payload.expireIn;
    },
    setDeleteState(state, payload) {
        state.email = payload.email;
        state.userID = payload.userID;
        state.token = payload.token;
        state.fullName = payload.fullName;
    },
    [SET_AUTO_LOGOUT_MUTATION] (state) {
        state.autoLogout = true;
    }

}
