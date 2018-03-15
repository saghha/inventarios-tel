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
              <input class="input form-control" type="text" placeholder="Nombre" v-validate.initial="'required'" name="nombre" :class="{'input': true, 'is-danger': errors.has('nombre') }" v-model="usuario.name">
              <p class="text-danger" v-if="errors.has('nombre')">{{ errors.first('nombre') }}</p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Correo</label>
          <div class="col-md-9">
              <input class="input form-control" type="text" placeholder="Correo" v-validate.initial="'required|email'" name="correo" :class="{'input': true, 'is-danger': errors.has('correo') }" v-model="usuario.email">
              <p class="text-danger" v-if="errors.has('correo')">{{ errors.first('correo') }}</p>
              <p class="text-danger" v-if="validate_email">{{message_email}}</p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Contaseña</label>
          <div class="col-md-9">
              <vue-password v-model="usuario.password"
                classes="input"
                :user-inputs="[usuario.email]">
              </vue-password>
              <p class="text-danger" v-if="validate_length">{{message_pass}}</p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Confirmar contraseña</label>
          <div class="col-md-9">
              <vue-password v-model="usuario.password_confirmation"
                classes="input"
                :user-inputs="[usuario.email]">
              </vue-password>
              <p class="text-danger" v-if="validate">{{message_pass}}</p>
          </div>
        </div>
      </form>   
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-sm btn-primary pull-right" @click="validateBeforeSubmit"><i class="fa fa-dot-circle-o"></i> Submit</button>
        <button type="reset" class="btn btn-sm btn-danger pull-right"><i class="fa fa-ban"></i> Reset</button>
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
import VeeValidate from 'vee-validate';
import axios from 'axios';
import modal from 'vue-strap/src/Modal';
import VuePassword from 'vue-password'
export default {
  name: 'Registrar',
  components: {
    modal,
    VeeValidate,
    VuePassword
  },
  data(){
    return{
      usuario:{
          name: '',
          email: '',
          password: '',
          password_confirmation: ''
      },
      usuarios: [],
      smallModal: false,
      message: '',
      response: '',
      validate: false,
      validate_pass: false,
      message_pass: '',
      validate_length: false,
      validate_email: false,
      message_email: ''
    }
  },
  created(){
    this.fecthData();
  },
  methods:{
    fecthData: function(){
      axios.get('/getUsers').then((response)=>{
        this.usuarios = response.data;
      });
    },

    validateBeforeSubmit: function(){
      this.falgsDown();
      let parent = this;
      let cond = false;
      this.usuarios.forEach(function(element){
        if(parent.usuario.email == element.email){
          cond = true;
        }
      });
      if(cond){
        this.message_email = 'el correo ya está registrado';
        this.validate_email = true;
      }
      else{
        this.validate_email = false;
        this.$validator.validateAll().then((result) => {
          if (result) {
            if(this.usuario.password.length < 6){
              this.message_pass = 'la contraseña debe tener mínimo 6 caracteres.';
              this.validate_length = true;
            }
            else if(this.usuario.password == this.usuario.password_confirmation){
              this.submit();
            }
            else{
              this.message_pass = 'la contraseña no coincide.'
              this.validate = true;
            }
          }
        });
      }
    },
    submit: function(){
      axios.post('/registerUser', this.usuario).then((response)=>{
        this.response = response.data;
        if(this.response.result.type == 'Error'){
          this.validate_pass = true;
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
    },
    falgsDown: function(){
      this.validate = false;
      this.validate_pass = false;
      this.validate_length = false;
      this.validate_email = false;
    }
  }
}
</script>