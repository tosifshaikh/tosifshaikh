import {http, httpFile} from "./http_service";


export function getTolist(){
    return http().get('/ToDoList');
}
