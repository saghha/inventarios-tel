<template lang="html">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="pull-left">
        <i class=""></i> Lista de materiales
        </div>
        <div class="pull-right">
          <button type="button" class="btn btn-sm btn-success" @click="largeModal = true; action='create'"><i class="fa fa-plus"></i> Agregar</button>
        </div>
        <div class="pull-left">
          <input type="text" placeholder="buscar" @keyup="someMethod(materiales.data)" v-model="name" class="form-control">
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
              <tr v-for="(material, index) in someMethod(materiales.data)">
                <td>{{material.nombre}}</td>
                <td>{{material.cantidad}}</td>
                <td>{{material.descripcion}}</td>
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
        <pagination :data="materiales" v-bind:showDisabled="true" icon="chevron" v-on:change-page="fetchItems"></pagination>
      </div>
    </div>
    
    <modal title="Modal title" large v-model="largeModal" @ok="largeModal = false" effect="fade/zoom" :callback="validateBeforeSubmit">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">Agregar nuevo material</h4>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-block">
              <form action="" method="post" enctype="multipart/form-data" class="form-horizontal ">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input" :class="{'has-error': errors.has('nombre') }">Nombre</label>
                  <div class="col-md-9">
                    <input type="text" id="nombre-input" name="nombre-input" class="form-control" :class="{'input': true, 'is-danger': errors.has('nombre') }" placeholder="Text" v-model="newMaterial.nombre">
                    <p class="text-danger" v-if="errors.has('nombre')">{{ errors.first('nombre') }}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">SKU</label>
                  <div class="col-md-9">
                    <input type="text" id="sku-input" name="sku-input" class="form-control" placeholder="Text" v-model="newMaterial.sku">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Cantidad</label>
                  <div class="col-md-9">
                    <input class="input form-control" type="number" placeholder="escribe la wea" min="1" v-model="newMaterial.cantidad">
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
                  <textarea id="observaciones-input" name="observaciones-input" rows="9" class="form-control" placeholder="Content.." v-model="newMaterial.observaciones"></textarea>
                </div>
              </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="file-input">File input</label>
                  <div class="col-md-9">
                    <input type="file" id="file-input" :name="uploadFieldName" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files); " accept="image/*" class="input-file">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </modal>
    <modal title="Modal title" class="modal-warning" v-model="warningModal" @ok="warningModal = false" effect="fade/zoom" :okText="'Ok'" :cancelText="'Cancelar'" :callback="deleteMaterial">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">Confirmar eliminación</h4>
      </div>
      ¿Desea eliminar el material?
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
import pagination from 'laravel-vue-semantic-ui-pagination';

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;
export default {
  name: 'Materiales',
  inject: ['$validator'],
  components:{
    modal,
    Datepicker,
    VueNumeric,
    pagination,
  },
  data(){
    return{
      materiales: {
        data: []
      },
      largeModal: false,
      resource_url: 'cap.local/materiales',
      newMaterial: {
        nombre: "",
        sku: "",
        descripcion: "",
        imagen: "",
        cantidad: 1,
        ubicacion: "",
        observaciones: ""
      },
      uploadedFiles: [],
      uploadError: null,
      currentStatus: null,
      warningModal: false,
      uploadFieldName: 'photo',
      response_add: '',
      search: false,
      name: '',
      materiales_filtrados: [],
      action: 'create',
    };
  },
  created(){
    this.fetchItems(1);
    console.log("monte la wea");
  },
  
  computed: {
    isInitial() {
        return this.currentStatus === STATUS_INITIAL;
    },
    isSaving() {
        return this.currentStatus === STATUS_SAVING;
    },
    isSuccess() {
        return this.currentStatus === STATUS_SUCCESS;
    },
    isFailed() {
        return this.currentStatus === STATUS_FAILED;
    },

  },
  methods:{
    someMethod: function(items) {
      var parent = this;
      //this.name = this.name.toLowerCase();
      return items.filter(function(item){
        if(item.nombre.toLowerCase().includes(parent.name.toLowerCase())){
          return item;
        }
      })
    },
    reset: function(){
      this.newMaterial = {
        nombre: "",
        sku: "",
        descripcion: "",
        imagen: "",
        cantidad: 1,
        ubicacion: "",
        observaciones: ""
      };
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

    fetchItems: function(page){
      axios.get('/materiales?page='+page).then((response)=> {
        this.materiales = response.data;
      });
    },
    validateBeforeSubmit: function() {
      
      this.$validator.validateAll().then((result) => {
        if (result) {
          if(this.action === 'create'){
            this.addMaterial();
          }
          else if(this.action === 'edit'){
            this.updateMaterial();
          }
          return;
        }
        this.largeModal = true;
      });
    },
    addMaterial: function(){
      axios.post('materiales', this.newMaterial).then((response)=> {
        this.response_add = response.data;
        this.fetchItems();
      });
      this.reset();
    },
    deleteMaterial: function(index){
      axios.delete('/materiales/'+this.newMaterial.id).then((response) => {
        this.response_add = response.data;
        this.fetchItems();
      });
    },
    updateMaterial: function(){
      axios.put('materiales/'+this.newMaterial.id,this.newMaterial).then((response) =>{
        this.response_add = response.data;
        this.fetchItems();
      });
      this.reset();
    },
    clearModal: function(){
      this.newMaterial = {
        nombre: "",
        sku: "",
        descripcion: "",
        imagen: "",
        cantidad: 1,
        ubicacion: "",
        observaciones: ""
      };
    },
    save(formData) {
        // upload data to the server
        this.currentStatus = STATUS_SAVING;

        upload(formData)
          .then(x => {
            this.newMaterial.imagen = x;
            this.currentStatus = STATUS_SUCCESS;
          })
          .catch(err => {
            this.uploadError = err.response;
            this.currentStatus = STATUS_FAILED;
          });
      },
    filesChange(fieldName, file) {
      // handle file changes
      const formData = new FormData();

      if (!file.length) return;

      // append the files to FormData
      //formData.photo = file;
      Array
        .from(Array(file.length).keys())
        .map(x => {
          formData.append(fieldName, file[x], file[x].name);
        });

      // save it
      this.save(formData);
    }
  }
}
</script>
