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
                          <Button @click="addData"><Icon type="md-add" v-if="isWritePermitted"/> Add admin user</Button>
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
                                        <td>{{val.role.roleName}}</td>
                                        <td>{{val.created_at}}</td>
                                        <td> <Button type="info" size="small" @click="showEditModal(val,i)" v-if="isUpdatePermitted">Edit</Button>
                                            <Button type="error" size="small" @click="showDeletingModal(val,i)" :loading="customFlags.isLoading" v-if="isDeletePermitted">Delete</Button>
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
                    <Select v-model="adminUserData.role_id" style="width:200px" placeholder="Select Role Type">
                    <Option  :value="r.id" v-for="(r,i) in roles" :key="i">{{r.roleName}}</Option>
                    </Select>
                    </div>
                    <div slot="footer">
                    <Button type="default" @click="customFlags.AddModal=false">Close</Button>
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
                     <Select v-model="adminUserData.role_id" style="width:200px" placeholder="Select Role Type">
                    <Option  :value="r.id" v-for="(r,i) in roles" :key="i">{{r.roleName}}</Option>
                    </Select>
                    </div>

                    <div slot="footer">
                    <Button type="default" @click="customFlags.EditModal=false">Close</Button>
                    <Button type="primary" @click="saveData" :disabled="customFlags.isAdding" :loading='customFlags.isAdding'>{{customFlags.isAdding ? 'Updating...' : 'Update'}}</Button>
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
                role_id : null,
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
                   {name : 'Role'},
                   {name : 'Created At'},
                   {name : 'Actions'},
            ],
            roles : [],





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
             let obj = {
                id:data.id,
                fullName : data.fullName,
                pass: '',
                email : data.email,
                role_id : data.role_id
            }
            this.customFlags.isEdit = true;
            this.adminUserData = obj;
            this.customFlags.EditModal =true;
            this.customFlags.index = index;
        },
         async saveData(){
                 this.customFlags.isAdding = true;
                if (this.customFlags.isAdd) {
                     if (this.adminUserData.fullName.trim() == '') {
                        return this.error('Full Name is required');
                    }
                    if (this.adminUserData.email.trim() == '') {
                        return this.error('Email is required');
                    }
                     if (this.adminUserData.pass.trim() == '') {
                        return this.error('Password is required');
                    }
                    if (!this.adminUserData.role_id) {
                        return this.error('Role is required');
                    }

                    const res =  await this.callApi('post','/app/create_user',this.adminUserData);
                    if (res.status == 201) {
                        this.dataList.unshift(res.data);
                        this.success('User has been added successfully!');
                         this.adminUserData = {};
                    } else{
                    if (res.status == 422) {
                       errorMsg(res.data.errors);

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
                    if (this.adminUserData.fullName.trim() == '') {
                        return this.error('Name is required');
                    }
                    if (this.adminUserData.email.trim() == '') {
                        return this.error('Email is required');
                    }
                     if (this.adminUserData.pass.trim() == '') {
                        return this.error('Password is required');
                    }
                    if (!this.adminUserData.role_id) {
                        return this.error('Role is required');
                    }


                    const res =  await this.callApi('post','/app/edit_user',this.adminUserData);
                    if (res.status == 200) {
                        this.dataList[this.customFlags.index]=this.adminUserData;
                        this.success('User has been edited successfully!');

                    } else{
                        if (res.status == 422) {
                            errorMsg(res.data.errors);

                        } else {
                            this.error('Some error occured');
                        }

                    }
                    this.customFlags.isEdit = false;
                    this.customFlags.EditModal = false;
                    //this.editCategoryData= {};
                    this.adminUserData= {};
                }

        },
        errorMsg(error){
               for(let e in error) {
                this.error(error[e][0]);
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
                msg : 'Are you sure?',
                successMsg : 'Tag has been deleted successfully!',
                isDeleted : false
            }
               this.$store.commit('setDeletingModalObj', deleteModalObj);
        },
         async getdata() {

               // this.token = window.Laravel.csrfToken;
                //it will run both the API's at once instead of one by one
            const [res ,resRole] = await Promise.all([
                this.callApi('get','/app/get_user'),
                this.callApi('get','/app/get_role')
            ]);

                if (res.status == 200) {
                    this.dataList = res.data;
                } else {
                    this.error();
                }
                if (resRole.status ==200) {
                     this.roles = resRole.data;
                }else {
                    this.error();
                }

            }

    },
       created() {
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
