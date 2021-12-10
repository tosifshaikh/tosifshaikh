import {http, httpFile} from "./http_service";


export function getTolist(){
    return http().get('/ToDoList');
}
export function addList(data){
    return http().post('/ToDoList-ADD', data);
}
