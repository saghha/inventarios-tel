<template>
  <navbar>
    <button class="navbar-toggler mobile-sidebar-toggler hidden-lg-up" type="button" @click="mobileSidebarToggle">&#9776;</button>
    <a class="navbar-brand" href="#"></a>
    <ul class="nav navbar-nav hidden-md-down">
      <li class="nav-item">
        <a class="nav-link navbar-toggler sidebar-toggler" href="#" @click="sidebarToggle">&#9776;</a>
      </li>
      <li class="nav-item px-1">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item px-1">
        <a class="nav-link" href="#/materiales">Materiales</a>
      </li>
      <li class="nav-item px-1">
        <a class="nav-link" href="#/prestamos/vigentes">Prestamos</a>
      </li>
    </ul>
    <ul class="nav navbar-nav ml-auto" style="padding-right:20px;">
      <!-- <li class="nav-item hidden-md-down">
        <a class="nav-link" href="#"><i class="icon-bell"></i><span class="badge badge-pill badge-danger">5</span></a>
      </li>
      <li class="nav-item hidden-md-down">
        <a class="nav-link" href="#"><i class="icon-list"></i></a>
      </li>
      <li class="nav-item hidden-md-down">
        <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
      </li> -->
      <dropdown size="nav" class="nav-item">
        <span slot="button">
          <img src="images/avatar.png" class="img-avatar" alt="admin@bootstrapmaster.com">
          <span class="hidden-md-down">{{userName}}</span>
        </span>
        <div slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">

          <div class="dropdown-header text-center"><strong>Settings</strong></div>

          <a class="dropdown-item" ><i class="fa fa-user"></i> Profile</a>
          <a class="dropdown-item" href="/logout"><i class="fa fa-lock"></i> Logout</a>
        </div>
      </dropdown>
      <!-- <li class="nav-item hidden-md-down">
        <a class="nav-link navbar-toggler aside-menu-toggler" href="#" @click="asideToggle">&#9776;</a>
      </li> -->
    </ul>
  </navbar>
</template>
<script>

import navbar from './Navbar';
import axios from 'axios';
import { dropdown } from 'vue-strap';

export default {
  name: 'header',
  components: {
    navbar,
    dropdown
  },
  data(){
    return{
      userName: '',
    }
  },
  created(){
    this.getUserName();
  },
  methods: {
    click () {
      // do nothing
    },
    sidebarToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('sidebar-hidden')
    },
    mobileSidebarToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('sidebar-mobile-show')
    },
    asideToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('aside-menu-hidden')
    },
    getUserName: function(){
      axios.get('/user/name').then((response) => {
        this.userName = response.data;
      });
    },
    logout: function(){
      axios.post("/logout").then((response)=> {
      });
    }
  }
}
</script>

<style lang="css">

.dropdown-toggle::after {
  /*display: none !important;*/
}
</style>
