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
                    <td>{{ i + 1 }}</td>
                    <td>{{ category.category_name }}</td>
                    <td>{{ category.created_at }}</td>
                    <td>
                      <Button
                        type="info"
                        size="small"
                        @click="showEditModal(category, i)"
                        >Edit</Button
                      >
                      <Button
                        type="error"
                        size="small"
                        @click="showDeletingModal(category, i)"
                        :loading="customFlags.isDeleteting"
                        >Delete</Button
                      >
                    </td>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <Modal
            v-model="customFlags.AddModalVisible"
            title="Add Menu Category"
            :mask-closable="false"
            :closable="false"
          >
            <Input
              placeholder="Add Menu category Name"
              v-model="data.category_name"
            />
            <div class="space"></div>
            <Select
              v-model="data.is_active"
              style="width: 300px"
              placeholder="Status"
            >
             <Option value="" :key="-1" >Select Status</Option>
              <Option :value=i v-for="(r, i) in Active" :key="i">{{
                r
              }}</Option>
            </Select>

            <div slot="footer">
              <Button type="default" @click="showHideToggle(1, false)"
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

          <Modal
            v-model="customFlags.EditModalVisible"
            title="Edit Menu Category"
            :mask-closable="false"
            :closable="false"
          >
            <Input
              v-model="data.category_name"
              placeholder="Edit Menu category Name"
            />
            <div class="space"></div>
             <Select
             v-model="data.is_active"
              style="width: 300px"
              placeholder="Status"
            >
              <Option value="1" key="1">Active</Option>
              <Option value="0" key="0">Inactive</Option>
            </Select>

            <div slot="footer">
              <Button type="default" @click="showHideToggle(2, false)"
                >Close</Button
              >
              <Button
                type="primary"
                @click="save"
                :disabled="customFlags.isAdding"
                :loading="customFlags.isAdding"
                >{{ customFlags.isAdding ? "Updating..." : "Update" }}</Button
              >
            </div>
          </Modal>
          <deleteModal />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import deleteModal from "../components/DeleteModal.vue";
import { mapGetters } from "vuex";
/* import { mapState, mapActions } from "vuex";
import { SAVE_DATA } from "../../store/modules/menucategory/ActionConstants"; */
export default {
  name: "menucategory",

  data() {
    return {
      data: {
        category_name: null,
        is_active : -1
      },
      customFlags: {
        isAdding: false,
        AddModalVisible: false,
        isEditing: false,
        EditModalVisible: false,
        index: -1,
        isDeleteting: false,
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
  components: {
    deleteModal,
  },
  computed: {
    ...mapGetters(["getdeleteModalObj"]),
  },
  watch: {
    getdeleteModalObj(value) {
      if (value.isDeleted) {
        this.dataList.splice(value.deleteIndex, 1);
      }
    },
  },
  created() {
    this.getData();
  },
  methods: {
    showEditModal(data, index) {

      let obj = Object.assign({}, data);
      this.customFlags.isEditing = true;
      this.data = obj;
      this.customFlags.EditModalVisible = true;
      this.customFlags.index = index;
    },

    showDeletingModal(data, index) {
      const deleteModalObj = {
        showDeleteModal: true,
        deleteURL: "app/menu-category/delete",
        data: { id: data.id },
        deleteIndex: index,
        isDeleted: false,
        confirmMsg:"Are you sure you want to delete " + data.category_name + "?",
        msg: "Menu Category has been deleted successfully!",
      };
      this.$store.commit("setDeletingModalObj", deleteModalObj);
      /* this.deleteItem = tag;
            this.deleteIndex = index;
            this.showDeleteModal = true; */
    },
    /*  ...mapActions("menucategorymodule", {
      saveAction: SAVE_DATA,
    }), */
    addData() {
      this.customFlags.AddModalVisible = true;
      // this.customFlags.isAdding = true;
    },
    showHideToggle(flag, value) {
      if (flag == 1) {
        this.customFlags.AddModalVisible = value;
        this.customFlags.isAdding = value;
      }
      if (flag == 2) {
        this.customFlags.EditModalVisible = value;
        this.customFlags.isEditing = value;
        this.showDeleteModal = value;
      }
       this.data.category_name = "";
       this.data.is_active = 0;
    },
    save() {
      if (this.customFlags.AddModalVisible) {
        /*  this.saveAction({
            method: "post",
            URL: "app/add-menu-category",
            data: this.data,
            }).then(response => {
                this.dataList = response.data;
            }); */
        this.callApi("post", "app/menu-category/add", this.data).then(
          (response) => {
            this.customFlags.AddModalVisible = false;
            this.customFlags.isAdding = false;
            this.data.category_name = "";
            this.dataList.unshift(response.data);
          }
        );
      }
      if (this.customFlags.isEditing) {
        this.callApi("post", "app/menu-category/edit", this.data).then(
          (response) => {
            this.customFlags.EditModalVisible = false;
            this.customFlags.isEditing = false;
            this.data.categoryName = "";
            this.dataList[this.customFlags.index] = response.data;
            this.customFlags.index = -1;
          }
        );
      }
    },
    getData() {
      this.callApi("GET", "app/menu-category/show").then((response) => {
        this.dataList = response.data;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.space {
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>
