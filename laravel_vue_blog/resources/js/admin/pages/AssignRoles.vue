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
                            <li class="breadcrumb-item"><a href="#!">{{headerText}}</a></li>
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
                    <Select v-model="data.id" style="width:300px" placeholder="Select Role Type">
                    <Option  :value="r.id" v-for="(r,i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                    </Select>
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
                                     <tr v-for="(val,i) in resource" :key="i">
                                        <td>{{val.resourceName}}</td>
                                        <td> <Checkbox v-model="val.read"></Checkbox></td>

                                        <td> <Checkbox v-model="val.write"></Checkbox></td>
                                        <td> <Checkbox v-model="val.update"></Checkbox></td>
                                        <td> <Checkbox v-model="val.delete"></Checkbox></td>
                                    </tr>
                                    <tr v-if="roles.length <=0">
                                        <td>No Data</td>

                                    </tr>
                                     <tr>
                                        <td> <div class="center_class"><Button type="primary" :loading="isSending" :disabled="isSending" @click="save">Assign</Button></div></td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ADD Modal box-->


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

export default {
    name : "assignroles",
    data() {
        return {
            headerText : 'Assign Role',
            data : {
                id : null,
                roleName : '',
            },
            isSending : false,
            dataList : [],
             headers : [
                   {name : 'Resource Name'},
                   {name : 'Read'},
                   {name : 'Write'},
                   {name : 'Update'},
                   {name : 'Delete'},
            ],
            resource : [
                {resourceName : 'Tags',read:false,write:false,update:false,delete:false,name:'tags'},
                {resourceName : 'Category',read:false,write:false,update:false,delete:false,name:'category'},
                {resourceName : 'Admin Users',read:false,write:false,update:false,delete:false,name:'adminusers'},
                {resourceName : 'Role',read:false,write:false,update:false,delete:false,name:'role-management'},
                {resourceName : 'Assign Role',read:false,write:false,update:false,delete:false,name:'assign-roles'},
                {resourceName : 'Dashboard',read:false,write:false,update:false,delete:false,name:'dashboard'},
                ],
                roles : [],

        }
    },

    methods: {
         async getdata() {

                this.token = window.Laravel.csrfToken;
                const res = await this.callApi('get','/app/get_role');
                if (res.status == 200) {
                    this.roles = res.data;
                    if (res.data.length) {
                        this.data.id = res.data[0].id;
                        if (res.data[0].permission) {
                            this.resource = JSON.parse(res.data[0].permission);
                        }
                    }

                }
            },
            async save() {
                    let data = JSON.stringify(this.resource);
                    const res= await this.callApi('post','app/assign-roles',{permission : data,id: this.data.id});
                    if (res.status ==200) {
                         this.success('Role has been assigned successfully!');
                    } else{
                        this.error();
                    }
            },

    },
       created() {
            this.getdata();
    }
}
</script>

<style>
.center_class{
    margin-top: 10px;
}
</style>
