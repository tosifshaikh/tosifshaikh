import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        deleteModalObj: {
            showDeleteModal: false,
            deleteURL: '',
            data: null,
            deleteIndex: -1,
            isDeleted: false,
            msg : ''
        },user : false
    },
    getters: {
        getdeleteModalObj(state) {
            return state.deleteModalObj;
        },
        /* loggedInUser(state) {
            return state.user;
        } */
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
        }
    }

})

