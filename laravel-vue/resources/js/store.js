import Vue from 'vue';
import Vuex from 'vuex';
import MyAppConstants from './constant';
import * as auth from './Services/auth_service';
Vue.use(Vuex);

export default new Vuex.Store({
    state : {
        isLoggedIn : false,
        apiURL: 'http://127.0.0.1:8000/api',
        serverPath :  'http://127.0.0.1:8000/',
        profile : {},
        MyAppConstants : MyAppConstants
    },
    mutations: {
        authenticate(state, payload) {
            state.isLoggedIn = auth.isLoggedIn();
            state.profile = {};
            if(state.isLoggedIn) {
                state.profile = payload;
            }
        }
    },
    actions:{
        authenticate(context, payload) {
            context.commit('authenticate',payload);
        }
    }
});
