<template>
<div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="pull-left">
        <i class=""></i> <strong>Lista de materiales</strong>
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
                  @click="largeModal=true;loadData(material.id)"><i class="fa fa-edit"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <pagination :records="totalMateriales" :per-page="perpage" @paginate="setPage"></pagination>
      </div>
    </div>
    <modal title="Modal title" large v-model="largeModal" @ok="largeModal = false" effect="fade/zoom" :callback="validateBeforeSubmit">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">Información del material</h4>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-block">
              <form action="" method="post" enctype="multipart/form-data" class="form-horizontal ">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                  <div class="col-md-9">
                    <input type="text" id="nombre-input" name="nombre-input" class="form-control" placeholder="Text" v-model="newMaterial.nombre" disabled>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">SKU</label>
                  <div class="col-md-9">
                    <input type="text" id="sku-input" name="sku-input" class="form-control" placeholder="Text" v-model="newMaterial.sku" disabled>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Cantidad</label>
                  <div class="col-md-9">
                    <input class="input form-control" type="number" placeholder="escribe la wea" min="1" v-model="newMaterial.cantidad" disabled>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Ubicación</label>
                  <div class="col-md-9">
                    <input type="text" id="ubicacion-input" name="ubicacion-input" class="form-control" placeholder="Text" v-model="newMaterial.ubicacion">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="textarea-input">Observaciones</label>
                  <div class="col-md-9">
                    <textarea id="observaciones-input" name="observaciones-input" rows="3" class="form-control" placeholder="Content.." v-model="newMaterial.observaciones"></textarea>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </modal>
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
        perpage: 10,
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
        warningModal: false,
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
      this.materiales.forEach(function(element){
        if(element.id == index){
          parent.newMaterial = element;
          return;
        }
      })
    },
    validateBeforeSubmit: function() {
      
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.updateMaterial();
          return;
        }
        this.largeModal = true;
      });
    },
    updateMaterial: function(){
      axios.put('materiales/'+this.newMaterial.id,this.newMaterial).then((response) =>{
        this.response_add = response.data;
        this.fetchItems();
      });
      this.reset();
    },
  }
}
</script>