<template>
<div class="col-lg-12">
  <div class="card">
    <div class="card-header">
      <div class="pull-left">
      <i class=""></i><strong> Prestamos Vigentes</strong>
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
              <th>Nombre alumno</th>
              <th class="text-center">Fecha prestamo</th>
              <th class="text-center">Fecha esperada devolución</th>
              <th class="text-center">Comentarios</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(prestamo, index) in someMethod(tabla)" :key="index">
              <td>{{prestamo.nombre_persona}} {{prestamo.apellido_p}} {{prestamo.apellido_m}}</td>
              <td class="text-center">{{prestamo.fecha_prestamo.substr(0,10)}}</td>
              <td class="text-center">{{prestamo.fecha_esperada_devolucion.substr(0,10)}}</td>
              <td class="text-center">{{prestamo.comentarios}}</td>
              <td class="text-center">
                <a data-toggle="tooltip" data-placement="top" title class="btn btn-secondary btn-xs " data-original-title="Editar"
                @click="largeModal=true;loadData(prestamo.id)"><i class="fa fa-edit"></i></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination :records="totalPrestamos" :per-page="perpage" @paginate="setPage"></pagination>
    </div>
  </div>
  <modal title="Modal title" large v-model="largeModal" @ok="largeModal = false" effect="fade/zoom" :callback="validateBeforeSubmit">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">Información del prestamo de material</h4>
      </div>
      <form method="post" enctype="multipart/form-data" class="form-horizontal ">
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
          <div class="col-md-9">
            <input type="text" id="nombre" name="nombre" class="form-control" v-model="newPrestamo.nombre_persona">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Fecha Prestamo</label>
          <div class="col-md-9">
              <datepicker :inputClass="'form-control'" placeholder="Select Date" v-model="date" :disabled="disabled_prestamo" ></datepicker>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Fecha Prestamo</label>
          <div class="col-md-9">
              <datepicker :inputClass="'form-control'" placeholder="Select Date" v-model="date_esperada" :disabled="disabled_esperada" ></datepicker>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="text-input">Fecha devolución</label>
          <div class="col-md-9">
              <datepicker :inputClass="'form-control'" placeholder="Select Date" v-model="date_devolucion" :disabled="disabled_devolucion" ></datepicker>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="textarea-input">Comentarios</label>
          <div class="col-md-9">
            <textarea id="obervaciones-input" name="textarea-input" rows="2" class="form-control" v-model="newPrestamo.comentarios"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <h3 class="col-md-12 text-center" for="textarea-input">materiales</h3>
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
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(material, index) in materiales" :key="index" >
                        <td>{{ material.nombre }}</td>
                        <td>{{ material.pivot.cantidad }}</td>
                        <td>{{ material.descripcion }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div><!--/.col-->
        </div>
      </form>
    </modal>
    <modal title="Modal title" small v-model="smallModal" @ok="smallModal = false" effect="fade/zoom" :okText="'Confirmar'" :callback="submit">
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
import Datepicker from 'vuejs-datepicker';
import {Pagination} from 'vue-pagination-2';
export default {
  name: 'PrestamosVigentes',
  components:{
    modal,
    Datepicker,
    Pagination,
  },
  data(){
    return{
      prestamos: [],
      materiales: [],
      tabla: [],
      page: 1,
      totalPrestamos: 0,
      perpage: 10,
      name: "",
      message: "",
      response: null,
      newPrestamo: {
        apellido_m: "",
        apellido_p: "",
        comentarios: "",
        fecha_prestamo: "",
        nombre_persona: "",
        fecha_esperada_devolucion: ""
      },
      largeModal: false,
      smallModal: false,
      date: null,
      date_esperada: null,
      date_devolucion: null,
      disabled_prestamo:{
        to: null,
        from: null
      },
      disabled_esperada:{
        to: null,
      },
      disabled_devolucion:{
        to: null
      },
      gridColumns: ['name', 'power'],
    gridData: [{
      name: 'Chuck Norris',
      power: Infinity
    }, {
      name: 'Bruce Lee',
      power: 9000
    }, {
      name: 'Jackie Chan',
      power: 7000
    }, {
      name: 'Jet Li',
      power: 8000
    }]
    }
  },
  created(){
    this.fetchPrestamos();
  },
  methods:{
    someMethod: function(items) {
      var parent = this;
      //this.name = this.name.toLowerCase();
      return items.filter(function(item){
        if(item.nombre_persona.toLowerCase().includes(parent.name.toLowerCase())){
          return item;
        }
      })
    },
    setPage: function(page) {
      this.page = page;
      this.tabla = [];
      let i = 0;
      for(i = ((page-1)*this.perpage); i<(page*this.perpage); i++){
        if(i < this.prestamos.length){
          this.tabla.push(this.prestamos[i]);
        }

      }
    },
    fetchPrestamos: function(){
      parent = this;
      axios.get('/prestamo/disponibles').then((response)=> {
        this.prestamos = response.data;
        this.totalPrestamos = this.prestamos.length;
        this.setPage(1);
        //this.tabla.push(this.prestamos[0]);
      });
      this.disabled_devolucion.to = new Date();
      this.disabled_devolucion.to.setDate(this.disabled_devolucion.to.getDate()-1);
      
    },
    loadData(index){
      this.date_devolucion = null;
      //this.newPrestamo = this.prestamos[index];
      var parent = this;
      this.prestamos.forEach(function(element){
        if(element.id == index){
          parent.newPrestamo = element;
          return;
        }
      });
      this.materiales = this.newPrestamo.get_materiales;
      let fecha = this.newPrestamo.fecha_prestamo.split(" ");
      let data = fecha[0].split("-");
      let fecha_esperada = this.newPrestamo.fecha_esperada_devolucion.split(" ");
      let data_esperada = fecha_esperada[0].split("-");
      this.date = new Date(data[0],(parseInt(data[1])-1),data[2]);
      this.date_esperada = new Date(data_esperada[0],(parseInt(data_esperada[1])-1),data_esperada[2]);
      this.disabled_prestamo.to = this.date;
      this.disabled_prestamo.from = this.date;
      this.disabled_esperada.to = this.date_esperada;
    },
    validateBeforeSubmit: function(){
      if(this.date_devolucion != null){
        this.newPrestamo.fecha_devolucion = this.date_devolucion.getFullYear()+"-"+(this.date_devolucion.getMonth()+1)+"-"+this.date_devolucion.getDate();
        this.message = "¿Desea confirmar la devoluciones de los materiales?";
      }
      else{
        this.message = "¿Desea confirmar los cambios realizados?"
        this.newPrestamo.fecha_esperada_devolucion = this.date_esperada.getFullYear()+"-"+(this.date_esperada.getMonth()+1)+"-"+this.date_esperada.getDate();
      }
      this.smallModal = true;
    },
    submit: function(){
      axios.put('/prestamo/'+this.newPrestamo.id,this.newPrestamo).then((response) =>{
        this.response = response.data;
        this.fetchPrestamos();
      });
    } 
  }
}
</script>