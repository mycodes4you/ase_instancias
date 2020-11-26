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
        {errorMessage}}
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
          
          <div class="card-body p-0">
            <a class="btn btn-app" href="inicio.php?accion=instancias" style="background-color: grey; color: white;">
              <span class="badge bg-danger">{{num_ins}}</span>
              <i class="fas fa-building"></i> Total
            </a>
          
            <a class="btn btn-app bg-success" href="inicio.php?accion=instancias_activas">
              <span class="badge bg-danger">{{num_ins_on}}</span>
              <i class="fas fa-toggle-on"></i> Activas
            </a>

            <a class="btn btn-app bg-danger" href="inicio.php?accion=instancias_inactivas">
              <span class="badge bg-warning">{{num_ins_off}}</span>
              <i class="fas fa-toggle-off"></i> Inactivas
            </a>

            <a class="btn btn-app" href="inicio.php?accion=instancias_codero" style="background-color: #00f4ff; color: grey;">
              <span class="badge bg-danger">{{num_ins_codero}}</span>
              <i class="fas fa-server"></i> Codero
            </a>

            <a class="btn btn-app bg-warning" href="inicio.php?accion=instancias_ovh">
              <span class="badge bg-danger">{{num_ins_ovh}}</span>
              <i class="fas fa-server"></i> OVH
            </a>

            <a class="btn btn-app" href="inicio.php?accion=instancias_jupiter" style="background-color: #6610f2; color: white;">
              <span class="badge bg-danger">{{num_ins_jup}}</span>
              <i class="fas fa-server"></i> Jupiter
            </a>
          </div>

        </div>
      </div>




      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Listado de Instancias</h3>

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
                        <div v-if="instancia.instancia_servidor == '0'">
                          <span class="badge" style="background-color: grey; color: white;">
                            Apagado
                          </span>
                        </div>
                        <div v-else-if="instancia.instancia_servidor == '2'">
                          <span class="badge bg-warning">
                            <a href="https://carshopmgr.com/controldb/" target="_blank" style="color: black;">OVH</a>
                          </span>
                        </div>
                        <div v-else-if="instancia.instancia_servidor == '1'">
                          <span class="badge" style="background-color: #00f4ff; color: grey;">
                            <a href="https://autoshopmgr.com/controldb/" target="_blank" style="color: grey;">Codero</a>
                          </span>
                        </div>
                        <div v-else-if="instancia.instancia_servidor == '3'">
                          <span class="badge" style="background-color: #6610f2; color: white;">
                            <a href="https://jup2.carshopmgr.com/controldb/" target="_blank" style="color: white;">Jupiter</a>
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
              <div class="form-group" v-if="clickedInstancia.instancia_servidor == '0'">
                <label>Servidor</label>
                <select class="form-control" id="instancia_servidor" v-model="clickedInstancia.instancia_servidor">
                  <option selected value="0">Apagado</option>
                  <option value="2">OVH</option>
                  <option value="1">Codero</option>
                  <option value="3">Jupiter</option>
                </select>
              </div>
              <div class="form-group" v-else-if="clickedInstancia.instancia_servidor =='2'">
                <label>Servidor</label>
                <select class="form-control" id="instancia_servidor" v-model="clickedInstancia.instancia_servidor">
                  <option selected value="2">OVH</option>
                  <option value="0">Apagado</option>
                  <option value="1">Codero</option>
                  <option value="3">Jupiter</option>
                </select>
              </div>
              <div class="form-group" v-else-if="clickedInstancia.instancia_servidor =='1'">
                <label>Servidor</label>
                <select class="form-control" id="instancia_servidor" v-model="clickedInstancia.instancia_servidor">
                  <option selected value="1">Codero</option>
                  <option value="0">Apagado</option>
                  <option value="2">OVH</option>
                  <option>Jupiter</option>
                </select>
              </div>
              <div class="form-group" v-else-if="clickedInstancia.instancia_servidor =='3'">
                <label>Servidor</label>
                <select class="form-control" id="instancia_servidor" v-model="clickedInstancia.instancia_servidor">
                  <option selected value="3">Jupiter</option>
                  <option value="0">Apagado</option>
                  <option value="2">OVH</option>
                  <option value="1">Codero</option>
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
      errorMessage: "",
      successMessage: "",
      listado_instancias: [],
      num_ins_on: "",
      num_ins_off: "",
      num_ins: "",
      num_ins_ovh: "",
      num_ins_codero: "",
      num_ins_jup: "",
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
      this.cargarInstancias();
    },

    methods: {
      moment: function () {
      return moment();
      },      
      
      cargarInstancias: function () {
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
      },
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
