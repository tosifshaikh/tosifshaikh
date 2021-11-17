import {http} from "./http_service";
import VueCryptojs from 'vue-cryptojs';
import Vue from "vue";
Vue.use(VueCryptojs);
export function register(user) {
    return http().post('/auth/register', user);
}
export function login(user) {
    return http().post('/auth/login', user).then(response => {
       // if (response.status == 200) {
            setToken(response.data);
        //}
    });
}
function setToken(user) {
    const encryptedText = this.CryptoJS.AES.encrypt("Hi There!", 12345).toString()
    console.log('encryptedText',encryptedText);
   localStorage.setItem('laravel-spa-token', encryptedText);
}
export function isLoggedIn() {
        const token= localStorage.getItem('laravel-spa-token');
        return token != null;
}