<template>
  <div class="animated fadeIn">
    <h5><strong>{{userName}}, bienvenid@ a la plataforma de gestión de inventario</strong></h5>
    <div class="row">
      <div class="col-sm-4 col-lg-4">
        <div class="card card-inverse card-primary">
          <div class="card-block">
            <div class="row">
              <div class="col-sm-7">
                <h4 class="mb-0">{{numeroAlumnos}}</h4>
                <p>Alumnos en el sistema</p>
              </div>
              <div class="col-sm-5">
                <img src="/images/avatar-2.png" class="img-responsive" style="max-height:112px">
              </div>
            </div>
          </div>
        </div>
      </div><!--/.col-->
      <div class="col-sm-4 col-lg-4">
        <div class="card card-inverse card-primary">
          <div class="card-block">
            <div class="row">
              <div class="col-sm-7">
                <h4 class="mb-0">{{numeroPrestamos}}</h4>
                <p>Prestamos vigentes</p>
              </div>
              <div class="col-sm-5">
                <img src="/images/book.png" class="img-responsive" style="max-height:112px">
              </div>
            </div>
          </div>
        </div>
      </div><!--/.col-->
      <div class="col-sm-4 col-lg-4">
        <div class="card card-inverse card-primary">
          <div class="card-block">
            <div class="row">
              <div class="col-sm-7">
                <h4 class="mb-0">{{numeroPrestados}}</h4>
                <p>Materiales prestados</p>
              </div>
              <div class="col-sm-5">
                <img src="/images/box.png" class="img-responsive" style="max-height:112px">
              </div>
            </div>
          </div>
        </div>
      </div><!--/.col-->
    </div><!--/.row-->

    <div class="card">
      <div class="card-header">
        <div class="pull-left">
          <i class=""></i><strong> Materiales más pedidos</strong>
        </div>
      </div>
      <div class="card-footer">
        <ul>
          <li v-for="(item, index) in mostRequired" :key="index" v-bind:class="getClass(index)">
            <div class="text-muted">{{item.nombre}}</div>
            <strong>{{item.cantidad}} ({{item.porcentaje}}%)</strong>
            <div class="progress progress-xs mt-h">
              <div class="progress-bar bg-success" role="progressbar" :class="[item.porcentaje > 50 ? 'bg-danger' : 'bg-success']" :style="{width: item.porcentaje + '%'}" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </li>
        </ul>
      </div>
    </div><!--/.card-->
  </div>
</template>

<script>
import CardLine1ChartExample from './dashboard/CardLine1ChartExample'
import axios from 'axios';

import { dropdown } from 'vue-strap'

export default {
  name: 'dashboard',
  components: {
    CardLine1ChartExample,
    dropdown
  },
  data(){
    return{
      numeroAlumnos: 0,
      numeroPrestamos: 0,
      numeroPrestados: 0,
      mostRequired: [],
      userName: ''
    }
  },
  created(){
    this.getMetricas();
    this.getUserName();
  },
  methods:{
    getMetricas: function(){
      axios.get('/persona/count').then((response) =>{
        this.numeroAlumnos = response.data.alumnos;
      });
      axios.get('/prestamo/disponibles').then((response)=>{
        this.numeroPrestamos = response.data.length;
      });
      axios.get('/material/countprestados').then((response)=>{
        this.numeroPrestados = response.data.prestados;
      });
      axios.get('/material/mostrequired').then((response) => {
        this.mostRequired = response.data;
      })
    },
    getClass: function(index){
      if(index%2 == 0){
        return" hidden-sm-down";
      }
      else{
        return "";
      }
    },
    getUserName: function(){
      axios.get('/user/name').then((response) => {
        this.userName = response.data;
      });
    },
  }
}
</script>
