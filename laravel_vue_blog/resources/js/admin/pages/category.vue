<template>
<div>
    <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Category</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#!">Category</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
         <div class="row">
            <!-- [ basic-table ] start -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
<!--                           <Button @click="AddModal=true"><Icon type="md-add" /> Add Category</Button>
 -->                          <Button @click="addData"><Icon type="md-add" /> Add Category</Button>
                       <!--  <h5>Basic Table</h5>
                        <span class="d-block m-t-5">use class <code>table</code> inside table element</span> -->
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th v-for="(header,indx) in headers" :key="indx">{{header.name}}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(category,i) in categories" :key="i">
                                        <td>{{i+1}}</td>
                                        <td>{{category.category_name}}</td>
                                        <td><img :src="category.iconImage" alt="" height="50px" width="50px"/></td>
                                        <td>{{category.created_at}}</td>
                                        <td> <Button type="info" size="small" @click="showEditModal(category,i)">Edit</Button>
                                            <Button type="error" size="small" @click="showDeletingModal(category,i)" :loading="category.isDeleteting">Delete</Button>
                                        </td>
                                    </tr>
                                    <tr v-if="categories.length <=0">
                                        <td>No Data</td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ADD Modal box    -->

                    <Modal
                    v-model="customFlags.AddModal"
                    title="Add Category" :mask-closable="false" :closable='false'>
                    <Input v-model="categoryData.categoryName" placeholder="Add category Name" />
                    <div class="space"></div>
                    <Upload
                    ref="uploads"
                    type="drag" :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                    :on-success="handleSuccess"
                    :format="['jpg','jpeg','png']"
                    :max-size="2048"
                    :on-error="handleError"
                    :on-exceeded-size="handleMaxSize"
                    :on-format-error="handleFormatError"
                    action="/app/upload">
                    <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag files here to upload</p>
                    </div>
                    </Upload>
                        <div class="demo-upload-list" v-if="categoryData.iconImage">

                        <img :src="`/uploads/category/${categoryData.iconImage}`" alt="">
                        <div class="demo-upload-list-cover">
                        <Icon type="ios-eye-outline"></Icon>
                        <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                        </div>

                        </div>
                   <!--  <div class="image_thumb" v-if="categoryData.iconImage">
                        <img :src="`/uploads/${categoryData.iconImage}`" alt="">
                    </div> -->

                    <div slot="footer">
                    <Button type="default" @click="customFlags.AddModal=false">Close</Button>
                    <Button type="primary" @click="saveData" :disabled="isAdding" :loading='isAdding'>{{isAdding ? 'Saving...' : 'Save'}}</Button>
                    </div>
                    </Modal>


                     <Modal
                    v-model="customFlags.EditModal"
                    title="Edit Category" :mask-closable="false" :closable='false'>
                       <Input v-model="editCategoryData.categoryName" placeholder="Edit category Name" />
                        <div class="space"></div>
                         <Upload
                    ref="uploads"
                    type="drag" :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                    :on-success="handleSuccess"
                    :format="['jpg','jpeg','png']"
                    :max-size="2048"
                    :on-error="handleError"
                    :on-exceeded-size="handleMaxSize"
                    :on-format-error="handleFormatError"
                    action="/app/upload">
                    <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag files here to upload</p>
                    </div>
                    </Upload>
                     <div class="demo-upload-list" v-if="editCategoryData.iconImage">

                        <img :src="`/uploads/category/${editCategoryData.iconImage}`" alt="">
                        <div class="demo-upload-list-cover">
                        <Icon type="ios-eye-outline"></Icon>
                        <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                        </div>

                        </div>

                    <div slot="footer">
                    <Button type="default" @click="customFlags.EditModal=false">Close</Button>
                    <Button type="primary" @click="saveData" :disabled="isAdding" :loading='isAdding'>{{isAdding ? 'Updating...' : 'Update'}}</Button>
                    </div>
                    </Modal>


                    <Modal v-model="showDeleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                    <p>Are you sure you want to delete this tag?.</p>
                    </div>
                    <div slot="footer">
                    <Button type="error" size="large" long :loading="modalLoading" @click="deleteTag">Delete</Button>
                    </div>
                    </Modal>
                </div>
            </div>
     </div>
</div>

</template>

<script>
export default {
    name : "category",
    data() {
        return {
            categoryData : {
                iconImage : '',
                categoryName : ''

            }, categories : [],
            headers : [
                   {name : '#'},
                   {name : 'Icon Image'},
                   {name : 'Category Name'},
                   {name : 'Created At'},
                   {name : 'Actions'},
            ],

            isAdding : false,
            editCategoryData : {
                categoryName : '',
                iconImage : ''
            },
            showDeleteModal : false,
            deleteItem : {},
            deleteIndex:-1,
            modalLoading : false,
            token : '',

            customFlags : {
                isAdd : false,
                AddModal :false,
                isEdit : false,
                EditModal :false,
                index: -1,

            }
        }
    },
    methods: {
        addData() {
           this.customFlags.isAdd = true;
           this.customFlags.AddModal=true;


/*
            if (this.categoryData.categoryName.trim() == '') {
                return this.error('Category Name is required');
            }
              const res =  await this.callApi('post','/app/create_category',this.categoryData);
              if (res.status == 201) {
                 // this.tags.unshift(res.data);
                  this.success('Category has been added successfully!');
                  this.AddModal = false;
              } else{
                  if (res.status == 422) {
                      if (res.data.errors.categoryName) {
                          this.info(res.data.errors.categoryName[0]);
                      }
                   } else {
                        this.error('Some error occured');
                   }

              }
              this.categoryData = {}; */
        },
       editData() {
           this.customFlags.isEdit = true;
           this.customFlags.EditModal = true;


          /*   if (this.editCategoryData.categoryName.trim() == '') {
                return this.error('Category Name is required');
            }
              const res =  await this.callApi('post','/app/edit_category',this.editCategoryData);
              if (res.status == 200) {
                  this.categories[this.index].categoryName=this.editCategoryData.categoryName;
                  this.success('Category has been edited successfully!');
                  this.EditModal = false;
              } else{
                  if (res.status == 422) {
                      if (res.data.errors.categoryName) {
                          this.info(res.data.errors.categoryName[0]);
                      }
                   } else {
                        this.error('Some error occured');
                   }

              } */
        },
        async saveData(){
                if (this.customFlags.isAdd) {
                    if (this.categoryData.categoryName.trim() == '') {
                        return this.error('Category Name is required');
                    }
                    if (this.categoryData.iconImage.trim() == '') {
                        return this.error('Icon image is required');
                    }
                   // this.categoryData.iconImage = ${this.categoryData.iconImage}`;
                    const res =  await this.callApi('post','/app/create_category',this.categoryData);
                    if (res.status == 201) {
                         this.categories.unshift(res.data);
                        this.success('Category has been added successfully!');
                         this.categoryData.iconImage = ''
                    } else{
                    if (res.status == 422) {
                        if (res.data.errors.categoryName) {
                            this.info(res.data.errors.categoryName[0]);
                        }if (res.data.errors.iconImage) {
                            this.info(res.data.errors.iconImage[0]);
                        }
                    } else {
                            this.error('Some error occured');
                        }

                    }
                    this.categoryData = {};
                    this.customFlags.isAdd = false;
                    this.customFlags.AddModal = false;
                }
                if (this.customFlags.isEdit) {
                    if (this.editCategoryData.categoryName.trim() == '') {
                        return this.error('Category Name is required');
                    }
                    const res =  await this.callApi('post','/app/edit_category',this.editCategoryData);
                    if (res.status == 200) {
                        this.categories[this.customFlags.index].categoryName=this.editCategoryData.categoryName;
                        this.success('Category has been edited successfully!');

                    } else{
                        if (res.status == 422) {
                            if (res.data.errors.categoryName) {
                                this.info(res.data.errors.categoryName[0]);
                            }
                        } else {
                            this.error('Some error occured');
                        }

                    }
                    this.customFlags.isEdit = false;
                    this.customFlags.EditModal = false;
                    this.editCategoryData= {};
                }

        },
        showEditModal(data,index) {
            let obj = {
                id:data.id,
                categoryName : data.category_name
            }
            this.editCategoryData = obj;
            this.customFlags.EditModal =true;
            this.customFlags.index = index;
        },
        showDeletingModal(tag,index) {
            this.deleteItem = tag;
            this.deleteIndex = index;
            this.showDeleteModal = true;
        },
        async deleteTag() {
             this.modalLoading = true;
                const res= await this.callApi('post','app/delete_category', this.deleteItem);
                if (res.status == 200) {
                    this.categories.splice(this.deleteIndex,1);
                     this.success('Tag has been deleted successfully!');
                } else {
                     this.error();
                }
                 this.modalLoading = false;
                       this.showDeleteModal = false;

        },
        handleSuccess (res, file) {
                this.categoryData.iconImage = res;
            },
            handleFormatError (file) {
                this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                });
            },
            handleMaxSize (file) {
                this.$Notice.warning({
                    title: 'Exceeding file size limit',
                    desc: 'File  ' + file.name + ' is too large, no more than 2M.'
                });
            },
            handleError() {
                    this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: `${file.errors.file.length ?  file.errors.file[0]: 'Something went wrong!'}`
                });
            },
           async deleteImage() {
                let image= this.categoryData.iconImage;
                this.categoryData.iconImage= '';
                this.$refs.uploads.clearFiles();
                const res= await this.callApi('post','app/delete_image',{imageName : image});
                if(res.status!=200) {
                        this.categoryData.iconImage = image;
                        this.error();
                }
            }

    },
      async created() { console.log(this.headers)
          this.token = window.Laravel.csrfToken;
        const res = await this.callApi('get','/app/get_category');
        if (res.status == 200) {
            this.categories = res.data;
        }
    },
}
</script>

<style>
.space{
    margin-top: 10px;
    margin-bottom: 10px;
}
.image_thumb{
    width: 50px;
}
 .demo-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>
