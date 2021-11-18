import {http, httpFile} from "./http_service";

export function userScope() {
    return http().post('/user-scope');
}
export function adminScope() {
    return http().post('/admin-scope');
}
