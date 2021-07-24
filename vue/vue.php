<html>
<script src="vue.js"></script>


<body>
<div id="app">
  <div>
    <select v-model="filteredProperty">
      <option value="name">Name</option>
      <option value="age">Age</option>
      <option value="birthday">Birthdate</option>
    </select>
    <input placeholder="filter value" v-model="query">    
    <button @click="addFilter">add filter</button>
  </div>
  <hr>
  <table v-if="activeFilters.length">
    <tr style="width: 100px">
      <th colspan="3">Filters in use:</th>
    </tr>
   <!-- <tr v-for="(filter, index) in activeFilters" :key="index">
      <td>{{ filter.name}}:</td>
      <td>{{ filter.value }}</td>
      <td style="padding-left: 10px;">
        <a href="#" @click.prevented="removeFilter(index)">
          remove
        </a>
      </td>
    </tr>-->
  </table>
  <hr v-if="activeFilters.length">
  <table>
    <tbody>
      <tr v-for="(record, index) in filtered" :key="index">
        <td style="padding-right:18px;">{{ record.name }}</td>
        <td style="padding-right:18px;">{{ record.age }}</td>
        <td>{{ record.birthday }}</td>
      </tr>
    </tbody>
  </table>  
</div>
</body>
</html>
<script>
new Vue({
  el: '#app',
  data: {
    filteredProperty: 'name',
    query: '',
    activeFilters: [],
    data: [
      {name: 'Patricia Miller', age: 62, birthday: '04-15-1948'},
      {name: 'Bill Baggett', age:62, birthday: '04-15-1948' },
      {name:'Maxine Thies', age:62, birthday:'11-28-1948'},
      {name:'Alison Battle', age:65, birthday:'08-07-1952'},      
      {name:'Dick Triplett', age:25, birthday:'08-27-1982'}
    ]
  },
  computed: {
    filtered () {
      var filtered = this.data;console.log('inn',filtered,this.activeFilters.length,this.activeFilters);
	  if(this.activeFilters.length>0)	
	  {
      this.activeFilters.forEach(filter => {
		  
        filtered = filtered.filter(record => {
			// console.log(record,'this.activeFilters');
          return filter.name === 'name'
            ? new RegExp(filter.value, 'i').test(record[filter.name])
            : record[filter.name] == filter.value
        })
      });
	  }console.log('innttt',filtered,this.data );
      return filtered
    }
  },
  methods: {
    addFilter () {
		this.activeFilters=[];
		if(this.query!=''){
      this.activeFilters.push({
        name: this.filteredProperty,
        value: this.query
      })
		}
    //  this.query = ''
    },
    removeFilter (idx) {
      this.activeFilters.splice(idx, 1)
    }
  }
})
</script>