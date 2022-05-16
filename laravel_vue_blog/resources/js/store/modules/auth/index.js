import mutations from './mutations';
import actions from './actions';
import getters from './getters';

export default {
    namespaced: true,
    state() {
        return {
            name : 'auth',
            token : '',
            email : '',
            userID: 0,
            refreshToken: '',
            expireIn: '',
            fullName : ''
        }
    },
    mutations,
    actions ,
    getters,
}
