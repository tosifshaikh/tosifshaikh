import mutations from './mutations';
import actions from './actions';
import getters from './getters';

export default {
    namespaced: true,
    state() {
        return {
            name : 'auth',
            user: {
                token : '',
                email : '',
                userID: '',
                refreshToken: '',
                expireIn: '',
                fullName : ''
            }
        }
    },
    mutations : mutations,
    actions : actions,
    getters : getters,
}
