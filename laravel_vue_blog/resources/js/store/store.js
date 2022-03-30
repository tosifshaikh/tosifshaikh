import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth/index';
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
        userPermission : null
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
