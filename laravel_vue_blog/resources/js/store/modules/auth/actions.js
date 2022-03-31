import SignupValidations from "../../../services/SignupValidations";
import axiosInstance from "../../../services/AxiosTokenInstance";
//LOADING_SPINNER_SHOW_MUTATION
import { LOGIN_ACTION, LOGOUT_ACTION, SET_USER_TOKEN_DATA_MUTATION } from "../../storeconstants";
export default {
    [LOGOUT_ACTION](context) {
       /*  context.commit(SET_USER_TOKEN_DATA_MUTATION, {
            email: '',
            userID: 0,
            token: '',
           // refreshToken: response.data.user.email,
            //expireIn: response.data.user.email,
            fullName: '',

        }); */
    },
    async [LOGIN_ACTION](context, payload) {
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
    }
}
