import {http, httpFile} from "./http_service";


export function getToDolist(){
    return http().get('/ToDoList');
}
export function addList(data){
    return http().post('/ToDoList', data);
}
export function UpdateList(id,data){
    return httpFile().post(`/ToDoList/${id}`, data);
}
