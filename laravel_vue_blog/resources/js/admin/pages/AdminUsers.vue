<template>
<div>
    <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tags</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#!">Admin Users</a></li>
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
                          <Button @click="addData"><Icon type="md-add" /> Add admin user</Button>
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
                                     <tr v-for="(val,i) in dataList" :key="i">
                                        <td>{{i+1}}</td>
                                        <td>{{val.fullName}}</td>
                                        <td>{{val.email}}</td>
                                        <td>{{userTypes[val.userType]}}</td>
                                        <td>{{val.created_at}}</td>
                                        <td> <Button type="info" size="small" @click="showEditModal(val,i)">Edit</Button>
                                            <Button type="error" size="small" @click="showDeletingModal(val,i)" :loading="customFlags.isLoading">Delete</Button>
                                        </td>
                                    </tr>
                                    <tr v-if="dataList.length <=0">
                                        <td>No Data</td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ADD Modal box-->

                    <Modal
                    v-model="customFlags.AddModal"
                    title="Add Admin" :mask-closable="false" :closable='false'>
                      <div class="space">
                        <Input type="text" v-model="adminUserData.fullName" placeholder="Full Name" style="width: 300px" />
                      </div>
                    <div class="space">
                            <Input type="email" v-model="adminUserData.email" placeholder="Email" style="width: 300px" />
                    </div>
                    <div class="space">
                    <Input type="password" v-model="adminUserData.pass" placeholder="Password" style="width: 300px" />
                    </div>
                    <div class="space">
                    <Select v-model="adminUserData.userType" style="width:200px" placeholder="Select Admin Type">
                    <Option  value="0">Admin</Option>
                    <Option  value="1">Editor</Option>
                    </Select>
                    </div>
                    <div slot="footer">
                    <Button type="default" @click="customFlags.closeEditModal">Close</Button>
                    <Button type="primary" @click="saveData" :disabled="customFlags.isAdding" :loading=' customFlags.isAdding'>{{customFlags.isAdding ? 'Saving...' : 'Save'}}</Button>
                    </div>
                    </Modal>


                 <Modal
                    v-model="customFlags.EditModal"
                    title="Edit Tag" :mask-closable="false" :closable='false'>
                       <div class="space">
                        <Input type="text" v-model="adminUserData.fullName" placeholder="Full Name" style="width: 300px" />
                      </div>
                    <div class="space">
                            <Input type="email" v-model="adminUserData.email" placeholder="Email" style="width: 300px" />
                    </div>
                    <div class="space">
                    <Input type="password" v-model="adminUserData.pass" placeholder="Password" style="width: 300px" />
                    </div>
                    <div class="space">
                    <Select v-model="adminUserData.userType" style="width:200px" placeholder="Select Admin Type">
                    <Option  value="0">Admin</Option>
                    <Option  value="1">Editor</Option>
                    </Select>
                    </div>

                    <div slot="footer">
                    <Button type="default" @click="customFlags.closeEditModal">Close</Button>
                    <Button type="primary" @click="saveData" :disabled="customFlags.isAdding" :loading='customFlags.isAdding'>{{customFlags.isAdding ? 'Editing...' : 'Edit Tag'}}</Button>
                    </div>
                    </Modal>
    <!--
                    <deleteModal /> -->

                   <!--  <Modal v-model="showDeleteModal" width="360">
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
                    </Modal> -->
                </div>
            </div>
     </div>
</div>

</template>

<script>
import deleteModal from '../components/DeleteModal.vue';
import { mapGetters } from 'vuex';

export default {
    name : "adminusers",
    data() {
        return {
            adminUserData : {
                fullName : '',
                pass : '',
                email : '',
                userType : '0',
            },
             customFlags : {
                isAdd : false,
                AddModal :false,
                isEdit : false,
                EditModal :false,
                index: -1,
                deleteItem : {},
                deleteIndex:-1,
                showDeleteModal : false,
                isLoading : false,
                closeEditModal :  false,
            },
            dataList : [],
             headers : [
                   {name : '#'},
                   {name : 'Name'},
                   {name : 'Email'},
                   {name : 'User Type'},
                   {name : 'Created At'},
                   {name : 'Actions'},
            ],
            userTypes : {
                0 : 'Admin',
                1 : 'Editor'
            }





           /*  editAdminUserData  : {
                fullName : '',
                pass : '',
                email : '',
                userType : '',
            }, */
        }
    },

    methods: {
       addData() {
           this.customFlags.isAdd = true;
           this.customFlags.AddModal=true;
           this.customFlags.closeEditModal = true;
        },
         showEditModal(data,index) {
           /*  let obj = {
                id:data.id,
                categoryName : data.category_name
            } */
            console.log('eeee');
            this.customFlags.isEdit = true;
            this.adminUserData = data;
            this.customFlags.EditModal =true;
            this.customFlags.index = index;
        },
         async saveData(){
                 this.customFlags.isAdding = true;
                if (this.customFlags.isAdd) {
                   /*  if (this.adminUserData.fullName.trim() == '') {
                        return this.error('Full Name is required');
                    }
                    if (this.adminUserData.email.trim() == '') {
                        return this.error('Email is required');
                    }
                    if (this.adminUserData.pass.trim() == '') {
                        return this.error('Password is required');
                    }
                    if (this.adminUserData.userType.trim() == '') {
                        return this.error('UserType is required');
                    } */

                    const res =  await this.callApi('post','/app/create_user',this.adminUserData);
                    if (res.status == 201) {
                        this.dataList.unshift(res.data);
                        this.success('User has been added successfully!');
                         this.adminUserData = {};
                    } else{
                    if (res.status == 422) {


                        if (res.data.errors.fullName) {
                            this.info(res.data.errors.fullName[0]);
                        }
                        if (res.data.errors.email) {
                            this.info(res.data.errors.email[0]);
                        }
                        if (res.data.errors.pass) {
                            this.info(res.data.errors.pass[0]);
                        }
                        if (res.data.errors.userType) {
                            this.info(res.data.errors.userType[0]);
                        }

                    } else {
                            this.error('Some error occured');
                        }

                    }
                    this.adminUserData = {};
                    this.customFlags.isAdd = false;
                    this.customFlags.AddModal = false;
                    this.customFlags.isAdding = false;
                }
                if (this.customFlags.isEdit) {
                    if (this.editCategoryData.category_name.trim() == '') {
                        return this.error('Category Name is required');
                    }
                    const res =  await this.callApi('post','/app/edit_category',this.editCategoryData);
                    if (res.status == 200) {
                        this.categories[this.customFlags.index].categoryName=this.editCategoryData.category_name;
                        this.success('Category has been edited successfully!');

                    } else{
                        if (res.status == 422) {
                            if (res.data.errors.category_name) {
                                this.info(res.data.errors.category_name[0]);
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

        showDeletingModal(data,index) {
          /*   this.deleteItem = data;
            this.deleteIndex = index;
            this.showDeleteModal = true; */
            const deleteModalObj = {
                showDeleteModal: true,
                deleteURL: 'app/delete_tag',
                data: data,
                deleteIndex: index,
                msg : 'Tag has been deleted successfully!',
                isDeleted : false
            }
               this.$store.commit('setDeletingModalObj', deleteModalObj);
        },
         async getdata() {

                this.token = window.Laravel.csrfToken;
                const res = await this.callApi('get','/app/get_user');
                if (res.status == 200) {
                    this.dataList = res.data;
                }
            }

    },
       created() {
           console.log(1);
            this.getdata();
       /*  const res = await this.callApi('get','/app/get_tag');
        if (res.status == 200) {
            this.tags = res.data;
        } */
    },
     components: {
        deleteModal,
    },computed: {
  ...mapGetters([
            'getdeleteModalObj',
        ])
    },watch : {
            getdeleteModalObj(value) {
                 console.log(value,'valuetagout');
                if (value.isDeleted) {
                      this.tags.splice(value.deleteIndex,1);
                    console.log(value,'valuetag',value.deleteIndex);
                }

            }
    }
}
</script>

<style>
.space{
    margin-top: 10px;
    margin-bottom: 10px;
}
</style>
