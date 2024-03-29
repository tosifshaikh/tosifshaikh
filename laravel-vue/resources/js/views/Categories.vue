<template>
    <div class="container-fluid px-4">
        <ol class="breadcrumb mt-4">
            <li class="breadcrumb-item active">
                <router-link to="/">
                    Dashboard
                </router-link>
            </li>

            <li class="breadcrumb-item">
                Categories
            </li>
        </ol>
      <div class="card mb-3">
          <div class="card-header d-flex">
              <span><i class="fas fa-chart-area"></i>
                    Categories Management
              </span>
            <button class="btn btn-primary btn-sm ml-auto" v-on:click="showNewCategoryModal"><span class="fa fa-plus"></span> Create New</button>
          </div>
            <div class="card-body">
               <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Image</td>
                        <td>Action</td>
                    </tr>
                </thead>
                   <tbody>
                    <tr v-for="(category, index) in categories" :key="index">
                        <td>{{index + 1}}</td>
                        <td>{{category.name}}</td>
                        <td><img :src="`${$store.state.serverPath}assets/uploads/category/${category.image}`" :alt="category.name"  class="img-thumbnail"></td>
                        <td>
                            <button class="btn btn-primary btn-sm" v-on:click="editCategory(category)"><span class="fa fa-edit" ></span></button>
                            <button class="btn btn-danger btn-sm"  v-on:click="deleteCategory(category)"><span class="fa fa-trash"></span></button>
                        </td>
                    </tr>
                   </tbody>
               </table>
                <div class="text-center" v-show="moreExist">
                    <button class="btn btn-primary btn-sm" v-on:click="loadMore"><span class="fa fa-arrow-down" ></span> Load More</button>
                </div>
            </div>
      </div>

        <b-modal ref="categoryModal" hide-footer title="Add New Category">
            <div class="d-block">
                <form v-on:submit.prevent="createCategory">
                    <div class="mb-3">
                        <label for="name" class="form-label">Enter Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Name" v-model="categoryData.name">
                        <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Choose an Image</label>
                        <div v-if="categoryData.image.name">
                            <img ref="newCategoryImageDisplay" class="img-thumbnail"/>
                        </div>
                        <input type="file" class="form-control" id="image" v-on:change="attachImage" ref="newCategoryImage">
                        <div class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</div>
                    </div>
                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" v-on:click="hideNewCategoryModal"> Cancel</button>
                        <button type="submit" class="btn btn-primary" ><span class="fa fa-check"></span> Save</button>
                    </div>

                </form>
            </div>

        </b-modal>
        <b-modal ref="editCategoryModal" hide-footer title="Edit Category">
            <div class="d-block">
                <form v-on:submit.prevent="updateCategory">
                    <div class="mb-3">
                        <label for="name" class="form-label">Enter Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Name" v-model="editCategoryData.name">
                        <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Choose an Image</label>
                        <div>
                            <img ref="editCategoryImageDisplay" :src="`${$store.state.serverPath}assets/uploads/category/${editCategoryData.image}`" :alt="editCategoryData.name"  class="img-thumbnail">
                        </div>

                        <input type="file" class="form-control" id="image" v-on:change="editAttachImage" ref="editCategoryImage">
                        <div class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</div>
                    </div>
                    <hr>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" v-on:click="hideEditCategoryModal"> Cancel</button>
                        <button type="submit" class="btn btn-primary" ><span class="fa fa-check"></span> Update</button>
                    </div>

                </form>
            </div>

        </b-modal>

    </div>
</template>

<script>
import * as categoryService from '../Services/category_service'
export default {
    name: "Categories",
    data() {
        return {
            categories : [],
            categoryData : {
                name :  '',
                image : ''
            },
            editCategoryData : {},
            moreExist : false,
            nextPage : 0,
            errors : {}
        }
    },
    mounted() {
        this.loadCategories();
        },
    methods : {
        editCategory(category) {
            this.editCategoryData = {...category};
           this.showEditCategoryModal();
        },
        loadCategories: async function() {
                try {
                    const response = await categoryService.loadCategories();
                   this.categories = response.data.data;
                    if (response.data.current_page < response.data.last_page) {
                        this.moreExist = true;
                        this.nextPage = response.data.current_page + 1;
                    } else {
                        this.moreExist = false;
                    }
                }
                catch (e) {
                    switch (e.response.status) {
                        case 422:
                            this.errors = e.response.data.errors;
                            break;
                        default:
                            this.flashMessage.error({
                                message: 'Some Error Occured!, Please Refresh!',
                                time: 5000,
                                blockClass: 'custom-block-class'
                            });
                            break;
                    }


                }
        },
        attachImage(){
            //to use file reader
            this.categoryData.image = this.$refs.newCategoryImage.files[0];
            let reader = new FileReader();
            reader.addEventListener('load', function () {
                this.$refs.newCategoryImageDisplay.src = reader.result;
            }.bind(this), false);
            reader.readAsDataURL(this.categoryData.image);
        },editAttachImage(){
            //to use file reader
            this.editCategoryData.image = this.$refs.editCategoryImage.files[0];
            let reader = new FileReader();
            reader.addEventListener('load', function () {
                this.$refs.editCategoryImageDisplay.src = reader.result;
            }.bind(this), false);
            reader.readAsDataURL(this.editCategoryData.image);
        },
        hideNewCategoryModal() {
            this.$refs.categoryModal.hide();
        },
        showNewCategoryModal() {
            this.$refs.categoryModal.show();
        },
        updateCategory :async function() {
            this.errors = {};
            let formData = new FormData();
            formData.append('name', this.editCategoryData.name);
            formData.append('image', this.editCategoryData.image);
            formData.append('_method','PUT')    ;
            try {
                const response = await categoryService.updateCategory(this.editCategoryData.id, formData);
                this.categories.map(category => {
                    if (category.id == response.data.id) {
                        for (let key in response.data) {
                            category[key] = response.data[key];
                        }
                    }
                });
                this.hideEditCategoryModal();
                this.flashMessage.success({
                    message: 'Category Updated Successfully!',
                    time: 5000,
                    blockClass: 'custom-block-class'
                });
                this.editCategoryData = {};
            }catch (e) {
                this.flashMessage.success({
                    message: e.response.data.message,
                    time: 5000,
                    blockClass: 'custom-block-class'
                });
            }


        },
        createCategory:async function() {
            this.errors = {};
            let formData = new FormData();
            formData.append('name', this.categoryData.name);
            formData.append('image', this.categoryData.image);
            try {
                const response = await categoryService.createCategory(formData);
                this.categories.unshift(response.data);

                this.hideNewCategoryModal();
                this.flashMessage.success({
                    message: 'Category Added Successfully!',
                    time: 5000,
                    blockClass: 'custom-block-class'
                });
                this.categoryData = {
                    name :  '', image : ''
                }
            } catch (e) {
                switch (e.response.status) {
                    case 422:
                    this.errors = e.response.data.errors;
                    break;
                    default:
                    break;
                }


            }

        },
        deleteCategory: async function(category) {
            console.log(category)
            if (window.confirm(`Are you sure you want to delete the ${category.name} ?`)) {
                try {
                    await categoryService.deleteCategory(category.id);
                    this.categories = this.categories.filter(obj => {
                        return obj.id != category.id;
                    });
                    this.flashMessage.success({
                        message: 'Category Deleted Successfully!',
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
        hideEditCategoryModal() {
            this.$refs.editCategoryModal.hide();
        }, showEditCategoryModal() {
            this.$refs.editCategoryModal.show();
        },
        loadMore: async function() {
            try {
                const response = await categoryService.loadMore(this.nextPage);
                if (response.data.current_page < response.data.last_page) {
                    this.moreExist = true;
                    this.nextPage = response.data.current_page + 1;
                } else {
                    this.moreExist = false;
                }
                response.data.data.forEach(data => {
                    this.categories.push(data);
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
