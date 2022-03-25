<template>
  <div>
    <!-- [ breadcrumb ] start

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
              <li class="breadcrumb-item"><a href="#!">Edit Blog</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ basic-table ] start -->

    <div class="card">
      <div class="card-header"></div>
      <div class="card-body table-border-style">
        <div class="input_field">
          <input
            type="text"
            name="title"
            id="title"
            v-model="data.title"
            placeholder="Title"
          />
        </div>
        <div class="table-responsive blog_editor" id="editorjs">
          <!--   <editor v-if="initData" ref="editor" :config="config" autofocus   :initialized="onInitialized"/> -->
        </div>
        <div class="input_field">
          <Input
            type="textarea"
            name="title"
            id="title"
            :rows="4"
            v-model="data.post_excerpt"
            placeholder="post execrpt"
          />
        </div>
        <div class="input_field">
          <Select
            filterable
            multiple
            placeholder="Select Category"
            v-model="data.category_id"
          >
            <Option v-for="(c, i) in category" :value="c.id" :key="i">{{
              c.category_name
            }}</Option>
          </Select>
        </div>
        <div class="input_field">
          <Select
            filterable
            multiple
            placeholder="Select Tag"
            v-model="data.tag_id"
          >
            <Option v-for="(t, i) in tags" :value="t.id" :key="i">{{
              t.tagName
            }}</Option>
          </Select>
        </div>
        <div class="input_field">
          <Input
            type="textarea"
            name="meta_description"
            id="meta_description"
            :rows="4"
            placeholder="Meta Description"
            v-model="data.meta_description"
          />
        </div>
        <div class="button_field">
          <Button @click="save" :loding="isLoading" :disabled="isLoading">{{
            isLoading ? "Please Wait...." : "Edit Blog"
          }}</Button>
        </div>
      </div>
      <!-- ADD Modal box-->
    </div>
  </div>
</template>

<script>
//https://github.com/ChangJoo-Park/vue-editor-js
//https://codesandbox.io/s/eosbi?file=/src/components/HelloWorld.vue:1360-1370
import EditorJS from "@editorjs/editorjs";
import ImageTool from "@editorjs/image";
import Header from "@editorjs/header";
import Paragraph from "@editorjs/paragraph";
import InlineCode from "@editorjs/inline-code";
import Code from "@editorjs/code";
import Linktool from "@editorjs/link";
import Checklist from "@editorjs/checklist";
import Raw from "@editorjs/raw";
import Warning from "@editorjs/warning";
import Personality from "@editorjs/personality";
import Quote from "@editorjs/quote";
import Marker from "@editorjs/marker";
import List from "@editorjs/list";
export default {
  name: "editblog",
  data() {
    return {
      data: {
        title: "",
        post: "",
        post_excerpt: "",
        meta_description: "",
        category_id: [],
        jsondata: null,
        tag_id: [],
      },
      editor: null,
      articleHTML: "",
      category: [],
      tags: [],
      isLoading: false,
    };
  },
  mounted() {
    //this.myEditor();
  },
  methods: {
    myEditor() {
      this.editor = new EditorJS({
        holder: "editorjs",
        initialBlock: "paragraph",

        tools: {
          paragraph: {
            class: Paragraph,
            inlineToolbar: true,
          },

          header: {
            class: Header,
            config: {
              placeholder: "Enter a header",
              levels: [1, 2, 3, 4, 5, 6],
              defaultLevel: 6,
            },
          },
          checkList: {
            class: Checklist,
          },
          list: {
            class: List,
          },

          InlineCode: {
            class: InlineCode,
          },
          CodeTool: {
            class: Code,
          },
          LinkTool: {
            class: Linktool,
          },

          Marker: {
            class: Marker,
          },
          image: {
            class: ImageTool,
            // Like in https://github.com/editor-js/image#config-params
            endpoints: {
              byFile: "http://localhost:8008/uploadFile", // Your backend file uploader endpoint
              byUrl: "http://localhost:8008/fetchUrl",
            },
            field: "image",
            types: "image/*",
          },
          RawTool: {
            class: Raw,
          },
          Warning: {
            class: Warning,
          },
          Personality: {
            class: Personality,
          },
          Quote: {
            class: Quote,
          },
        },
      });
      this.editor.isReady
        .then(() => {
          /** Do anything you need after editor initialization */
          this.renderData();
        })
        .catch((reason) => {
          console.log(`Editor.js initialization failed because of ${reason}`);
        });
    },

    outputHTML(articleObj) {
      articleObj.map((obj) => {
        switch (obj.type) {
          case "paragraph":
            this.articleHTML += this.makeParagraph(obj);
            break;
          case "ImageTool":
            this.articleHTML += this.makeImage(obj);
            break;
          case "header":
            this.articleHTML += this.makeHeader(obj);
            break;
          case "raw":
            this.articleHTML += `<div><code>${obj.data.html}</code></div>\n`;
            break;
          case "code":
            this.articleHTML += this.makeCode(obj);
            break;
          case "list":
            this.articleHTML += this.makeList(obj);
            break;
          case "quote":
            this.articleHTML += this.makeQuote(obj);
            break;
          case "warning":
            this.articleHTML += this.makeWarning(obj);
            break;
          case "checklist":
            this.articleHTML += this.makeCheckList(obj);
            break;
          case "embed":
            this.articleHTML += this.makeEmbed(obj);
            break;
          case "delimeter":
            this.articleHTML += this.makeDelimeter(obj);
            break;
          default:
            return "";
        }
      });
    },
    async saveData(data) {
      this.data.post = this.articleHTML;
      this.data.jsondata = JSON.stringify(data);
      if (this.data.post.trim() == "") {
        return this.error("Post is required");
      }
      if (this.data.title.trim() == "") {
        return this.error("Title is required");
      }
      if (this.data.post_excerpt.trim() == "") {
        return this.error("Post Excerpt is required");
      }
      if (this.data.meta_description.trim() == "") {
        return this.error("Meta Description is required");
      }
      if (this.data.category_id.length <= 0) {
        return this.error("Category is required");
      }
      if (this.data.tag_id.length <= 0) {
        return this.error("Tag is required");
      }
      const res = await this.callApi(
        "post",
        `/app/edit_blog/${this.$route.params.id}`,
        this.data
      );
      if (res.status == 200) {
        this.success("Blog has been edited successfully!");
        this.$router.push("/blogs");
      } else {
        this.error();
      }
      this.isLoading = false;
      this.data = {};
    },
    save() {
      this.isLoading = true;
      this.editor
        .save()
        .then((data) => {
          // Do what you want with the data here
          this.outputHTML(data.blocks);
          this.saveData(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    async renderData() {
      const id = parseInt(this.$route.params.id);
      if (!id) {
        return this.$router.push("/notfound");
      }

      const [blog, cat, tag] = await Promise.all([
        this.callApi("get", `/app/blog-data/${id}`),
        this.callApi("get", "/app/get_category"),
        this.callApi("get", "/app/get_tag"),
      ]);
      if (blog.status == 200) {
        this.category = cat.data;
        this.tags = tag.data;
        let blogData = blog.data;
        this.data = blog.data;
        let tempCategory = [],
          tempTags = [];
        for (let c in this.data.cat) {
          if (this.data.cat[c].id) {
            tempCategory.push(this.data.cat[c].id);
          }
        }
        this.data.category_id = tempCategory;
        for (let t in this.data.tag) {
          if (this.data.tag[t].id) {
            tempTags.push(this.data.tag[t].id);
          }
        }
        this.data.tag_id = tempTags;
        this.editor.render(JSON.parse(this.data.jsonData));
      } else {
        this.error();
      }
    },
  },
  async created() {
    this.myEditor();
  },
};
</script>

<style>
.space {
  margin-top: 10px;
  margin-bottom: 10px;
}
.blog_editor {
  width: 1000px;
  margin-left: 160px;
  padding: 4px 7px;
  font-size: 14px;
  border: 1px solid #dcdee2;
  border-radius: 4px;
  color: #515a6e;
  background-color: #fff;
  background-image: none;
  z-index: -1;
}
.blog_editor:hover {
  border: 1px solid #57a3f3;
}
.input_field {
  margin: 20px 0 20px 160px;
  width: 1000px;
  display: grid;
  border: 0px;
}
.button_field {
  margin: 20px 0 0 160px;
}
.input_field:hover {
  border: 1px solid #57a3f3;
}
</style>
