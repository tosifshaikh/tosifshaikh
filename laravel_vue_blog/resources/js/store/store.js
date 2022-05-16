import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersistence from 'vuex-persist';
import SecureLS from 'secure-ls';
const ls = new SecureLS({encodingType: 'rc4',
isCompression: false,
encryptionSecret: 's3cr3t$@1'});
import auth from './modules/auth/index';
import { LOADING_SPINNER_SHOW_MUTATION } from './storeconstants';
Vue.use(Vuex);
const vuexPersist = new VuexPersistence({
  //  asyncStorage: true,
    key: "vuexPersistStorage_default",
    modules: ['auth'],
    //storage: window.localStorage,
   //supportCircular: true,
    storage: {
        getItem: (key) => {
            let data = (ls.get(key)) ? JSON.parse(ls.get(key)) : '';
            console.log(key, 'key',data);
            if (data && data.auth.token != '') {
                return  ls.get(key);
            } else {
                console.log('vuexPersist.storage');
                vuexPersist.storage.removeItem(key);
                //ls.remove(key);
            }

        },
        setItem: (key, value) => {

            ls.set(key, value);
            console.log(key, JSON.parse(value),'setItem');
           // if (!key) {
                //ls.set(key, value)
            //}

        },
        removeItem: (key) => {
            console.log(key,'removestoragerrrr')
            ls.remove(key)
        }
    },
  /*   reducer(val) {
        console.log(val,'reducer');
        if(val.auth.token === null) { // val.user.token (your user token for example)
          return {}
        }
        return val;
      } */
    //  saveState: async (key, state, storage) => {
    //     let data = state;

    //     if (storage && state) {
    //     }

    //     console.log(key, JSON.stringify(ls.data),ls.set('jjjjjj','55555'))
    //     storage.setItem(key,data.auth);
    //   },
    //   restoreState: async function (key, storage) {
    //     let data = await storage.getItem(key);
    //     if (await data) {
    //       try {
    //       } catch (e) {
    //         console.log(e);
    //       }
    //     }
    //     return await data;
    //   }
  })
export default new Vuex.Store({
    plugins: [vuexPersist.plugin],
   /*  mutations: {
        RESTORE_MUTATION: vuexPersist.RESTORE_MUTATION // this mutation **MUST** be named "RESTORE_MUTATION"
      }, */
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
        },

    }

})

