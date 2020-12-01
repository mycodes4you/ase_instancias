<?php
include('parciales/cabecera.php');
include('parciales/menu.php');
include('parciales/titulo.php');
?>


    <!-- Main content -->
    <section class="content" id="app" v-cloak>

      <div class="alert alert-success alert-dismissible" v-if="successMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Exito!</h5>
        {{successMessage}}
      </div>

      <div class="alert alert-danger alert-dismissible" v-if="errorMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        {{errorMessage}}
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Acciones</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- 
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>-->
            
          </div>
        </div>
        <div class="card-body">
          
          <div class="card-body p-0" style="text-align: center;">            
              <button class="btn btn-app bg-info" @click="cargarInstancias();">
                <i class="fas fa-building"></i>
                <big><b>{{num_ins}}</b></big> Total
              </button>
            
              <button class="btn btn-app bg-success" @click="ins_activas();">
                <i class="fas fa-toggle-on"></i>
                <big><b>{{num_ins_on}}</b></big> Activas
              </button>

              <button class="btn btn-app bg-danger" @click="ins_inactivas();">
                <i class="fas fa-toggle-off"></i>
                <big><b>{{num_ins_off}}</b></big> Inactivas
              </button>

              <button class="btn btn-app" style="background-color: #00f4ff; color: grey;" @click="ins_codero();">
                <i class="fas fa-server"></i>
                <big><b>{{num_ins_codero}}</b></big> Codero
              </button>

              <button class="btn btn-app bg-warning" @click="ins_ovh();">
                <i class="fas fa-server"></i>
                <big><b>{{num_ins_ovh}}</b></big> OVH
              </button>

              <button class="btn btn-app" style="background-color: #6610f2; color: white;" @click="ins_jupiter();">
                <i class="fas fa-server"></i>
                <big><b>{{num_ins_jup}}</b></big> Jupiter
              </button><br>
              
         
            <div class="form-group">
                <label for="instancia_nombre">Buscar Instancia</label>          
                <input type="text" class="form-control" placeholder="Buscar Instancia" v-on:keyup="searchMonitor" v-model="search.keyword">
              </div>
          </div>

        </div>
      </div>

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Listado de Instancias {{titulo}}</h3>
          <div class="card-tools">
           <button class="btn btn-success"><i class="fas fa-plus"></i> Instancia</button>
            
          </div>
          
        </div>
        <div class="card-body">
          <div class="card-body p-0">
                <table class="table table-bordered table-hover" id="example2">
                  <thead>
                    <tr>
                      <th style="width: 10px">ID</th>
                      <th>Logo</th>
                      <th>Nombre</th>
                      <th>Renovacion SSL</th>
                      <th>Servidor</th>
                      <th style="width: 40px">Estado</th>
                      <th style="width: 10px">Editar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="noMember">
                            <td colspan="2" align="center">Ningún miembro coincide con su búsqueda</td>
                        </tr>
                    <tr v-for="instancia of listado_instancias">
                      <td>{{instancia.instancia_id}}</td>
                      <td>
                        <div v-if="instancia.instancia_estado == 1">
                          <a :href="instancia.instancia_url" target="_blank">
                            <img class="attachment-img" style="width: 50px;" :src="instancia.instancia_img">
                          </a>
                        </div>
                        <div v-else>
                          <img class="attachment-img" style="width: 50px; filter: grayscale(1);" src="https://entrenamiento.autoshop-easy.com/particular/logo-agencia.png">
                        </div>
                      </td>
                      <td>
                        <div v-if="instancia.instancia_estado == 1">
                          <a :href="instancia.instancia_url" target="_blank">{{instancia.instancia_nombre}}</a>
                        </div>
                        <div v-else>{{instancia.instancia_nombre}}</div>
                      </td>
                      <td>
                        {{instancia.instancia_ssl}}
                      </td>
                      <td>
                        <div v-if="instancia.instancia_servidor == 'Apagado'">
                          <span class="badge" style="background-color: grey; color: white;">
                            {{instancia.instancia_servidor}}
                          </span>
                        </div>
                        <div v-else-if="instancia.instancia_servidor == 'OVH'">
                          <span class="badge bg-warning">
                            <a href="https://carshopmgr.com/controldb/" target="_blank" style="color: black;">{{instancia.instancia_servidor}}</a>
                          </span>
                        </div>
                        <div v-else-if="instancia.instancia_servidor == 'Codero'">
                          <span class="badge" style="background-color: #00f4ff; color: grey;">
                            <a href="https://autoshopmgr.com/controldb/" target="_blank" style="color: grey;">{{instancia.instancia_servidor}}</a>
                          </span>
                        </div>
                        <div v-else-if="instancia.instancia_servidor == 'Jupiter'">
                          <span class="badge" style="background-color: #6610f2; color: white;">
                            <a href="https://jup2.carshopmgr.com/controldb/" target="_blank" style="color: white;">{{instancia.instancia_servidor}}</a>
                          </span>
                        </div>
                      </td>
                      <td>
                        <div v-if="instancia.instancia_estado == '1'">
                          <span class="badge bg-success">
                            ON
                          </span>
                        </div>
                        <div v-else>
                          <span class="badge bg-danger">
                            OFF
                          </span>
                        </div>
                      </td>
                      <td>
                        <button type="button" data-toggle="modal" data-target="#modal-default" class="btn btn-block bg-gradient-warning btn-xs" @click="showingeditModal = true; selectInstancia(instancia);"><i class="fas fa-edit"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
        </div>
        <!--/.card-body -->
        <div class="card-footer">
          Autoshop-Easy
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->



      <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Editar Instancia {{clickedInstancia.instancia_nombre}}</h4>
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="instancia_nombre">Nombre Instancia</label>
                <input type="text" class="form-control" id="instancia_nombre" v-model="clickedInstancia.instancia_nombre">
                <input type="hidden" class="form-control" id="instancia_id" v-model="clickedInstancia.instancia_id">
              </div>
              <!-- select dependiendo el actual -->
              <div class="form-group" v-if="clickedInstancia.instancia_servidor == 'Apagado'">
                <label>Servidor</label>
                <select class="form-control" id="instancia_servidor" v-model="clickedInstancia.instancia_servidor">
                  <option selected value="Apagado">Apagado</option>
                  <option value="OVH">OVH</option>
                  <option value="Codero">Codero</option>
                  <option value="Jupiter">Jupiter</option>
                </select>
              </div>
              <div class="form-group" v-else-if="clickedInstancia.instancia_servidor =='OVH'">
                <label>Servidor</label>
                <select class="form-control" id="instancia_servidor" v-model="clickedInstancia.instancia_servidor">
                  <option selected value="OVH">OVH</option>
                  <option value="Apagado">Apagado</option>
                  <option value="Codero">Codero</option>
                  <option value="Jupiter">Jupiter</option>
                </select>
              </div>
              <div class="form-group" v-else-if="clickedInstancia.instancia_servidor =='Codero'">
                <label>Servidor</label>
                <select class="form-control" id="instancia_servidor" v-model="clickedInstancia.instancia_servidor">
                  <option selected value="Codero">Codero</option>
                  <option value="Apagado">Apagado</option>
                  <option value="OVH">OVH</option>
                  <option value="Jupiter">Jupiter</option>
                </select>
              </div>
              <div class="form-group" v-else-if="clickedInstancia.instancia_servidor =='Jupiter'">
                <label>Servidor</label>
                <select class="form-control" id="instancia_servidor" v-model="clickedInstancia.instancia_servidor">
                  <option selected value="Jupiter">Jupiter</option>
                  <option value="Apagado">Apagado</option>
                  <option value="OVH">OVH</option>
                  <option value="Codero">Codero</option>
                </select>
              </div>
              <!-- FIN select dependiendo el actual -->

              <div class="form-group">
                  <label>Fecha Renovación SSL:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="2020-12-25" id="instancia_ssl" v-model="clickedInstancia.instancia_ssl">
                  </div>
                  <!-- /.input group -->
                </div>



            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              <button type="button" class="btn btn-success" @click="showingeditModal = false; updateInstancia();" data-dismiss="modal"><i class="fas fa-save"></i> Guardar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>





    </section>
    <!-- /.content -->





  </div>
  <!-- /.content-wrapper -->


<script type="text/javascript">
  Vue.use('vue-moment');
  var now = moment();
  moment.locale('es');
  var app = new Vue({ 
    el: "#app",
    data: {
      date: "",
      search: {keyword: ''},
      noMember: false,
      errorMessage: "",
      successMessage: "",
      listado_instancias: [],
      num_ins_on: "",
      num_ins_off: "",
      num_ins: "",
      num_ins_ovh: "",
      num_ins_codero: "",
      num_ins_jup: "",
      titulo: "",
      showingeditModal: false,
      clickedInstancia: {},
      servidores: [{
        value: '0',
        text: 'Apagado'
        },
        {
          value: '1',
          text: 'Codero'
        },
        {
          value: '2',
          text: 'OVH'
        },
        {
          value: '3',
          text: 'Jupiter'
        }
      ],
      seleccionado: ''
    },

    mounted: function () {
      console.log("Vue.js esta corriendo...");
      console.log(moment().format('LLLL'));
      //this.cargarInstancias();
      this.cargarInstancias();
    },

    methods: {
      moment: function () {
      return moment();
      },      

      ins_activas: function(){
            axios.post('<?= $axios_url ?>api/instancias_api.php?accion=activas')
                .then(function(response){
                  console.log(response);
                    if (response.data.error) {
            app.errorMessage = response.data.message;
            console.log(response.data.message);
          } else {
            app.listado_instancias = response.data.listado_instancias;
            app.num_ins_on = response.data.num_ins_on;
            app.num_ins = response.data.num_ins;
            app.num_ins_off = response.data.num_ins_off;
            app.num_ins_ovh = response.data.num_ins_ovh;
            app.num_ins_codero = response.data.num_ins_codero;
            app.num_ins_jup = response.data.num_ins_jup;
            app.titulo = response.data.titulo;
            console.log(response.data.listado_instancias);
          }
                });
        },
        ins_codero: function(){
            axios.post('<?= $axios_url ?>api/instancias_api.php?accion=codero')
                .then(function(response){
                  console.log(response);
                    if (response.data.error) {
            app.errorMessage = response.data.message;
            console.log(response.data.message);
          } else {
            app.listado_instancias = response.data.listado_instancias;
            app.num_ins_on = response.data.num_ins_on;
            app.num_ins = response.data.num_ins;
            app.num_ins_off = response.data.num_ins_off;
            app.num_ins_ovh = response.data.num_ins_ovh;
            app.num_ins_codero = response.data.num_ins_codero;
            app.num_ins_jup = response.data.num_ins_jup;
            app.titulo = response.data.titulo;
            console.log(response.data.listado_instancias);
          }
                });
        },
        ins_ovh: function(){
            axios.post('<?= $axios_url ?>api/instancias_api.php?accion=ovh')
                .then(function(response){
                  console.log(response);
                    if (response.data.error) {
            app.errorMessage = response.data.message;
            console.log(response.data.message);
          } else {
            app.listado_instancias = response.data.listado_instancias;
            app.num_ins_on = response.data.num_ins_on;
            app.num_ins = response.data.num_ins;
            app.num_ins_off = response.data.num_ins_off;
            app.num_ins_ovh = response.data.num_ins_ovh;
            app.num_ins_codero = response.data.num_ins_codero;
            app.num_ins_jup = response.data.num_ins_jup;
            app.titulo = response.data.titulo;
            console.log(response.data.listado_instancias);
          }
                });
        },
        ins_jupiter: function(){
            axios.post('<?= $axios_url ?>api/instancias_api.php?accion=jupiter')
                .then(function(response){
                  console.log(response);
                    if (response.data.error) {
            app.errorMessage = response.data.message;
            console.log(response.data.message);
          } else {
            app.listado_instancias = response.data.listado_instancias;
            app.num_ins_on = response.data.num_ins_on;
            app.num_ins = response.data.num_ins;
            app.num_ins_off = response.data.num_ins_off;
            app.num_ins_ovh = response.data.num_ins_ovh;
            app.num_ins_codero = response.data.num_ins_codero;
            app.num_ins_jup = response.data.num_ins_jup;
            app.titulo = response.data.titulo;
            console.log(response.data.listado_instancias);
          }
                });
        },
        ins_inactivas: function(){
            axios.post('<?= $axios_url ?>api/instancias_api.php?accion=inactivas')
                .then(function(response){
                  console.log(response);
                    if (response.data.error) {
            app.errorMessage = response.data.message;
            console.log(response.data.message);
          } else {
            app.listado_instancias = response.data.listado_instancias;
            app.num_ins_on = response.data.num_ins_on;
            app.num_ins = response.data.num_ins;
            app.num_ins_off = response.data.num_ins_off;
            app.num_ins_ovh = response.data.num_ins_ovh;
            app.num_ins_codero = response.data.num_ins_codero;
            app.num_ins_jup = response.data.num_ins_jup;
            app.titulo = response.data.titulo;
            console.log(response.data.listado_instancias);
          }
                });
        },
        searchMonitor: function() {
            var keyword = app.toFormData(app.search);
            axios.post('<?= $axios_url ?>api/instancias_api.php?accion=buscar', keyword)
                .then(function(response){
                    app.listado_instancias = response.data.listado_instancias;
                    console.log(response);
                    if(response.data.listado_instancias == ''){
                        app.noMember = true;
                    }
                    else{
                        app.noMember = false;
                    }
                });
        },
  
        cargarInstancias: function(){
            axios.post('<?= $axios_url ?>api/instancias_api.php?accion=mostrar')
                .then(function(response){
                  console.log(response);
                    if (response.data.error) {
            app.errorMessage = response.data.message;
            console.log(response.data.message);
          } else {
            app.listado_instancias = response.data.listado_instancias;
            app.num_ins_on = response.data.num_ins_on;
            app.num_ins = response.data.num_ins;
            app.num_ins_off = response.data.num_ins_off;
            app.num_ins_ovh = response.data.num_ins_ovh;
            app.num_ins_codero = response.data.num_ins_codero;
            app.num_ins_jup = response.data.num_ins_jup;
            app.titulo = response.data.titulo;
            console.log(response.data.listado_instancias);
          }
                });
        },

      
      /*cargarInstancias: function () {
        axios.get('<?= $axios_url ?>api/instancias_api.php?accion=listado')
        .then(function (response) {
          console.log(response);

          if (response.data.error) {
            app.errorMessage = response.data.message;
            //console.log(response.data.message);
          } else {
            app.listado_instancias = response.data.listado_instancias;
            app.num_ins_on = response.data.num_ins_on;
            app.num_ins = response.data.num_ins;
            app.num_ins_off = response.data.num_ins_off;
            app.num_ins_ovh = response.data.num_ins_ovh;
            app.num_ins_codero = response.data.num_ins_codero;
            app.num_ins_jup = response.data.num_ins_jup;
            //console.log(response.data.listado_instancias);
          }
        })
      },*/
      updateInstancia: function () {
        var formData = app.toFormData(app.clickedInstancia);
        axios.post('<?= $axios_url ?>api/instancias_api.php?accion=actualizar', formData)
        .then(function (response) {
          console.log(response);
          app.clickedInstancia = {};

          if (response.data.error) {
            app.errorMessage = response.data.message;
            //app.notificacionE('top','center');
          } else {
            app.successMessage = response.data.message;
                //app.successMessage2 = response.data.message2;
            app.cargarInstancias();
            //app.notificacionS('top','center');
          }
        });
      },
      selectInstancia(Instancia) {
        app.clickedInstancia = Instancia;


        /*N*/
      },

    toFormData: function (obj) {
        var form_data = new FormData();
        for (var key in obj) {
          form_data.append(key, obj[key]);
        }
        return form_data;
      },

    }
  });
</script>

<?php
include('parciales/pie.php');
 ?>
