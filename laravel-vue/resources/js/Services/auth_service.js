import {http} from "./http_service";
<<<<<<< HEAD
import VueCryptojs from 'vue-cryptojs';
import Vue from "vue";
Vue.use(VueCryptojs);
=======
import crypto from "crypto-js";
//import VueCryptojs from 'vue-cryptojs';
//import Vue from "vue";
//Vue.use(VueCryptojs);
>>>>>>> laravel-vue
export function register(user) {
    return http().post('/auth/register', user);
}
export function login(user) {
    return http().post('/auth/login', user).then(response => {
<<<<<<< HEAD
       // if (response.status == 200) {
            setToken(response.data);
        //}
    });
}
function setToken(user) {
    const encryptedText = this.CryptoJS.AES.encrypt("Hi There!", 12345).toString()
    console.log('encryptedText',encryptedText);
   localStorage.setItem('laravel-spa-token', encryptedText);
=======
        if (response.status == 200) {
            setToken(response.data);
        }
    });
}
export function logout() {
     http().get('auth/logout');
    localStorage.removeItem('laravel-spa-token');
}
function setToken(user) {
    let token = crypto.AES.encrypt(JSON.stringify(user), "password@111").toString();
  //  const encryptedText = crypto.AES.encrypt("Hi There!", 12345).toString()
   localStorage.setItem('laravel-spa-token', token);
>>>>>>> laravel-vue
}
export function isLoggedIn() {
        const token= localStorage.getItem('laravel-spa-token');
        return token != null;
<<<<<<< HEAD
}
=======
}
export function getAccessToken() {
       // const token= localStorage.getItem('laravel-spa-token');
      //  return token != null;
    const token= localStorage.getItem('laravel-spa-token');
    if (!token) {
        return null;
    }
    const tokenData =JSON.parse(crypto.AES.decrypt(token, 'password@111').toString(crypto.enc.Utf8));
    return tokenData.acccess_token;

}
>>>>>>> laravel-vue
