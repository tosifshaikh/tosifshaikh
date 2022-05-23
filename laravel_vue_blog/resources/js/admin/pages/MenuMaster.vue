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
            <Button @click="addData"><Icon type="md-add" />Add</Button>
          </div>
          <Modal
            v-model="customFlags.AddModalVisible"
            title="Add Menu"

            :mask-closable="false"
            :closable="false"
          >
            <div v-html="htmlContent"></div>
            <!-- <i-select  @on-change = "onChange($event)"
            style="width: 300px"
            placeholder="Menu" key="key1">
            v-model = "optionSelected">
            <span id="menu1"><Input
              v-model="data.menu1"
              :v-show="show1"
              placeholder="Add Menu name"
            /></span>
            <i-option  value="-1" key="-1">Enter Text</i-option>
            <i-option  :value="r.value" v-for="(r, i) in active" :key="i">{{r.label}}</i-option>
            </i-select> -->
            <div class="space"></div>

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
        innerHTML : '',
        customFlags: {
        isAdding: false,
        AddModalVisible: false,
        isEditing: false,
        EditModalVisible: false,
        index: -1,
        isDeleteting: false,
        },
          dataList: [],
          active : [
            { value : '1', label : 'inactive'},
            { value : '0', label : 'active'},
          ],
    };
  },
 watch: {
        onChange1() {
            console.log('11111');
        }
 },
 computed : {
    htmlContent() {
         return `${this.innerHTML}`;
    }
 },
 created()  {
    this.loadHtml();
 },
  mounted() {},

  methods: {
    loadHtml() {
        this.renderHtml();
    },
    renderHtml() {
  this.innerHTML = `<Select  @on-change = "onChange($event)"
            style="width: 300px"
            placeholder="Menu" key="key1"
            v-model = ${this.optionSelected}>
            <Option  :value="" :key="-11">Select Menu</Option>
            <Option  :value="-1" :key="-1">Enter Text</Option>

            </Select>`;
    },
    addData() {
      this.customFlags.AddModalVisible = true;
    },
    onChange(e) {
      if(e==-1) {

      }
      console.log('sacascasc',e);
    },
    save() {

    }
  },
};
</script>

<style lang="scss" scoped>
</style>
