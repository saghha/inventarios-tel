<template>
<div id="filter-by-example">
  <input v-model="name" @keyup="someMethod(users)">
  <ul>
    <li v-for="user in someMethod(users)" :key="user">
      {{ user.name }}
    </li>
  </ul>
</div>
</template>
<script>
export default {
  name: 'Test',
  data() {
    return{
      sortKey: 'name',
      name: '',
      reverse: false,

      search: '',

      columns: ['name', 'age'],

      newUser: {},

      users: [
        { name: 'John', age: 50 },
        { name: 'Jane', age: 22 },
        { name: 'Paul', age: 34 },
        { name: 'Kate', age: 15 },
        { name: 'Amanda', age: 65 },
        { name: 'Steve', age: 38 },
        { name: 'Keith', age: 21 },
        { name: 'Don', age: 50 },
        { name: 'Susan', age: 21 }
      ]
    }
  },
  methods: {
    someMethod: function(items) {
      var parent = this;
      //this.name = this.name.toLowerCase();
      return items.filter(function(item){
        if(item.name.toLowerCase().includes(parent.name.toLowerCase())){
          return item;
        }
      })
    }
  }
}
</script>