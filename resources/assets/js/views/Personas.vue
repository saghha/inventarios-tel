<template>
<div class="col-lg-12">
  <div class="card">
    <div class="card-header">
      <div class="pull-left">
      <i class=""></i> Lista de alumnos
      </div>
      <div class="pull-right">
        <button type="button" class="btn btn-sm btn-success" @click="largeModal = true; action='create'; reset()"><i class="fa fa-plus"></i> Agregar</button>
      </div>
      <div class="pull-right" style="padding-right: 10px;">
        <button type="button" class="btn btn-sm btn-success" @click="personaModal = true; action='create'; reset()"><i class="fa fa-plus" ></i> Carga masiva</button>
      </div>
    </div>
    <div class="card-block">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Rol</th>
              <th>Rut</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(persona, index) in tabla" :key="index">
              <td>{{persona.nombre}} {{persona.apellido_p}} {{persona.apellido_m}}</td>
              <td>{{persona.rol}}</td>
              <td>{{persona.rut}}</td>
              <td>
                <a data-toggle="tooltip" data-placement="top" title class="btn btn-secondary btn-xs " data-original-title="Editar"
                @click="largeModal=true;action='edit';loadData(index+(page-1)*perpage)"><i class="fa fa-edit"></i></a>
                <a data-toggle="tooltip" data-placement="top" title class="btn btn-danger btn-xs " data-original-title="Eliminar"
                @click="warningModal = true; loadData(index+(page-1)*perpage)"><i class="fa fa-times"></i></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination :records="totalPersonas" :per-page="perpage" @paginate="setPage"></pagination>
    </div>
  </div>
  <modal title="Agregar alumno" large v-model="largeModal" @ok="largeModal = false" effect="fade/zoom" :callback="validateBeforeSubmit">
    <div slot="modal-header" class="modal-header">
      <h4 class="modal-title">Agregar alumno</h4>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-block">
            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal ">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="nombre" :class="{'has-error': errors.has('nombre') }">Nombres</label>
                <div class="col-md-9">
                  <input type="text" id="nombre-input" v-validate.initial="'required'" name="nombre" class="form-control" :class="{'input': true, 'is-danger': errors.has('nombre') }" placeholder="Text" v-model="newPersona.nombre">
                  <p class="text-danger" v-if="errors.has('nombre')">{{ errors.first('nombre') }}</p>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="apellido-paterno" :class="{'has-error': errors.has('apellido-paterno') }">Apellido Paterno</label>
                <div class="col-md-9">
                  <input type="text" id="apellido-p-input" v-validate.initial="'required'" name="apellido-paterno" class="form-control" :class="{'input': true, 'is-danger': errors.has('apellido-paterno') }" placeholder="Text" v-model="newPersona.apellido_p">
                  <p class="text-danger" v-if="errors.has('apellido-paterno')">{{ errors.first('apellido-paterno') }}</p>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="apellido-materno" :class="{'has-error': errors.has('apellido-materno') }">Apellido Materno</label>
                <div class="col-md-9">
                  <input type="text" id="apellido-m-input" name="apellido-materno" class="form-control" :class="{'input': true, 'is-danger': errors.has('apellido-materno') }" placeholder="Text" v-model="newPersona.apellido_m">
                  <p class="text-danger" v-if="errors.has('apellido-materno')">{{ errors.first('apellido-materno') }}</p>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="rol" :class="{'has-error': errors.has('rol') }">Rol</label>
                <div class="col-md-9">
                  <input type="text" id="rol-input" v-validate.initial="'required'" name="rol" class="form-control" :class="{'input': true, 'is-danger': errors.has('rol') }" placeholder="Text" v-model="newPersona.rol">
                  <p class="text-danger" v-if="errors.has('rol')">{{ errors.first('rol') }}</p>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="rut" :class="{'has-error': errors.has('rut') }">Rut</label>
                <div class="col-md-9">
                  <input type="text" id="rut-input" v-validate.initial="'required'" v-rut:live @keyup="formato_rut()" name="rut" class="form-control" :class="{'input': true, 'is-danger': errors.has('rut') }" placeholder="Text" v-model="newPersona.rut">
                  <p class="text-danger" v-if="errors.has('rut')">{{ errors.first('rut') }}</p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </modal>
  <modal title="Modal information" class="modal-info" small v-model="smallModal" @ok="smallModal = false" effect="fade/zoom">
    <div slot="modal-header" class="modal-header">
      <h4 class="modal-title">Información</h4>
    </div>
    {{message}}
  </modal>
  <modal title="Modal massiva" class="modal-info" v-model="personaModal" @ok="personaModal = false" effect="fade/zoom" :callback="save">
    <div slot="modal-header" class="modal-header">
      <h4 class="modal-title">Carga masiva</h4>
    </div>
    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal ">
      <div class="form-group row">
        <label class="col-md-3 form-control-label" for="file-input">Seleccione el archivo</label>
        <div class="col-md-9">
          <input type="file" id="file-input" :name="uploadFieldName" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files); " accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="input-file">
        </div>
      </div>
    </form>
  </modal>
  <modal title="Modal confirmation" class="modal-warning" v-model="warningModal" @ok="warningModal = false" effect="fade/zoom" :okText="'Ok'" :cancelText="'Cancelar'" :callback="deletePersona">
    <div slot="modal-header" class="modal-header">
      <h4 class="modal-title">Confirmar eliminación</h4>
    </div>
    ¿Desea eliminar al alumno de los registros?
  </modal>
</div>
</template>
<script>
import axios from 'axios';
import modal from 'vue-strap/src/Modal';
import VueNumeric from 'vue-numeric';
import VeeValidate from 'vee-validate';
import { upload } from './components/excel-upload.service';
import {Pagination} from 'vue-pagination-2';
import { rutValidator } from 'vue-dni';
import { rutInputDirective } from 'vue-dni';

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;
export default {
  name: 'Personas',
  inject: ['$validator'],
  components:{
    modal,
    VueNumeric,
    Pagination,
    rutValidator,
  },
  data(){
    return{
        personas: [],
        tabla: [],
        perpage: 50,
        personaModal: false,
        message: "",
        page: 1,
        rut: "",
        url: [],
        formDataGlobal: null,
        uploadFieldName: 'excel',
        action: 'create',
        totalPersonas: 0,
        response: "",
        newPersona:{
            nombre: "",
            apellido_p: "",
            apellido_m: "",
            rol: "",
            rut: ""
        },
        largeModal: false,
        smallModal: false,
        largeModalResponse: false,
        warningModal: false,
    }
  },
  created(){
    this.fetchPersonas();
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
    setPage: function(page) {
      this.page = page;
      this.tabla = [];
      let i = 0;
      for(i = ((page-1)*this.perpage); i<(page*this.perpage); i++){
        if(i < this.personas.length){
          this.tabla.push(this.personas[i]);
        }
      }
    },
    loadData: function(index){
      this.newPersona = this.personas[index];
    },
    fetchPersonas: function(){
      axios.get('/personas').then((response)=> {
          this.personas = response.data;
          this.totalPersonas = this.personas.length;
          this.setPage(1);
          //this.tabla.push(this.personas[0]);
      });
    },
    addPersona: function(){
      axios.post('/personas',this.newPersona).then((response)=>{
        this.response = response.data;
        this.message = "Se añadió correctamente.";
        this.smallModal = true;
        this.fetchPersonas();
      });
    },
    updatePersona: function(){
      axios.put('/personas/'+thi.newPersona.id,this.newPersona).then((response)=>{
        this.response = response.data;
        this.mesage = "Se actualizó correctamente la información.";
        this.smallModal = true;
        this.fetchPersonas();
      });
    },
    validateBeforeSubmit: function() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          if(this.action === 'create'){
            this.addPersona();
            console.log("creando...");
          }
          else if(this.action === 'edit'){
            this.updatePersona();
            console.log("editando");
          }
          return;
        }
        this.largeModal = true;
      });
    },
    reset: function(){
      this.newPersona = {
            nombre: "",
            apellido_p: "",
            apellido_m: "",
            rol: "",
            rut: ""
        };
    },
    deletePersona: function(){
      axios.delete('/personas/'+this.newPersona.id).then((response)=>{
        this.response = response.data;
        this.fetchPersonas();
      })
    },
    save() {
        // upload data to the server
        this.currentStatus = STATUS_SAVING;

        upload(this.formDataGlobal)
          .then(x => {
            this.url = x;
            this.currentStatus = STATUS_SUCCESS;
            //this.largeModalResponse = true;
            //this.personaModal = false;
            this.fetchPersonas();
          })
          .catch(err => {
            this.uploadError = err.response;
            this.currentStatus = STATUS_FAILED;
          });
      },
    filesChange(fieldName, file) {
      // handle file changes
      const formData = new FormData();
      //var parent = this;
      //this.formData = new FormData();
      if (!file.length) return;

      // append the files to FormData
      //formData.photo = file;
      Array
        .from(Array(file.length).keys())
        .map(x => {
          formData.append(fieldName, file[x], file[x].name);
        });

      // save it
      //this.save(formData);
      this.formDataGlobal = formData;
    }
  }
}
</script>