import { LOGIN_ACTION, SET_USER_TOKEN_DATA_MUTATION } from "../../storeconstants";

export default {
   async [LOGIN_ACTION](context, payload) {
        let response =  await axios({
            method: payload.method,
            url: payload.URL,
             data: payload.data,
             //responseType: 'json',
             headers: {
                //'Content-Type': 'multipart/form-data'
              }
        });
        if (response.status == 200) {
            context.commit(SET_USER_TOKEN_DATA_MUTATION, {
                email: response.data.user.email,
                userID: response.data.user.userId,
                token: response.data.user.token,
               // refreshToken: response.data.user.email,
                //expireIn: response.data.user.email,
                fullName: response.data.user.fullName,

            });
        }
    }
}
