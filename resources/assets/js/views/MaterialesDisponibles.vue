<template>
<div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="pull-left">
        <i class=""></i> Lista de materiales
        </div>
        <div class="pull-right">
          <input type="text" placeholder="buscar" @keyup="someMethod(tabla)" v-model="name" class="form-control">
        </div>
      </div>
      <div class="card-block">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th>Ubicación</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(material, index) in someMethod(tabla)" :key="index">
                <td>{{material.nombre}}</td>
                <td>{{material.cantidad}}</td>
                <td>{{material.descripcion}}</td>
                <td>{{material.ubicacion}}</td>
                <td>
                  <a data-toggle="tooltip" data-placement="top" title class="btn btn-secondary btn-xs " data-original-title="Editar"
                  @click="largeModal=true;action='edit';loadData(material.id)"><i class="fa fa-edit"></i></a>
                  <a data-toggle="tooltip" data-placement="top" title class="btn btn-danger btn-xs " data-original-title="Eliminar"
                  @click="warningModal = true; loadData(material.id)"><i class="fa fa-times"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <pagination :records="totalMateriales" :per-page="perpage" @paginate="setPage"></pagination>
      </div>
    </div>
  </div><!--/.col-->
</template>
<script>
import axios from 'axios';
import modal from 'vue-strap/src/Modal';
import Datepicker from 'vuejs-datepicker';
import VueNumeric from 'vue-numeric';
import { upload } from './components/file-upload.service';
import VeeValidate from 'vee-validate';
import {Pagination} from 'vue-pagination-2';
export default {
  name: 'MaterialesDisponibles',
  inject: ['$validator'],
  components:{
    modal,
    Datepicker,
    VueNumeric,
    Pagination,
  },
  data(){
    return{
        materiales: [],
        tabla: [],
        name: "",
        page: 1,
        perpage: 1,
        totalMateriales: 0,
        newMaterial: {
            nombre: "",
            sku: "",
            descripcion: "",
            imagen: "",
            cantidad: 0,
            ubicacion: "",
            observaciones: ""
        },
        largeModal: false,
    } 
  },
  created(){
      this.fetchItems();
  },
  methods:{
    setPage: function(page) {
        this.page = page;
        this.tabla = [];
        let i = 0;
        for(i = ((page-1)*this.perpage); i<(page*this.perpage); i++){
            if(i < this.materiales.length){
                this.tabla.push(this.materiales[i]);
            }
        }
    },
    fetchItems: function(page){
        axios.get('/material/disponibles').then((response)=> {
        this.materiales = response.data;
        this.totalMateriales = this.materiales.length;
        this.setPage(1);
        });
    },
    someMethod: function(items) {
        var parent = this;
        //this.name = this.name.toLowerCase();
        return items.filter(function(item){
            if(item.nombre.toLowerCase().includes(parent.name.toLowerCase())){
                return item;
            }
        })
    },
    loadData: function(index){
      //this.newMaterial = this.materiales.data[index];
      var parent = this;
      this.materiales.data.forEach(function(element){
        if(element.id == index){
          parent.newMaterial = element;
          return;
        }
      })
    },
  }
}
</script>