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
                 <MenuSelect  :data="getData" ></MenuSelect>
          </div>
          <Modal
            v-model="customFlags.AddModalVisible"
            title="Add Menu"

            :mask-closable="false"
            :closable="false"
          >

<!--            <MenuSelect  :data="getData"></MenuSelect>-->
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
        data: Array


    },
   /* `<Select style="width: 300px" placeholder="Menu" key="key1" @on-change="childMenuChange">
                <Option  value="-11" :key="-11">Select Menu</Option>
                <Option  value="-1" :key="-1">Enter Text</Option>
                <Option  :value="dt.id" :key="dt.id" v-for="dt in data">{{dt.name}}</Option>
                </Select>`*/
     template :`<div>
    <Select   v-for="(row, level) in data" style="width: 300px" placeholder="Menu" :key="level" ref="select_level" @on-change="childMenuChange($event,level)">
    <Option  :value="-11+'|'+level" :key="-11">Select Menu</Option>
    <Option  :value="-1+'|'+level" :key="-1">Enter Text</Option>
    <Option  :value="column.id+'|'+column.pid"  :key="idx2"  v-for="(column, idx2) in row">{{column.menu_name}}</Option>
    </Select>

    </div>`  ,
    data() {
        return {
            innerHTML : '',
        }


    }, computed : {
         recurObj() {

            //console.log(this.localData,'this.localData')
             //return this.localData;
         }
        /*htmlContent() {
            return
        }*/
    },

    watch: {
        data: function (oldValue, newValue) {
            //console.log(newValue,oldValue, 'watch');
        },
    },
     methods: {
         childMenuChange(event,idx) {
             this.$nextTick(()=>{
                   console.log('child event',event,idx,this.$refs,this.$refs.select_level.length,this.data);

                    bus.$emit('menu-change',{selectedVal : event,level :idx, refs : this.$refs,});

             });



         },
        loadObj(parent) {
           // this.localData = this.data
        }
          /* renderHtml() {
               //@on-change="$emit('menu-change',$event)"
               this.innerHTML = `<Select style="width: 300px" placeholder="Menu" key="key1" @on-change="testchange">
           <Option  value="-11" :key="-11">Select Menu</Option>
            <Option  value="-1" :key="-1">Enter Text</Option>

            </Select>`
           }*/
     },
    mounted() {
       // this.localData = JSON.parse(JSON.stringify(this.data))
       // console.log('mounted child',this.localData,this.data);
    },
    created()  {
       // this.loadObj(0);
        // this.renderHtml();
         // console.log('child name',this.localData,this.data);
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
        levelCounter : 0,
        num : 0,
        childIndex : 0,
        localDataList : [],
        responseData : [],
          dataList: [
           /*  [{id:1,name:'test1',parent : 0},{id:11,name:'test21',parent : 0}],
              [{id:2,name:'test2',parent : 0},{id:22,name:'test22',parent : 0}],
              {id:3,name:'test3',parent : 0},
              {id:4,name:'test4',parent : 0},
              {id:5,name:'test5',parent : 0},
*/
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
         console.log(this.localDataList,'getData');
         return this.localDataList;
     }
 },
 created()  {
      bus.$on('menu-change', (event) => {
         this.onChange(event);
     console.log('onchange parent',event);
    })

    this.callApi('get','app/menu-master/getmenu/').then((response) => {
        this.dataList =[];
        this.responseData = response.data;
         this.recursivefunction(0, this.responseData);
          this.filterData(0,0);
        // this.localDataList = [...this.localDataList];
        //this.filterData(response.data);
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
    filterData(pid,level) {
        for(const index in this.dataList) {
            if(index <= level) {
                for (const dd in this.dataList[index]) {
                      if(pid ==this.dataList[index][dd].pid) {
                          if(! this.localDataList[index]) {
                               this.localDataList[index] = [];
                          }
                          this.localDataList[index].push(this.dataList[index][dd]);
                      }
                }
               /*  console.log(index,'index',this.dataList); */
            }
        }
        this.localDataList = [...this.localDataList];
          /* this.localDataList = this.dataList.filter((data,index)=>{
            if(index <= level  ) {
                data.filter((data2,idx)=>{
                    console.log(pid,data2.pid,'hhh');
                        if(pid == data2.id) {
                           return data2[idx];
                        }
                });
                 return this.dataList[index];



            }
        }); */
          if(!this.dataList[parseInt(level)+1] &&  this.localDataList.length == 0) {
               // this.localDataList[parseInt(level)+1]= [];
                //this.localDataList.push([]);
                 // this.localDataList=[...this.localDataList];
                //return false;
               // this.recursivefunction(parentID, this.responseData);
            }
         console.log(this.localDataList,'this.localDataList', this.dataList,pid,level,this.localDataList.length);
    },
    recursivefunction(parent ,arr, levelnum) {
        for(let d in arr) {
            if(arr[d].pid == parent) {
                if(parent == 0) {
                levelnum = 0;
                 this.levelCounter =0;
                } else {
                    levelnum = this.levelCounter;
                }
                 this.levelCounter++;
               this.recursivefunction(arr[d].id,arr,levelnum);
              // console.log(this.dataList[levelnum],'first')
                if(!this.dataList[levelnum]) {
                    this.dataList[levelnum]=[];
                    //console.log('in');

                }
                  this.dataList[levelnum].push(arr[d]);

            }
        }

        /*this.localDataList = this.dataList.filter((data,index,array)=>{
            if(index <= parent) {
                console.log(index,parent,'in');
               return this.dataList[index];

            }
        });
        console.log(  this.localDataList,this.dataList,'  this.localDataList');*/
    },
    onChange(event) {
        if(event) {
            let dataSplit = event.selectedVal.split('|');
            let parentID =  dataSplit[0];


            console.log(parentID,'parentID',Object.keys(event.refs),event.refs, this.dataList,dataSplit,this.dataList[dataSplit[1]+1]);
             if(!this.dataList[parseInt(dataSplit[1])+1]) {
                 this.localDataList.push([]);
                return false;
               // this.recursivefunction(parentID, this.responseData);
            }
            this.localDataList =[];
            this.filterData(parentID,(event.level+1));

        }
    },
    save() {

    }
  },
};
</script>

<style lang="scss" scoped>
</style>
