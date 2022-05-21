<template>
  <div>
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="m-b-10">Menu</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.html"><i class="feather icon-box"></i></a>
              </li>
              <li class="breadcrumb-item"><a href="#!">Master</a></li>
              <li class="breadcrumb-item"><a href="#!">Menu</a></li>
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
                  <Select
             v-on:change="onChange1"
              style="width: 300px"
              placeholder="Menu"
              v-model="optionSelected"
            >
              <Option :value="1" :key="1">Menu 1</Option>
              <Option :value="0" :key="0">Menu 2</Option>
            </Select>
            <Button @click="addData"><Icon type="md-add" />Add</Button>
          </div>
          <Modal
            v-model="customFlags.AddModalVisible"
            title="Add Menu"
           
            :mask-closable="false"
            :closable="false"
          >
            <!-- <Input
              placeholder="Add Menu Name"
              v-model="test"
            /> -->
            <div class="space"></div>
            <Select
             on-change="onChange1"
              style="width: 300px"
              placeholder="Menu"
              v-model="optionSelected"
            >
              <Option :value="1" :key="1">Menu 1</Option>
              <Option :value="0" :key="0">Menu 2</Option>
            </Select>

            <div slot="footer">
              
            </div>
          </Modal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "menuComponent",

  data() {
    return {
        optionSelected : '',
        customFlags: {
        isAdding: false,
        AddModalVisible: false,
        isEditing: false,
        EditModalVisible: false,
        index: -1,
        isDeleteting: false,
        },
          dataList: [],
    };
  },
 watch: {
        onChange1() {
            console.log('sacascascacs');
        }
 },
  mounted() {},

  methods: {
    addData() {
      this.customFlags.AddModalVisible = true;
    },
    onChange1() {
      console.log('sacascasc');
    },
    save() {

    }
  },
};
</script>

<style lang="scss" scoped>
</style>