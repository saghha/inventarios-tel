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
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(material, index) in someMethod(tabla)" :key="index">
                <td>{{material.nombre}}</td>
                <td>{{material.cantidad}}</td>
                <td>{{material.descripcion}}</td>
                <td>
                  <a data-toggle="tooltip" data-placement="top" title class="btn btn-secondary btn-xs " data-original-title="Editar"
                  @click="largeModal=true;action='edit';loadData(material.id)"><i class="fa fa-edit"></i></a>
                  <!-- <a data-toggle="tooltip" data-placement="top" title class="btn btn-danger btn-xs " data-original-title="Eliminar"
                  @click="warningModal = true; loadData(material.id)"><i class="fa fa-times"></i></a> -->
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <pagination :records="totalMateriales" :per-page="perpage" @paginate="setPage"></pagination>
      </div>
    </div>
    <modal title="Modal title" large v-model="largeModal" @ok="largeModal = false" effect="fade/zoom">
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
                  <label class="col-md-3 form-control-label" for="text-input">Cantidad prestada</label>
                  <div class="col-md-9">
                    <input class="input form-control" type="number" placeholder="cantidad" min="1" v-model="newMaterial.cantidad" disabled>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="textarea-input">Observaciones</label>
                  <div class="col-md-9">
                    <textarea id="observaciones-input" name="observaciones-input" rows="3" class="form-control" placeholder="Content.." v-model="newMaterial.descripcion"></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <h3 class="col-md-12 text-center" for="textarea-input">Inofrmación prestamos</h3>
                </div>
                <div class="form-group row">
                  <div class="row col-md-12">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-block">
                          <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                              <thead>
                                <tr>
                                <th>Nombre alumno</th>
                                <th>Rol</th>
                                <th>Cantidad</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(prestamo, index) in newMaterial.personas" :key="index" >
                                <td>{{ prestamo.nombre_persona }} {{ prestamo.apellido_p}} {{prestamo.apellido_m}}</td>
                                <td>{{prestamo.rol}}</td>
                                <td>{{ prestamo.cantidad }}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!--/.col-->
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
  name: 'MaterialesPrestados',
  inject: ['$validator'],
  components:{
    modal,
    Datepicker,
    VueNumeric,
    Pagination,
  },
  data(){
      return{
          page: 1,
          name: "",
          tabla: [],
          totalMateriales: 0,
          materiales: [],
          newMaterial: {
              nombre: "",
              cantidad: '',
              descripcion: '',
              personas:[]
          },
          perpage: 10,
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
        axios.get('/material/prestados').then((response)=> {
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
  }
}
</script>