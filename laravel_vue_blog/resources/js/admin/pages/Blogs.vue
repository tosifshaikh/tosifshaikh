<template>
<div>
    <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Blogs</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#!">Blogs</a></li>
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
                          <Button @click="$router.push('/create-blog')"><Icon type="md-add" /> Create Blog</Button>
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
                                    <tr v-for="(blog,i) in blogs" :key="i" >
                                        <td>{{i+1}}</td>
                                        <td>{{blog.title}}</td>
                                        <td ><span v-for="(c,j) in blog.cat" :key="j" > <Tag type="border">{{c.category_name}}</Tag></span></td>
                                        <td ><span v-for="(t,jj) in blog.tag" :key="jj" > <Tag type="border">{{t.tagName}}</Tag></span></td>
                                        <td>{{blog.views}}</td>

                                        <td>
                                            <Button type="info" size="small">Visit Blog</Button>
                                            <Button type="info" size="small" @click="$router.push(`/editblogs/${blog.id}`)" v-if="isUpdatePermitted">Edit</Button>
                                            <Button type="error" size="small" @click="showDeletingModal(blog,i)"  v-if="isDeletePermitted">Delete</Button>
                                        </td>
                                    </tr>
                                    <tr v-show="noData">
                                        <td>No Data</td>

                                    </tr>

                                </tbody>
                            </table>
                              <deleteModal />
                        </div>
                    </div>
                </div>
            </div>
     </div>
</div>

</template>

<script>
import deleteModal from '../components/DeleteModal.vue';
import { mapGetters } from 'vuex';

export default {
    name : "blogs",
    data() {
        return {
            data : {

            },
             headers : [
                   {name : '#'},
                   {name : 'Title'},
                   {name : 'Categories'},
                   {name : 'Tags'},
                   {name : 'Views'},
                   {name : 'Actions'},
            ],
blogs:[],
            AddModal :false,
            EditModal :false,
            isAdding : false,
            index: -1,
            showDeleteModal : false,
            deleteItem : {},
            deleteIndex:-1,
            modalLoading : false,
            noData : false,
        }
    },
     mounted() {

         //this.getdata();
     },
    methods: {

        showDeletingModal(data,index) {
          /*   this.deleteItem = data;
            this.deleteIndex = index;
            this.showDeleteModal = true; */
            const deleteModalObj = {
                showDeleteModal: true,
                deleteURL: 'app/delete_blog',
                data: {id:data.id},
                deleteIndex: index,
                msg : 'Are you sure you want to delete this blog?',
                successMsg : 'Blog has been deleted successfully!',
                isDeleted : false
            }
               this.$store.commit('setDeletingModalObj', deleteModalObj);
        },
         async getdata() {

                this.token = window.Laravel.csrfToken;
                const res = await this.callApi('get','/app/blog-data');
                if (res.status == 200) {
                    this.blogs = res.data;
                }else {
                  noData = true;
                }
            },
             dataLength(data){
            return data.length;
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
        ]),

    },watch : {
            getdeleteModalObj(value) {
                // console.log(value,'valuetagout');
                if (value.isDeleted) {
                      this.blogs.splice(value.deleteIndex,1);
                    //console.log(value,'valuetag',value.deleteIndex);
                }

            }
    }
}
</script>

<style>

</style>
