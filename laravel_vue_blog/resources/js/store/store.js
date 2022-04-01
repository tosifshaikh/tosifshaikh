import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersistence from 'vuex-persist';
import auth from './modules/auth/index';
import { LOADING_SPINNER_SHOW_MUTATION } from './storeconstants';
Vue.use(Vuex);
const vuexPersist = new VuexPersistence({
    asyncStorage: true,
    key: "vuexPersistStorage_default",
    storage: window.localStorage,
    saveState: async (key, state, storage) => {
        let data = state;

        if (storage && data) {
        }
        console.log(key, data)
        storage.setItem(key, data);
      },
      restoreState: async function (key, storage) {
        let data = await storage.getItem(key);
        if (await data) {
          try {
          } catch (e) {
            console.log(e);
          }
        }
        return await data;
      }
  })
export default new Vuex.Store({
    plugins: [vuexPersist.plugin],
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

