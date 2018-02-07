<template lang="html">
  <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <div class="pull-left">
            <i class="fa fa-align-justify"></i> Simple Table
            </div>
            <div class="pull-right">
              <button type="button" class="btn btn-sm btn-success" @click="largeModal = true"><i class="fa fa-plus"></i> Agregar</button>
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
                  <tr v-for="material in materiales">
                    <td>{{material.nombre}}</td>
                    <td>{{material.cantidad}}</td>
                    <td>{{material.descripcion}}</td>
                    <td><button type="button" class="btn btn-warning">Edit</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="#">Prev</a></li>
              <li class="page-item active">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">4</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </div>
        </div>
        <modal title="Modal title" large v-model="largeModal" @ok="largeModal = false" effect="fade/zoom" :callback="hola">
          <div slot="modal-header" class="modal-header">
            <h4 class="modal-title">Modal title</h4>
          </div>
          <div class="card">
          <div class="card-header">
            <strong>Basic Form</strong> Elements
          </div>
          <div class="card-block">
            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal ">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                <div class="col-md-9">
                  <input type="text" id="text-input" name="text-input" class="form-control" placeholder="ingrese nombre del material" v-model="newMaterial.nombre">
                  <span class="help-block">This is a help text</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">SKU</label>
                <div class="col-md-9">
                  <input type="text" id="sku-input" name="text-input" class="form-control" placeholder="ingrese codigo del material" v-model="newMaterial.sku">
                  <span class="help-block">This is a help text</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="textarea-input">Descripción</label>
                <div class="col-md-9">
                  <textarea id="descripcion-input" name="textarea-input" rows="3" class="form-control" placeholder="Content.." v-model="newMaterial.descripcion"></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Cantidad</label>
                <div class="col-md-9">
                  <input class="input form-control" type="number" placeholder="escribe la wea" v-model="newMaterial.cantidad" min="1">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Ubicación</label>
                <div class="col-md-9">
                  <input type="text" id="ubicacion-input" name="text-input" class="form-control" placeholder="ingrese ubicación del material" v-model="newMaterial.ubicacion">
                  <span class="help-block">This is a help text</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="textarea-input">Observaciones</label>
                <div class="col-md-9">
                  <textarea id="obervaciones-input" name="textarea-input" rows="3" class="form-control" placeholder="Content.." v-model="newMaterial.obervaciones"></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="file-input">File input</label>
                <div class="col-md-9">
                  <input type="file" id="file-input" name="file-input">
                </div>
              </div>
            </form>
          </div>
        </div>
        </modal>
      </div><!--/.col-->
</template>

<script>
import axios from 'axios';
import modal from 'vue-strap/src/Modal';
import Datepicker from 'vuejs-datepicker';
import VueNumeric from 'vue-numeric'
export default {
  name: 'Materiales',
  components:{
    modal,
    Datepicker,
    VueNumeric
  },
  data(){
    return{
      materiales: [],
      largeModal: false,
      newMaterial: {
        nombre: "",
        sku: "",
        descripcion: "",
        imagen: "",
        cantidad: 0,
        ubicacion: "",
        observaciones: ""
      }
    };
  },
  created(){
    this.fetchItems();
    console.log("monte la wea");
  },
  methods:{
    fetchItems: function(){
      axios.get('/materiales').then((response)=> {
        this.materiales = response.data;
      });
    },
    hola: function(){
      console.log(this.newMaterial);
    }
  }
}
</script>
