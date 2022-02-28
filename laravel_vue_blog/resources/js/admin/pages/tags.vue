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
        showDeletingModal(tag,index) {
            this.deleteItem = tag;
            this.deleteIndex = index;
            this.showDeleteModal = true;
        },
        async deleteTag() {
             this.modalLoading = true;
                const res= await this.callApi('post','app/delete_tag', this.deleteItem);
                if (res.status == 200) {
                    this.tags.splice(this.deleteIndex,1);
                     this.success('Tag has been deleted successfully!');
                      
                    
                } else {
                     this.error('Some error occured');
                }
                 this.modalLoading = false;
                       this.showDeleteModal = false;
            
        }
    },
      async created() {
        const res = await this.callApi('get','/app/get_tag');
        if (res.status == 200) {
            this.tags = res.data;
        } 
    }, 
}
</script>

<style>

</style>