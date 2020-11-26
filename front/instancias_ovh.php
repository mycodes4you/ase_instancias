<?php
include('parciales/cabecera.php');
include('parciales/menu.php');
include('parciales/titulo.php');
?>


    <!-- Main content -->
    <section class="content" id="app" v-cloak>
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
            <a class="btn btn-app bg-primary" href="inicio.php?accion=instancias">
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

            <a class="btn btn-app" href="inicio.php?accion=instancias_ovh" style="background-color: grey; color: white;"> 
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
                      <th>Servidor</th>
                      <th style="width: 40px">Estado</th>
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
      num_ins_jup: ""
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
        axios.get('<?= $axios_url ?>api/instancias_api.php?accion=listado_ovh')
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
      }

    }
  });
</script>

<?php
include('parciales/pie.php');
 ?>
