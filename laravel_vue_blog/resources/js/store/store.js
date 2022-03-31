import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth/index';
import { LOADING_SPINNER_SHOW_MUTATION } from './storeconstants';
Vue.use(Vuex);
export default new Vuex.Store({
    modules: {
        auth
    },
    state: {
        deleteModalObj: {
            showDeleteModal: false,
            deleteURL: '',
            data: null,
            deleteIndex: -1,
            isDeleted: false,
            msg : ''
        }, user: null,
        userPermission: null,
        showLoading : false
    },
    getters: {
        getdeleteModalObj(state) {
            return state.deleteModalObj;
        },
        getUserPermission(state) {
            return state.userPermission;
        },
         loggedInUser(state) {
            return state.user;
        },
    },
    mutations: {
        [LOADING_SPINNER_SHOW_MUTATION](state,payload) {
            state.showLoading = payload;
        },
        setDeleteModal(state, data) {
            console.log(state, data,'mutation');
             const   deleteModalObj= {
                showDeleteModal: false,
                deleteURL: '',
                data: null,
                deleteIndex: state.deleteModalObj.deleteIndex,
                isDeleted: data,
                msg : ''
            }
            state.deleteModalObj= deleteModalObj;

        },
        setDeletingModalObj(state,data) {
             state.deleteModalObj=data;
        },
        updateUser(state, data) {
             state.user =data;
        },
        setUser(state, data) {
            state.user = data;
        },
        SetUserPermission(state, data) {
            state.userPermission = data;
        }
    }

})

