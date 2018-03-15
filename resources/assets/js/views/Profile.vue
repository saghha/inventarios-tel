<template>
  <div class="card">
    <div class="card-header">
        <strong>Información del usuario</strong>
    </div>
    <div class="card-block">
      <form method="post" enctype="multipart/form-data" class="form-horizontal ">
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="nombre" >Nombre</label>
          <div class="col-md-9">
              <input class="input form-control" type="text" placeholder="Nombre" v-model="usuario.name" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Correo</label>
          <div class="col-md-9">
              <input class="input form-control" type="text" placeholder="Correo" v-model="usuario.email" disabled>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input"><strong>Cambiar contraseña</strong></label>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Contraseña actual</label>
          <div class="col-md-9">
              <input class="input form-control" type="password" placeholder="Contraseña actual" v-model="change.password">
              <p class="text-danger" v-if="validate_pass">La contraseña no es correcta</p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Contaseña nueva</label>
          <div class="col-md-9">
              <vue-password v-model="change.new"
                classes="input"
                :user-inputs="[usuario.email]">
              </vue-password>
              <p class="text-danger" v-if="validate_length">{{message_pass}}</p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Confirmar contraseña nueva</label>
          <div class="col-md-9">
              <vue-password v-model="change.new_confirmation"
                classes="input"
                :user-inputs="[usuario.email]">
              </vue-password>
              <p class="text-danger" v-if="validate">{{message_pass}}</p>
          </div>
        </div>
        <div class="form-group row pull-right">
          <button type="button" class="btn btn-success" @click="validateBeforeSubmit">Cambiar</button>
        </div>
      </form>   
    </div>
    <modal title="Modal information" small v-model="smallModal" @ok="smallModal = false" effect="fade/zoom" :okText="'Ok'">
        <div slot="modal-header" class="modal-header">
            <h4 class="modal-title">Información</h4>
        </div>
        {{message}}
    </modal>
  </div>
</template>
<script>
import axios from 'axios';
import modal from 'vue-strap/src/Modal';
import VuePassword from 'vue-password'
export default {
  name: 'Profile',
  components: {
    modal,
    VuePassword
  },
  data(){
    return{
      usuario:{},
      smallModal: false,
      message: '',
      change: {
        password: '',
        new: '',
        new_confirmation: '',
      },
      response: '',
      validate: false,
      validate_pass: false,
      message_pass: '',
      validate_length: false,
    }
  },
  created(){
    this.fecthData();
  },
  methods:{
    fecthData: function(){
      axios.get('/get_user').then((response)=>{
        this.usuario = response.data;
      });
    },
    validateBeforeSubmit: function(){
      if(this.change.new.length < 6){
        this.message_pass = 'la contraseña debe tener mínimo 6 caracteres.';
        this.validate_length = true;
      }
       else if(this.new == this.new_confirmation){
        this.submit();
      }
      else{
        this.message_pass = 'la contraseña no coincide.'
        this.validate = true;
      }
    },
    submit: function(){
      axios.post('/changepass', this.change).then((response)=>{
        this.response = response.data;
        if(this.response.result.type == 'Error'){
          this.message = this.response.result.message;
          this.smallModal = true;
        }
        else{
          this.validate = false;
          this.validate_pass = false;
          this.validate_length = false;
          this.message = this.response.result.message;
          this.smallModal = true;
        }
      });
    }
  }
}
</script>