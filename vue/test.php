<html>
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
      var filtered = this.data
      this.activeFilters.forEach(filter => {
        filtered = filtered.filter(record => {
          return filter.name === 'name'
            ? new RegExp(filter.value, 'i').test(record[filter.name])
            : record[filter.name] == filter.value
        })
      })
      return filtered
    }
  },
  methods: {
    addFilter () {
      this.activeFilters.push({
        name: this.filteredProperty,
        value: this.query
      })
      this.query = ''
    },
    removeFilter (idx) {
      this.activeFilters.splice(idx, 1)
    }
  }
})
</script>
<body>

</body>
</html>