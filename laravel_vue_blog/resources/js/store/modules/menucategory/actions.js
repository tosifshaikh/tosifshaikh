import { GET_DATA, SAVE_DATA  } from "./ActionConstants";

export default {
    async [SAVE_DATA](context,payload) {
        let response = "";
        try {
            response = await axios({
                method: payload.method,
                url: payload.URL,
                data: payload.data,
                responseType: 'json'
                , headers: {
                    Accept: 'application/json',
                }
            });
            if (response.status == 200) {
                return response;
            }
        } catch (error) {
            throw error;
        }
        return response;
       
    },
    async [GET_DATA](context,payload) {

    }
}