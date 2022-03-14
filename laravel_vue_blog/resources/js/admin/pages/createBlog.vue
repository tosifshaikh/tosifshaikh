<template>
  <div>
    <!-- [ breadcrumb ] start
    https://github.com/ChangJoo-Park/vue-editor-js
    -->

    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="m-b-10">Permissions</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html"><i class="feather icon-lock"></i></a>
              </li>
              <li class="breadcrumb-item"><a href="#!">Permissions</a></li>
              <li class="breadcrumb-item"><a href="#!">Create Blog</a></li>
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
            <!--   <Button @click="addData"><Icon type="md-add" />Add {{headerText}}</Button> -->
            <!--  <h5>Basic Table</h5>
                        <span class="d-block m-t-5">use class <code>table</code> inside table element</span> -->
          </div>
          <div class="card-body table-border-style">
            <div class="table-responsive">
              <editor
                ref="editor"
                :config="config"
                :initialized="onInitialized"
                @save="OnSave"
                autofocus
              />
              <Button @click="save">Save</Button>
            </div>
          </div>
          <!-- ADD Modal box-->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "createblog",
  data() {
    return {
      data: {},
      config: {
          tools: {
          header: require('@editorjs/header'),
          list: require('@editorjs/list'),
          paragraph: require('@editorjs/paragraph'),
          InlineCode: require('@editorjs/inline-code'),
         CodeTool : require('@editorjs/code'),
         LinkTool: require('@editorjs/link'),
         Checklist : require('@editorjs/checklist'),
        },
        image: {
          // Like in https://github.com/editor-js/image#config-params
          endpoints: {},
          field: "image",
          types: "image/*",
        },
      },
    };
  },

  methods: {
    OnSave(response) {
        console.log(response,'response');
    },onInitialized(editor){
        console.log(editor)
    },
    async save(){
        const res =  await this.$refs.editor._data.state.editor.save();
            /* this.$refs.editor._data.state.editor.save()
					.then((data) => {
						// Do what you want with the data here
						console.log(data)
					})
					.catch(err => { console.log(err) })*/
    }
  },
  created() {},
}
</script>

<style>
.space {
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>
