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
      <!-- [ basic-table ] start -->

        <div class="card">
          <div class="card-header">
            <!--   <Button @click="addData"><Icon type="md-add" />Add {{headerText}}</Button> -->
            <!--  <h5>Basic Table</h5>
                        <span class="d-block m-t-5">use class <code>table</code> inside table element</span> -->
          </div>
          <div class="card-body table-border-style">
               <div class="input_field">
                <input type="text" name="title" id="title" v-model="data.title" placeholder="Title">
              </div>
            <div class="table-responsive blog_editor">
              <editor
                ref="editor"
                :config="config"


                autofocus
              />

            </div>
             <div class="input_field">
                <Input type="textarea" name="title" id="title" :rows="4" v-model="data.post_excerpt" placeholder="post execrpt" />
              </div>
               <div class="input_field">
             <Select  filterable multiple placeholder = "Select Category" v-model="data.category_id" >
                <Option v-for="(c,i) in category" :value="c.id" :key="i">{{ c.category_name }}</Option>
            </Select>
            </div>
             <div class="input_field">
             <Select  filterable multiple placeholder = "Select Tag" v-model="data.tag_id" >
                <Option v-for="(t,i) in tags" :value="t.id" :key="i">{{ t.tagName }}</Option>
            </Select>
            </div>
              <div class="input_field">
                <Input type="textarea" name="meta_description" id="meta_description" :rows="4" placeholder="Meta Description" v-model="data.meta_description" />
              </div>
               <div class="button_field">
              <Button @click="save" :loding="isLoading" :disabled="isLoading">{{isLoading ? 'Please Wait....' :  'Create Blog'}}</Button>
               </div>
          </div>
          <!-- ADD Modal box-->

      </div>
  </div>
</template>

<script>
import ImageTool from '@editorjs/image';
 const Paragraph= require('@editorjs/paragraph');
 const Header = require('@editorjs/header');
 const Marker = require('@editorjs/marker');
export default {
  name: "createblog",
  data() {
    return {
      data: {
          title : '',
          post : '',
          post_excerpt : '',
          meta_description : '',
          category_id : [],
          jsondata : null,
          tag_id:null
      },
      articleHTML : '',
      category : [],
      tags : [],
      isLoading : false,
      config: {
          tools: {
              paragraph : {
                   class: Paragraph,
                    inlineToolbar: true,
              },

          header: {
            class: Header,
                placeholder: 'Enter a header',
                levels: [1,2, 3, 4,5,6],
                defaultLevel: 1
          },
          list: require('@editorjs/list'),

          InlineCode: require('@editorjs/inline-code'),
         CodeTool : require('@editorjs/code'),
         LinkTool: require('@editorjs/link'),
         Checklist : require('@editorjs/checklist'),
         RawTool : require('@editorjs/raw'),
         marker : {
             class : Marker
         },
        Warning : require('@editorjs/warning'),
        Personality : require('@editorjs/personality'),
        ImageTool : require('@editorjs/image'),
        Quote : require('@editorjs/quote')
        },
        image: {
          // Like in https://github.com/editor-js/image#config-params
          endpoints: {
                byFile: 'http://localhost:8008/uploadFile', // Your backend file uploader endpoint
          byUrl: 'http://localhost:8008/fetchUrl',
          },
          field: "image",
          types: "image/*",
        },
      },
    };
  },

  methods: {
    outputHTML(articleObj){

         articleObj.map(obj =>{
            switch(obj.type){
                case 'paragraph':
                    this.articleHTML +=this.makeParagraph(obj);
                    break;
                case 'ImageTool':
                     this.articleHTML +=this.makeImage(obj);
                    break;
                    case 'header':
                     this.articleHTML +=this.makeHeader(obj);
                    break;
                      case 'raw':
                     this.articleHTML +=`<div><code>${obj.data.html}</code></div>\n`;
                    break;
                     case 'code':
                     this.articleHTML +=this.makeCode(obj);
                    break;
                    case 'list':
                     this.articleHTML +=this.makeList(obj);
                    break;
                    case 'quote':
                     this.articleHTML +=this.makeQuote(obj);
                    break;
                    case 'warning':
                     this.articleHTML +=this.makeWarning(obj);
                    break;
                    case 'checklist':
                     this.articleHTML +=this.makeCheckList(obj);
                    break;
                    case 'embed':
                     this.articleHTML +=this.makeEmbed(obj);
                    break;
                    case 'delimeter':
                     this.articleHTML +=this.makeDelimeter(obj);
                    break;
                    default:
                        return '';
            }
        });
    },
    async saveData(data) {
         this.data.post = this.articleHTML; console.log( data.blocks,'after')
         this.data.jsondata = JSON.stringify( data);console.log( this.data.jsondata,'this.data.jsondata');
            const res = await this.callApi('post','app/create-blog',this.data);
            console.log( this.data,'this.data')
                      if(res.status ==200) {
                        this.success('Blog has been added successfully!');
                        this.$router.push('/blogs');
                      } else {
                           this.error();
                      }
                      this.isLoading = false;
                      this.data = {};
    },
    async save(){
        this.isLoading = true;
       this.$refs.editor._data.state.editor.save()
					.then((data) => {
						// Do what you want with the data here

                        this.outputHTML(data.blocks);
                         this.saveData(data);
                       /*  this.data.post = this.articleHTML; console.log( data.blocks,'after')
                        this.data.jsondata = JSON.stringify( data);console.log( this.data.jsondata,'this.data.jsondata') */
					})
					.catch(err => { console.log(err) })

    }
  },
  async created() {
     // const res = await this.callApi('get','app/get_category');
      const [cat, tag] = await Promise.all([
          this.callApi('get','app/get_category'),
          this.callApi('get','app/get_tag')
      ]);
      if(cat.status ==200) {
        this.category = cat.data;
        this.tags = tag.data;
      } else {
          this.error();
      }
  },
}
</script>

<style>
.space {
  margin-top: 10px;
  margin-bottom: 10px;
}
.blog_editor{
    width: 1000px;
    margin-left: 160px;
    padding: 4px 7px;
    font-size: 14px;
    border: 1px solid  #dcdee2;
    border-radius: 4px;
    color: #515a6e;
    background-color: #fff;
    background-image: none;
    z-index: -1;
}
.blog_editor:hover{
border: 1px solid #57a3f3;
}
.input_field{
margin: 20px 0 20px 160px;
width: 1000px;
display: grid;
border: 0px ;
}
.button_field{
margin: 20px 0 0 160px;
}
.input_field:hover{
border: 1px solid #57a3f3;
}
</style>
