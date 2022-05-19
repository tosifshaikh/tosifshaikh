import SignupValidations from "../../../services/SignupValidations";
import axiosInstance from "../../../services/AxiosTokenInstance";
//LOADING_SPINNER_SHOW_MUTATION
import {
    AUTH_ACTION,
    AUTO_LOGIN_ACTION, AUTO_LOGOUT_ACTION,
    GET_AUTH_DATA,
    LOGIN_ACTION,
    LOGOUT_ACTION, SET_AUTO_LOGOUT_MUTATION,
    SET_USER_TOKEN_DATA_MUTATION,
} from '../../storeconstants';
let timer= '';
export default {
    async [LOGOUT_ACTION](context,payload) {

        let userData = this.getters[`auth/${GET_AUTH_DATA}`];
        let response = "";
        try {
            response = await axios({
                method: payload.method,
                url: payload.URL,
                data : userData
                //responseType: 'json',
                ,headers: {
                    Accept:'application/json',
                }
                    //         //'Content-Type': 'multipart/form-data'
                    //       }
            })/*.then(()=> {
                location.href = '/login';
            });;*/
        } catch (error) {
            throw error;
        }
        if (response.status == 200) {
            context.commit('setDeleteState',{
                email: '',
                userID: 0,
                token: '',
               // refreshToken: response.data.user.email,
                expireIn: '',
                fullName: '',

              });

            if(timer) {
                clearTimeout(timer);
            }
           // console.log(timer,'timer');return
            location.href = '/login';
        }


       /*  context.commit('setDeleteState',{
            email: '',
            userID: 0,
            token: '',
           // refreshToken: response.data.user.email,
            //expireIn: response.data.user.email,
            fullName: '',

          }); */
         /*  context.commit(SET_USER_TOKEN_DATA_MUTATION, {
            email: '',
            userID: 0,
            token: '',
           // refreshToken: response.data.user.email,
            //expireIn: response.data.user.email,
            fullName: '',

          }); */

    },
    async [AUTO_LOGOUT_ACTION](context) {
         context.dispatch(LOGOUT_ACTION,{method : 'post', URL : 'app/logout'});
        context.commit(SET_AUTO_LOGOUT_MUTATION);
         },
    [AUTO_LOGIN_ACTION](context) {
        let userData = this.getters[`auth/${GET_AUTH_DATA}`];
        if (userData.token) {
            console.log(userData,'userData', new Date().getTime());

            let expirationTime = +userData.expireIn - new Date().getTime();

            if (expirationTime < 10000) {
                //you can refresh the token or logout
              // console.log(expirationTime,'expirationTime',userData.expireIn,new
                // Date().getTime());return
               //  context.dispatch(AUTO_LOGOUT_ACTION);
            } else {
                timer = setTimeout(() => {   context.dispatch(AUTO_LOGOUT_ACTION);}, expirationTime)
                /*timer= new Promise(function(resolve, reject) {
                    setTimeout(() => {
                        console.log('10 seconds Timer expired!!!');
                        context.dispatch(AUTO_LOGOUT_ACTION);
                    }, expirationTime)
                });*/
            }
            context.commit(SET_USER_TOKEN_DATA_MUTATION,userData);
        }
    },
    async [AUTH_ACTION](context, payload) {
        let response = "";
        //  context.commit(LOADING_SPINNER_SHOW_MUTATION,true,{root : true});
        try {
            response =  await axiosInstance({
                method: payload.method,
                 url: payload.URL,
                 data: payload.data,
                 //responseType: 'json',
            //      headers: {
            //         //'Content-Type': 'multipart/form-data'
            //       }
            });
          /*   response = await axios({
                method: payload.method,
                url: payload.URL,
                data: payload.data,
                //responseType: 'json',
                headers: {
                    //'Content-Type': 'multipart/form-data'
                },
            }); */
        } catch (error) {
            // context.commit(LOADING_SPINNER_SHOW_MUTATION,false,{root : true});
            let errorMessage = SignupValidations.getErrorMessageFromCode(
                error.response
            );
            throw errorMessage;
        }
        // context.commit(LOADING_SPINNER_SHOW_MUTATION,false,{root : true});

        if (response.status == 200) {
            let expirationTime = +response.data.user.expireIn * 1000;

            timer = setTimeout(() => {   context.dispatch(AUTO_LOGOUT_ACTION);}, expirationTime)

            /*timer= setTimeout(()=> {
                 console.log('timeout')
                 context.dispatch(AUTO_LOGOUT_ACTION);
             },expirationTime);
 */
            /*timer= new Promise(function(resolve, reject) {
                setTimeout(() => {
                    console.log('10 seconds Timer expired!qqqq!!');
                    context.dispatch(AUTO_LOGOUT_ACTION);
                }, expirationTime)
            });*/
            console.log(expirationTime,'expirationTime',timer);
            let tokenData = {
                email: response.data.user.email,
                userID: response.data.user.userId,
                token: response.data.user.token,
                refreshToken: "",
                expireIn: expirationTime,
                fullName: response.data.user.fullName,
            }
            context.commit(SET_USER_TOKEN_DATA_MUTATION, tokenData);

        }
    },
    async [LOGIN_ACTION](context, payload) {
        // return context.dispatch(AUTH_ACTION,{...payload,url : payload.URL});
        return context.dispatch(AUTH_ACTION, payload);
        /*
        let response = '';
      //  context.commit(LOADING_SPINNER_SHOW_MUTATION,true,{root : true});
        try {
            // axiosInstance({
            //     method: payload.method,
            //     url: payload.URL,
            //      data: payload.data,
            //      //responseType: 'json',
            //      headers: {
            //         //'Content-Type': 'multipart/form-data'
            //       }
            // });
            response =  await axios({
                method: payload.method,
                url: payload.URL,
                 data: payload.data,
                 //responseType: 'json',
                 headers: {
                    //'Content-Type': 'multipart/form-data'
                  }
            });
        } catch (error) {
           // context.commit(LOADING_SPINNER_SHOW_MUTATION,false,{root : true});
            let errorMessage = SignupValidations.getErrorMessageFromCode(error.response);
            throw errorMessage;

        }
       // context.commit(LOADING_SPINNER_SHOW_MUTATION,false,{root : true});
        if (response.status == 200) {
            context.commit(SET_USER_TOKEN_DATA_MUTATION, {
                email: response.data.user.email,
                userID: response.data.user.userId,
                token: response.data.user.token,
                refreshToken: '',
                expireIn: '',
                fullName: response.data.user.fullName,

            });
        }
     */
    },
};
