<template>
  <div>
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="m-b-10">Menu Category</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html"><i class="feather icon-box"></i></a>
              </li>
              <li class="breadcrumb-item"><a href="#!">Master</a></li>
              <li class="breadcrumb-item"><a href="#!">Menu Category</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <Button @click="addData"><Icon type="md-add" />Add</Button>
          </div>
          <div class="card-body table-border-style">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th v-for="(header, indx) in headers" :key="indx">
                      {{ header.name }}
                    </th>
                  </tr>
                  <tr v-for="(category, i) in dataList" :key="i">
                    <td>{{ category.category_name }}</td>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <Modal
            v-model="customFlags.AddModalVisible"
            title="Add Menu Category"
          >
            <Input
              placeholder="Add Menu category Name"
              v-model="data.categoryName"
            />
            <div class="space"></div>

            <!--  <div class="image_thumb" v-if="categoryData.iconImage">
                        <img :src="`/uploads/${categoryData.iconImage}`" alt="">
                    </div> -->

            <div slot="footer">
              <Button type="default" @click="showHideToggle(1, true)"
                >Close</Button
              >
              <Button
                type="primary"
                @click="save"
                :disabled="customFlags.isAdding"
                :loading="customFlags.isAdding"
                >{{ customFlags.isAdding ? "Saving..." : "Save" }}</Button
              >
            </div>
          </Modal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { SAVE_DATA } from "../../store/modules/menucategory/ActionConstants";
export default {
  name: "menucategory",

  data() {
    return {
      data: {
        categoryName: null,
      },
      customFlags: {
        isAdding: false,
        AddModalVisible: false,
        isEditing: false,
        EditModalVisible: false,
        index: -1,
      },

      headers: [
        { name: "#" },
        { name: "Menu Category Name" },
        { name: "Created At" },
        { name: "Actions" },
      ],
      dataList: [],
    };
  },
  created() {
    this.getData();
  },
  mounted() {
    console.log("mounted");
  },

  methods: {
    ...mapActions("menucategorymodule", {
      saveAction: SAVE_DATA,
    }),
    addData() {
      this.customFlags.AddModalVisible = true;
      this.customFlags.isAdd = true;
    },
    showHideToggle: (flag, value) => {
      if (flag == 1) {
        this.customFlags.AddModalVisible = value;
      }
    },
    save() {
      if (this.customFlags.isAdd) {
        /*  this.saveAction({
            method: "post",
            URL: "app/add-menu-category",
            data: this.data,
            }).then(response => {
                this.dataList = response.data;
            }); */
        this.callApi({
          method: "post",
          URL: "app/add-menu-category",
          data: this.data,
        }).then((response) => {
          this.dataList = response.data;
        });
      }
    },
    getData() {
      this.callApi('post','app/menu-category/show/1',{'acb': 1});
    },
  },
};
</script>

<style lang="scss" scoped>
</style>