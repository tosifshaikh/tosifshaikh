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
            <button class="btn btn-primary btn-sm ml-auto" v-on:click="showNewCategoryModal"><span class="fa fa-plus"></span>Create New</button>
          </div>
            <div class="card-body">
               <table class="table">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Image</td>
                        <td>Action</td>
                    </tr>
                </thead>
                   <tbody>
                    <tr v-for="(category, index) in categories" :key="index">
                        <td>{{category.id}}</td>
                        <td>{{category.name}}</td>
                        <td>{{category.image}}</td>
                        <td>
                            <button class="btn btn-primary btn-sm"><span class="fa fa-edit"></span></button>
                            <button class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></button>
                        </td>
                    </tr>
                   </tbody>
               </table>
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
                            <img ref="newCategoryImageDisplay" width="150px" />
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
            errors : {}
        }
    },
    mounted() {
        this.loadCategories();
        },
    methods : {
        loadCategories: async function() {
                try {
                    const response = await categoryService.loadCategories();
                   this.categories = response.data.data;
                    console.log(  response.data.data)
                }
                catch (e) {
                    console.log(  e)
                    this.flashMessage.success({
                        message: 'Some Error Occured!, Please Refresh!',
                        time: 5000,
                        blockClass: 'custom-block-class'
                    });
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
        },
        hideNewCategoryModal() {
            this.$refs.categoryModal.hide();
        },
        showNewCategoryModal() {
            this.$refs.categoryModal.show();
        },
        createCategory:async function() {
            let formData = new FormData();
            formData.append('name', this.categoryData.name);
            formData.append('image', this.categoryData.image);
            try {
                const response = await categoryService.createCategory(formData);
                this.hideNewCategoryModal();
                this.flashMessage.success({
                    message: 'Category Added Successfully!',
                    time: 5000,
                    blockClass: 'custom-block-class'
                });
            } catch (e) {
                switch (e.response.status) {
                    case 422:
                    this.errors = e.response.data.errors
                    break;
                    default:
                    break;
                }

                console.log('submitted error',e.response.status)
            }

        }
    }
}
</script>

<style scoped>

</style>
