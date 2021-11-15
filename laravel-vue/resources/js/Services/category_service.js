import {http, httpFile} from "./http_service";

export function createCategory(data){
    return httpFile().post('/categories', data);
}
export function loadCategories() {
    return httpFile().get('/categories');
}
export function deleteCategory(id) {
    return httpFile().delete(`/categories/${id}`);
}
export function updateCategory(id, data) {
    return httpFile().post(`/categories/${id}`, data);
}
