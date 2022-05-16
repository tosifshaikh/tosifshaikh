import Axios from 'axios';
import store from '../store/store';
import { GET_USER_TOKEN_GETTER } from '../store/storeconstants';
const axiosInstance = Axios.create({});
axiosInstance.interceptors.request.use((config) => {
    let params = new URLSearchParams();
    //let token = store.getters[`auth/${GET_USER_TOKEN_GETTER}`];
   // params.append('auth', token);
    config.params = params;
    console.log(config,'config');
    return config;
});
axiosInstance.defaults.withCredentials = true;
export default axiosInstance;
