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

            <MenuSelect  :data="dataList" :mydata="getData"></MenuSelect>
            <!-- v-on:menu-change="onChange" <i-select  @on-change = "onChange($event)"
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
import {bus} from '../../event-bus';
let dropHTML = {
   props: {
        data: Array,
        mydata:Array
    },
   /* `<Select style="width: 300px" placeholder="Menu" key="key1" @on-change="childMenuChange">
                <Option  value="-11" :key="-11">Select Menu</Option>
                <Option  value="-1" :key="-1">Enter Text</Option>
                <Option  :value="dt.id" :key="dt.id" v-for="dt in data">{{dt.name}}</Option>
                </Select>`*/
     template : `<Select style="width: 300px" placeholder="Menu" key="key1" @on-change="childMenuChange">
                <Option  value="-11" :key="-11">Select Menu</Option>
                <Option  value="-1" :key="-1">Enter Text</Option>
                <Option  value="0">{{mydata}}</Option>
                </Select>`,
    data() {
        return {
            innerHTML : ''
        }


    }, computed : {
        /*htmlContent() {
            return
        }*/
    },
     methods: {
         childMenuChange(e) {
             bus.$emit('menu-change',e);
                  console.log('child event');

         },
          /* renderHtml() {
               //@on-change="$emit('menu-change',$event)"
               this.innerHTML = `<Select style="width: 300px" placeholder="Menu" key="key1" @on-change="testchange">
           <Option  value="-11" :key="-11">Select Menu</Option>
            <Option  value="-1" :key="-1">Enter Text</Option>

            </Select>`
           }*/
     },
      created()  {
         // this.renderHtml();
          console.log('child name',this.data);
     /*  bus.$on('onChange', () => {
     console.log('onchange444');
    }) */
   // this.loadHtml();
 }
}
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
          dataList: [
             /* {id:1,name:'test1',parent : 0},
              {id:2,name:'test2',parent : 0},
              {id:3,name:'test3',parent : 0},
              {id:4,name:'test4',parent : 0},
              {id:5,name:'test5',parent : 0},*/

          ],
          active : [
            { value : '1', label : 'inactive'},
            { value : '0', label : 'active'},
          ],
    };
  },
  components : {
        'MenuSelect' : dropHTML
  },
 watch: {
        onChange1() {
            console.log('11111');
        }
 },
 computed : {
    /*htmlContent() {
         return this.innerHTML;
    }*/
     getData() {
         return this.dataList;
     }
 },
 created()  {
      bus.$on('menu-change', (data) => {
     console.log('onchange parent',data);
    })
    this.callApi('get','app/menu-master/getmenu/').then((response) => {
        this.filterData(response.data);
    }) ;
 },
  /*mounted() {},
  render() {
    return (<App>
      <span >
       hello
      </span></App>
    )
  },*/

  methods: {
   /* loadHtml() {
        this.renderHtml();
    },
    renderHtml() {
        this.innerHTML = `<Select style="width: 300px" placeholder="Menu" key="key1" @on-change="testchange">
           <Option  value="-11" :key="-11">Select Menu</Option>
            <Option  value="-1" :key="-1">Enter Text</Option>

            </Select>`;
    },*/
    addData() {
      this.customFlags.AddModalVisible = true;
    },
    testchange() {
        console.log('eemmit');
    },
    filterData(data) {
        let counter = 0;
        for(let d in data) {
            if(!this.dataList[data[d].pid]) {
                this.dataList[data[d].pid]= [];
                counter = 0;
            }
            this.dataList[data[d].pid][counter++]=data[d];
        }
        console.log(this.dataList,'filterData')
    },
    recursivefunction() {

    },
    onChange(e) {
      /* if(e==-1) {

      } */
        console.log('55555',e);
    // this.$emit('onChange');
    },
    save() {

    }
  },
};
</script>

<style lang="scss" scoped>
</style>
