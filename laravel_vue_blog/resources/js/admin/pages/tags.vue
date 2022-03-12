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
                            <li class="breadcrumb-item"><a href="#!">Tags</a></li>
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
                          <Button @click="AddModal=true"><Icon type="md-add" /> Add tag</Button>
                       <!--  <h5>Basic Table</h5>
                        <span class="d-block m-t-5">use class <code>table</code> inside table element</span> -->
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tag Name</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tag,i) in tags" :key="i">
                                        <td>{{i+1}}</td>
                                        <td>{{tag.tagName}}</td>
                                        <td>{{tag.created_at}}</td>
                                        <td> <Button type="info" size="small" @click="showEditModal(tag,i)">Edit</Button>
                                            <Button type="error" size="small" @click="showDeletingModal(tag,i)" :loading="tag.isDeleteting">Delete</Button>
                                        </td>
                                    </tr>
                                    <tr v-if="tags.length <=0">
                                        <td>No Data</td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ADD Modal box-->

                    <Modal
                    v-model="AddModal"
                    title="Add Tag" :mask-closable="false" :closable='false'>
                       <Input v-model="tagData.tagName" placeholder="Add Tag Name" style="width: 300px" />

                    <div slot="footer">
                    <Button type="default" @click="AddModal=false">Close</Button>
                    <Button type="primary" @click="addTag" :disabled="isAdding" :loading='isAdding'>{{isAdding ? 'Adding...' : 'Add Tag'}}</Button>
                    </div>
                    </Modal>


                     <Modal
                    v-model="EditModal"
                    title="Edit Tag" :mask-closable="false" :closable='false'>
                       <Input v-model="editTagData.tagName" placeholder="Edit Tag Name" style="width: 300px" />

                    <div slot="footer">
                    <Button type="default" @click="EditModal=false">Close</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading='isAdding'>{{isAdding ? 'Editing...' : 'Edit Tag'}}</Button>
                    </div>
                    </Modal>

                    <deleteModal />

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
    name : "tag",
    data() {
        return {
            tagData : {
                tagName : '',

            }, tags : [],
            AddModal :false,
            EditModal :false,
            isAdding : false,
            editTagData : {
                tagName : '',
            },index: -1,
            showDeleteModal : false,
            deleteItem : {},
            deleteIndex:-1,
            modalLoading : false
        }
    },
     mounted() {

         this.getdata();
     },
    methods: {
       async addTag() {
            if (this.tagData.tagName.trim() == '') {
                return this.error('Tag Name is required');
            }
              const res =  await this.callApi('post','/app/create_tag',this.tagData);
              if (res.status == 201) {
                  this.tags.unshift(res.data);
                  this.success('Tag has been added successfully!');
                  this.AddModal = false;
              } else{
                  if (res.status == 422) {
                      if (res.data.errors.tagName) {
                          this.info(res.data.errors.tagName[0]);
                      }
                   } else {
                        this.error('Some error occured');
                   }

              }
              this.tagData = {};
        },
      async editTag() {
            if (this.editTagData.tagName.trim() == '') {
                return this.error('Tag Name is required');
            }
              const res =  await this.callApi('post','/app/edit_tag',this.editTagData);
              if (res.status == 200) {
                  this.tags[this.index].tagName=this.editTagData.tagName;
                  this.success('Tag has been edited successfully!');
                  this.EditModal = false;
              } else{
                  if (res.status == 422) {
                      if (res.data.errors.tagName) {
                          this.info(res.data.errors.tagName[0]);
                      }
                   } else {
                        this.error('Some error occured');
                   }

              }
        },
        showEditModal(tag,index) {
            let obj = {
                id:tag.id,
                tagName : tag.tagName
            }
            this.editTagData = obj;
            this.EditModal =true;
            this.index = index;
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
                const res = await this.callApi('get','/app/get_tag');
                if (res.status == 200) {
                    this.tags = res.data;
                }
            }

    },
       created() {
           console.log(this.isReadPermitted)
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

</style>
