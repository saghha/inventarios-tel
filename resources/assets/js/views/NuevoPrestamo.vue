<template>
    <div class="card">
        <div class="card-header">
            <strong>Prestar Material a alumno</strong>
        </div>
        <div class="card-block">
            <form method="post" enctype="multipart/form-data" class="form-horizontal ">
                <div class="form-group row">
                <label class="col-md-3 form-control-label" for="nombre" :class="{'has-error': errors.has('nombre') }">Nombre alumno</label>
                <div class="col-md-9">
                    <TypeAhead
                        v-model="data_name"
                        :classes="classes"
                        :placeholder="placeholder"
                        src="/personas"
                        :getResponse="getResponse"
                        :selectFirst="selectFirst"
                        :limit="parseInt(limit)"
                        :queryParamName="queryParamName"
                        :minChars="parseInt(minChars)"
                        :delayTime="parseInt(delayTime)"
                        :onHit="onHitPerson"
                        :highlighting="highlighting"
                        :render="render"
                        :class="{'input': true, 'is-danger': errors.has('nombre') }"
                        v-validate.initial="'required'"
                        name="nombre"
                    ></TypeAhead>
                    <p class="text-danger" v-if="errors.has('nombre')">{{ errors.first('nombre') }}</p>
                </div>
                </div>
                <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Fecha Prestamo</label>
                <div class="col-md-9">
                    <datepicker :inputClass="'form-control'" placeholder="Select Date" v-model="date" :disabled="disabled" ></datepicker>
                </div>
                </div>
                <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Fecha esperada devolución</label>
                <div class="col-md-9">
                    <datepicker :inputClass="'form-control'" placeholder="Select Date" v-model="date_esperada" :disabled="disabled" ></datepicker>
                </div>
                </div>
                <div class="form-group row">
                <label class="col-md-3 form-control-label" for="textarea-input">comentarios</label>
                <div class="col-md-9">
                    <textarea id="comentarios-input" name="textarea-input" rows="3" class="form-control" placeholder="Content.." v-model="comentarios"></textarea>
                </div>
                </div>
                <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Material</label>
                <div class="col-md-5">
                    <TypeAhead
                        v-model="data"
                        :classes="classes"
                        :placeholder="placeholder"
                        src="/material/disponibles"
                        :getResponse="getResponse"
                        :selectFirst="selectFirst"
                        :limit="parseInt(limit)"
                        :queryParamName="queryParamName"
                        :minChars="parseInt(minChars)"
                        :delayTime="parseInt(delayTime)"
                        :onHit="onHit"
                        :highlighting="highlighting"
                        :render="render"
                    ></TypeAhead>
                </div>
                <div class="col-md-2">
                    <input class="input form-control" type="number" placeholder="escribe la wea" min="1" v-model="cantidad">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-success" @click="agregar()">Agregar</button>
                </div>
                </div>
            </form>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                    <div class="card-block">
                        <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(material, index) in materiales" :key="index" >
                            <td>{{ material.nombre }}</td>
                            <td>{{ material.cantidad }}</td>
                            <td>{{ material.descripcion }}</td>
                            <td class="text-center">
                                <button class="btn btn-outline-danger btn-sm" @click="quitar(index)"><i class="fa fa-trash"></i>Quitar</button>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div><!--/.col-->
            </div><!--/.row-->
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary pull-right" @click="submit()"><i class="fa fa-dot-circle-o"></i> Submit</button>
            <button type="reset" class="btn btn-sm btn-danger pull-right" @click="reset()"><i class="fa fa-ban"></i> Reset</button>
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
    import VueNumeric from 'vue-numeric';
    import Datepicker from 'vuejs-datepicker';
    import TypeAhead from 'vue2-typeahead';
    import modal from 'vue-strap/src/Modal';
    export default {
  name: 'NuevoPrestamo',
  inject: ['$validator'],
  components: {
    VeeValidate,
    VueNumeric,
    Datepicker,
    TypeAhead,
    modal
  },
  data(){
      return{
        data_name: '',
        response: '',
        message: '',        
        data: '',
        cantidad: 1,
        comentarios: '',
        selectFirst: false,
        limit: 9999,
        queryParamName: ':keyword',
        minChars: 2,
        delayTime: 500,
        placeholder: 'Please input something',
        classes: 'typeahead',
        date: null,
        date_esperada: null,
        today: null,
        disabled: {
            to: null
        },
        materiales: [],
        tempMaterial: null,
        tempPersona: null,
        myQueryString: "",
        smallModal: false,
        prestamo: null
      }
  },
  created(){
      this.disabled.to = new Date();
      this.disabled.to.setDate(this.disabled.to.getDate()-1);
      console.log(this.disabled);    
  },
  methods:{
      submit: function(){
          if(this.date == null || this.date_esperada == null || this.materiales.length == 0 || this.data_name == ''){
              this.message = 'Falta completar la información requerida';
              this.smallModal = true;
              return;
          }
          this.prestamo = {
              id_persona: this.tempPersona.id,
              fecha_prestamo: this.date.getFullYear()+"-"+(this.date.getMonth()+1)+"-"+this.date.getDate(),
              fecha_esperada_devolucion: this.date_esperada.getFullYear()+"-"+(this.date_esperada.getMonth()+1)+"-"+this.date_esperada.getDate(),
              comentarios: this.comentarios,
              materiales: this.materiales
          }
          axios.post('/prestamo', this.prestamo).then((response)=> {
            this.response = response.data;
          });
          this.message = "Se ha creado el registro del prestamo de materiales";
          this.smallModal = true;
          this.reset();                  
      },
      reset: function(){
          this.date = null;
          this.date_esperada = null;
          this.materiales = {};
          this.data_name = '';
          this.comentarios = '';
      },
      quitar: function(index){
          this.materiales.splice(index,1);
      },
      agregar: function(){
          var parent = this;
          var cond = true;
          this.materiales.forEach(function(elemento){
            console.log(parent);
            if(parent.tempMaterial.id == elemento.id){
                if(parent.cantidad >= (parent.tempMaterial.cantidad - elemento.cantidad)){
                    cond =false;
                }
            }
          });
          if(parent.cantidad > parent.tempMaterial.cantidad){
              cond = false;
          }
          if(cond){
            parent.tempMaterial.cantidad = parent.cantidad;
            parent.materiales.push(parent.tempMaterial);             
          }
          else{
              parent.message = 'No hay más '+parent.tempMaterial.nombre +' disponibles';
              parent.smallModal = true;
          }
          
      },
      getResponse: function (response) {
        console.log(response);
        return response.data;
      },
      onHit: function (item, vue, index) {
        vue.query = item.nombre;
        this.tempMaterial = item;

      },
      onHitPerson: function(item, vue, index){
        vue.query = item.nombre;
        this.tempPersona = item;  
      },
      highlighting: function (item, vue) {
        console.log(item);
        return item.nombre.replace(vue.query, `<b>${vue.query}</b>`)
      },
      render: function (data, vue) {
        let newItem = data;
        return newItem;
      }
  }
    }
</script>