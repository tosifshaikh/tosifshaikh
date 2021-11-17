import Vue from 'vue';
import Vuex from 'vuex';
import * as auth from './Services/auth_service';
Vue.use(Vuex);
export default new Vuex.Store({
    state : {
        isLoggedIn : false,
        apiURL: 'http://127.0.0.1:8000/api',
        serverPath :  'http://127.0.0.1:8000/',
        profile : {}
    },
    mutations: {
        authenticate(state, payload) {
            state.isLoggedIn = auth.isLoggedIn();
        }
    },
    actions:{}
});
