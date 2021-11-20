import {http, httpFile} from "./http_service";

export function createProduct(data){
    return httpFile().post('/Products', data);
}
export function loadProducts() {
    return http().get('/Products');
}
export function deleteProduct(id) {
    return http().delete(`/Products/${id}`);
}
export function updateProduct(id, data) {
    return httpFile().post(`/Products/${id}`, data);
}
export function loadMore(nextpage) {
    return http().get(`/Products?page=${nextpage}`);
}
