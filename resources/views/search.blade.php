<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Buscador de deuda</title>
    
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background: #eee;
            font: 12px Lucida sans, Arial, Helvetica, sans-serif;
            color: #333;
            text-align: center;
        }
    </style>
	<script>
		window.Laravel = {!! json_encode([
			'csrfToken' => csrf_token(),
		]) !!};
	</script>
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div class="page-header" style="padding:3%;">
        <h2> Buscador de deuda de materiales</h2>
    </div>
    <div class="container" id="app">
        <div class="card">
            <div class="card-header">
            <strong>Ingresa tu rut para consultar si tienes deuda</strong>
            </div>
            <div class="card-block">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="nf-email">Rut</label>
                        <div class="container">
                            <v-select :options="personas" label="rut" v-model="buscar" placeholder="ingresa el rut con puntos y guión" @selected="search"></v-select>
                        </div>
                    </div>
                    <div>
                    <button type="button" class="btn btn-primary " @click="search">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-block" v-if="show">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Descripción</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(material, index) in deudas">
                            <td>@{{material.nombre}}</td>
                            <td>@{{material.cantidad}}</td>
                            <td>@{{material.descripcion}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-block" v-if="information">
                <h3>No presenta deuda de materiales</h3>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<script>
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: '#app',
        data() {
            return{
                message: "vengo de Vue",
                personas: [],
                buscar: null,
                deudas: [],
                show: false,
                information: false,
            }
        },
        computed(){
            this.show = false;
            this.information = false;
        },
        created(){
            this.fetchAlumnos();
        },
        methods:{
            fetchAlumnos: function(){
                axios.get('/personas').then((response) =>{
                    this.personas = response.data;
                });
            },
            search: function(){
                this.show = false;
                this.information = false;
                if(this.buscar != null){
                    axios.get('prestados/'+this.buscar.id).then((response) =>{
                        this.deudas = response.data;
                        if(this.deudas.length > 0){
                            this.show = true;
                        }
                        else{
                            this.information = true;
                        }
                    });
                }
            }
        },
        
    })
</script>
</html>