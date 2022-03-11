<template>
<div>
    <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Permissions</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-lock"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Permissions</a></li>
                            <li class="breadcrumb-item"><a href="#!">{{headerText}} Management</a></li>
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
                          <Button @click="addData"><Icon type="md-add" />Add {{headerText}}</Button>
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
                                        <td>{{val.roleName}}</td>

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
                    :title="`Add ${headerText}`" :mask-closable="false" :closable='false'>
                      <div class="space">
                        <Input type="text" v-model="data.roleName" placeholder="Role name" style="width: 300px" />
                      </div>

                    <div slot="footer">
                    <Button type="default" @click="customFlags.AddModal=false">Close</Button>
                    <Button type="primary" @click="saveData" :disabled="customFlags.isLoading" :loading=' customFlags.isLoading'>{{customFlags.isLoading ? 'Saving...' : 'Save'}}</Button>
                    </div>
                    </Modal>


                 <Modal
                    v-model="customFlags.EditModal"
                    :title="`Edit ${headerText}`" :mask-closable="false" :closable='false'>
                       <div class="space">
                        <Input type="text" v-model="data.roleName" placeholder="Role name" style="width: 300px" />
                      </div>

                    <div slot="footer">
                    <Button type="default" @click="customFlags.EditModal=false">Close</Button>
                    <Button type="primary" @click="saveData" :disabled="customFlags.isLoading" :loading='customFlags.isLoading'>{{customFlags.isLoading ? 'Updating...' : 'Update'}}</Button>
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
    name : "role",
    data() {
        return {
            headerText : 'Role',
            data : {
                roleName : '',
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
                   {name : 'Role Name'},
                   {name : 'Created At'},
                   {name : 'Actions'},
            ],
            userTypes : {
                0 : 'Admin',
                1 : 'Editor'
            },





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
                roleName : data.roleName,
            }
            this.customFlags.isEdit = true;
            this.data = obj;
            this.customFlags.EditModal =true;
            this.customFlags.index = index;
        },
         async saveData(){
                 this.customFlags.isLoading = true;
                if (this.customFlags.isAdd) {
                   if (this.data.roleName.trim() == '') {
                        return this.error('Role Name is required');
                    }
                    const res =  await this.callApi('post','/app/create_role',this.data);
                    if (res.status == 201) {
                        this.dataList.unshift(res.data);
                        this.success('Role has been added successfully!');

                    } else{
                    if (res.status == 422) {
                       errorMsg(res.data.errors);

                    } else {
                            this.error('Some error occured');
                        }

                    }

                    this.customFlags.isAdd = false;
                    this.customFlags.AddModal = false;

                }
                if (this.customFlags.isEdit) {
                    if (this.data.roleName.trim() == '') {
                        return this.error('Role Name is required');
                    }

                    const res =  await this.callApi('post','/app/edit_role',this.data);
                    if (res.status == 200) {
                        this.dataList[this.customFlags.index]=this.data;
                        this.success('Role has been edited successfully!');

                    } else{
                        if (res.status == 422) {
                            errorMsg(res.data.errors);

                        } else {
                            this.error('Some error occured');
                        }

                    }
                    this.customFlags.isEdit = false;
                    this.customFlags.EditModal = false;

                }
                 this.customFlags.isLoading = false;
                   this.data= {};

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
                msg : 'Tag has been deleted successfully!',
                isDeleted : false
            }
               this.$store.commit('setDeletingModalObj', deleteModalObj);
        },
         async getdata() {

                this.token = window.Laravel.csrfToken;
                const res = await this.callApi('get','/app/get_role');
                if (res.status == 200) {
                    this.dataList = res.data;
                }
            }

    },
       created() {
            this.getdata();
    },
     components: {
        deleteModal,
    },computed: {
  ...mapGetters([
            'getdeleteModalObj',
        ])
    },watch : {
            getdeleteModalObj(value) {
                if (value.isDeleted) {
                      this.tags.splice(value.deleteIndex,1);
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
