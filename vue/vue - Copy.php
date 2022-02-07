<html>
<script src="vue.js"></script>
<script>
new Vue({
  el: '#app',
  data: {
    currentFilterProperty: 'name',
    currentFilterValue: '',
    filteredData:[],
    cols: [
      { title: 'Name', prop:'name' },
      { title: 'Age', prop:'age' },
      { title: 'Birthday', prop:'birthday' },      
    ],

    dataFilters: [],
    addFilters:[],
    data: [
      { name:'Patricia Miller', age:69, birthday:'04-15-1948' },
      { name:'Bill Baggett', age:62, birthday:'05-07-1955' },      
      { name:'Maxine Thies', age:21, birthday:'11-28-1995' },      
      { name:'Alison Battle', age:65, birthday:'08-07-1952' },      
      { name:'Dick Triplett', age:25, birthday:'08-27-1982' } 
    ]
  },
  
  methods: {
    addFilter: function() {
     if(!this.currentFilterValue){
      return false;
     }
      var obj = {};
      this.addFilters.push({name:this.currentFilterProperty,value:this.currentFilterValue});
      this.currentFilterValue = "";
    	var vm = this;
      this.dataFilters = this.data
      //var temp = [];
      for(var i in vm.addFilters){
        this.dataFilters = this.dataFilters.filter(function(a,b){
        return ((a[vm.addFilters[i].name]).toString().toLowerCase()).indexOf((vm.addFilters[i].value).toString().toLowerCase()) !== -1;
        });
      }
      // How to apply filter?
    }
  },
  mounted(){
    this.dataFilters = this.data;
  }
})
</script>
<body>
<script src="https://unpkg.com/vue"></script>

<div id="app">
  <div>
    <select v-model="currentFilterProperty">
      <option value="name">Name</option>
      <option value="age">Age</option>
      <option value="birthday">Birthdate</option>
    </select>
    <input placeholder="filter value" v-model="currentFilterValue" />
    
    <button v-on:click="addFilter">
    add filter
    </button>
  </div>
  <div v-for="(filter,index) in addFilters">{{filter.name}} : {{filter.value}}</div>
  <hr />

  <table>
    <tbody>
      <tr v-for="(record, index) in dataFilters">
        <td style="padding-right:18px;">{{ record.name }}</td>
        <td style="padding-right:18px;">{{ record.age }}</td>
        <td>{{ record.birthday }}</td>
      </tr>
    </tbody>
  </table>  
</div>
</body>
</html>