<template>
    <div class="container-fluid px-4">
        <ol class="breadcrumb mt-4">
            <li class="breadcrumb-item active">
                <router-link to="/">
                    Dashboard
                </router-link>
            </li>

            <li class="breadcrumb-item">
                Products
            </li>
        </ol>
        <div class="card mb-3">
            <div class="card-header d-flex">
              <span><i class="fas fa-chart-area"></i>
                    Products Management
              </span>
                <button class="btn btn-primary btn-sm ml-auto" v-on:click="showNewProductModal"><span class="fa fa-plus"></span> Create New</button>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Category</td>
                        <td>Product Name</td>
                        <td>Image</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(Product, index) in Products" :key="index">
                        <td>{{index + 1}}</td>
<!--                        <td>{{Product.category.name}}</td>-->
                        <td>{{findCategory(Product.category_id)}}</td>
                        <td>{{Product.product_name}}</td>
                        <td><img :src="`${$store.state.serverPath}assets/uploads/product/${Product.image}`" :alt="Product.name"  class="img-thumbnail"></td>
                        <td>
                            <button class="btn btn-primary btn-sm" v-on:click="editProduct(Product)"><span class="fa fa-edit" ></span></button>
                            <button class="btn btn-danger btn-sm"  v-on:click="deleteProduct(Product)"><span class="fa fa-trash"></span></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center" v-show="moreExist">
                    <button class="btn btn-primary btn-sm" v-on:click="loadMore"><span class="fa fa-arrow-down" ></span> Load More</button>
                </div>
            </div>
        </div>

        <b-modal ref="ProductModal" hide-footer title="Add New Product">
            <div class="d-block">
                <form v-on:submit.prevent="createProduct">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control"  id="category_id" v-model="ProductData.category_id">
                            <option value="">Choose Category</option>
                            <option v-for="(category,index) in categories" :value="category.id" :key="index">{{ category.name }}</option>
                        </select>
                        <div class="invalid-feedback" v-if="errors.category_id">{{errors.category_id[0]}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Enter Product Name</label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Product Name" v-model="ProductData.name">
                        <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Choose an Image</label>
                        <div v-if="ProductData.image.name">
                            <img ref="newProductImageDisplay" class="img-thumbnail"/>
                        </div>
                        <input type="file" class="form-control" id="image" v-on:change="attachImage" ref="newProductImage">
                        <div class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</div>
                    </div>
                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" v-on:click="hideNewProductModal"> Cancel</button>
                        <button type="submit" class="btn btn-primary" ><span class="fa fa-check"></span> Save</button>
                    </div>

                </form>
            </div>

        </b-modal>
        <b-modal ref="editProductModal" hide-footer title="Edit Product">
            <div class="d-block">
                <form v-on:submit.prevent="updateProduct">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control"  id="category_id" name="category_id" v-model="editProductData.category_id">
                            <option value="">Choose Category</option>
                            <option v-for="(category,index) in categories" :value="category.id" :key="index">{{ category.name }}</option>
                        </select>
                        <div class="invalid-feedback" v-if="errors.category_id">{{errors.category_id[0]}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Enter Product Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Product Name" v-model="editProductData.product_name">
                        <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Choose an Image</label>
                        <div>
                            <img ref="editProductImageDisplay" :src="`${$store.state.serverPath}assets/uploads/product/${editProductData.image}`" :alt="editProductData.name"  class="img-thumbnail">
                        </div>

                        <input type="file" class="form-control" id="image" v-on:change="editAttachImage" ref="editProductImage">
                        <div class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</div>
                    </div>
                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" v-on:click="hideEditProductModal"> Cancel</button>
                        <button type="submit" class="btn btn-primary" ><span class="fa fa-check"></span> Update</button>
                    </div>

                </form>
            </div>

        </b-modal>

    </div>
</template>

<script>
import * as ProductService from '../Services/product_service'
export default {
    name: "Products",
    data() {
        return {
            Products : [],
            categories : [],
            ProductData : {
                category_id : '',
                name :  '',
                image : ''
            },
            editProductData : {},
            moreExist : false,
            nextPage : 0,
            errors : {}
        }
    },
    mounted() {
        this.loadCategories();
        this.loadProducts();
    },
    methods : {
        editProduct(Product) {
            this.editProductData = {...Product};
            this.showEditProductModal();
        },
        loadCategories: async function() {
            try {
                const response = await ProductService.getCategories();
                this.categories = response.data;
            }
            catch (e) {
                this.flashMessage.error({
                    message: 'Some Error Occured!, Please Refresh!',
                    time: 5000,
                });
            }
        },
        loadProducts: async function() {
            try {
                const response = await ProductService.loadProducts();
                this.Products = response.data.data;
                if (response.data.current_page < response.data.last_page) {
                    this.moreExist = true;
                    this.nextPage = response.data.current_page + 1;
                } else {
                    this.moreExist = false;
                }
            }
            catch (e) {
                this.flashMessage.error({
                    message: 'Some Error Occured!, Please Refresh!',
                    time: 5000,
                });
            }
        },
        attachImage(){
            //to use file reader
            this.ProductData.image = this.$refs.newProductImage.files[0];
            let reader = new FileReader();
            reader.addEventListener('load', function () {
                this.$refs.newProductImageDisplay.src = reader.result;
            }.bind(this), false);
            reader.readAsDataURL(this.ProductData.image);
        },editAttachImage(){
            //to use file reader
            this.editProductData.image = this.$refs.editProductImage.files[0];
            let reader = new FileReader();
            reader.addEventListener('load', function () {
                this.$refs.editProductImageDisplay.src = reader.result;
            }.bind(this), false);
            reader.readAsDataURL(this.editProductData.image);
        },
        hideNewProductModal() {
            this.$refs.ProductModal.hide();
        },
        showNewProductModal() {
            this.$refs.ProductModal.show();
        },
        updateProduct :async function() {
            let formData = new FormData();
            formData.append('name', this.editProductData.product_name);
            formData.append('categoryID', this.editProductData.category_id);
            formData.append('image', this.editProductData.image);
            formData.append('id', this.editProductData.id)
            formData.append('_method','PUT')    ;
            try {
                const response = await ProductService.updateProduct(this.editProductData.id, formData);
                this.Products.map(product => {

                    if (product.id == response.data.id) {
                        for (let key in response.data) {
                            product[key] = response.data[key];
                        }
                    }
                }); console.log(this.Products)
                this.hideEditProductModal();
                this.flashMessage.success({
                    message: 'Product Updated Successfully!',
                    time: 5000,
                    blockClass: 'custom-block-class'
                });
                this.editCategoryData = {};
            }catch (e) {
                console.log('update called', e);
            }


        },
        createProduct:async function() {
            this.errors = {};
            let formData = new FormData();
            formData.append('category_id', this.ProductData.category_id);
            formData.append('name', this.ProductData.name);
            formData.append('image', this.ProductData.image);
            try {
                const response = await ProductService.createProduct(formData);
                this.Products.unshift(response.data);

                this.hideNewProductModal();
                this.flashMessage.success({
                    message: 'Product Added Successfully!',
                    time: 5000,
                    blockClass: 'custom-block-class'
                });
                this.ProductData = {
                    name :  '', image : ''
                }
            } catch (e) {
                switch (e.response.status) {
                    case 422:
                        this.errors = e.response.data.errors;
                        console.log('err', this.errors)
                        break;
                    default:
                        break;
                }


            }

        },
        deleteProduct: async function(Product) {
            console.log(Product)
            if (window.confirm(`Are you sure you want to delete the ${Product.product_name} ?`)) {
                try {
                    await ProductService.deleteProduct(Product.id);
                    this.Products = this.Products.filter(obj => {
                        return obj.id != Product.id;
                    });
                    this.flashMessage.success({
                        message: 'Product Deleted Successfully!',
                        time: 5000,
                        blockClass: 'custom-block-class'
                    });
                } catch (e) {
                    this.flashMessage.success({
                        message: e.response.data.message,
                        time: 5000,
                        blockClass: 'custom-block-class'
                    });
                }
            }
        },
        hideEditProductModal() {
            this.$refs.editProductModal.hide();
        }, showEditProductModal() {
            this.$refs.editProductModal.show();
        },
        findCategory(category_id) {
            let categoryName = '';
            this.categories.forEach(category => {
                console.log(category_id,category.id,category.name)
                if (category.id == category_id) {
                    categoryName = category.name;
                }
            });
            return categoryName;
        },
        loadMore: async function() {
            try {
                const response = await ProductService.loadMore(this.nextPage);
                if (response.data.current_page < response.data.last_page) {
                    this.moreExist = true;
                    this.nextPage = response.data.current_page + 1;
                } else {
                    this.moreExist = false;
                }
                response.data.data.forEach(data => {
                    this.Products.push(data);
                });
            }
            catch (e) {
                this.flashMessage.success({
                    message: 'Some error occured during loading more categories',
                    time: 5000,
                    blockClass: 'custom-block-class'
                });
            }
        }

    }
}
</script>

<style scoped>

</style>
