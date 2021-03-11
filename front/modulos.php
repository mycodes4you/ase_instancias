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
          
          <div class="card-body p-0">        
            <!--
            <a class="btn btn-app bg-success" href="#">
              <span class="badge bg-danger">{{num_usr}}</span>
              <i class="fas fa-users"></i> Total
            </a>   
          
            <a class="btn btn-app bg-success" href="inicio.php?accion=usuarios">
              <span class="badge bg-danger">{{num_usr_on}}</span>
              <i class="fas fa-users"></i> Activos
            </a>

            <a class="btn btn-app bg-danger" href="inicio.php?accion=instancias_inactivas">
              <span class="badge bg-warning">{{num_usr_off}}</span>
              <i class="fas fa-users-slash"></i> Inactivos
            </a>
          -->
            <?php if(validaAcceso("400-002", $usuario_id) == TRUE){ /// --- Agregar Modulo?>
              <button type="button" data-toggle="modal" data-target="#modal-nuevo" class="btn bg-gradient-success btn-app" @click="app.showingaddModal = true;"><i class="fas fa-puzzle-piece"></i> Agregar Modulo</button>
            <?php } ?>


          </div>

        </div>
      </div>




      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Listado de Modulos</h3>

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
                      <th>Descripción</th>
                      <th>Número</th>
                      <th>Estado</th>
                      <th style="width: 10px">Editar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="modulo of listado_modulos">
                      <td>{{modulo.modulo_id}}</td>
                      
                      <td>
                        
                     {{modulo.modulo_descripcion}}
                      </td>
                      <td>
                        {{modulo.modulo_numero}}
                      </td>
                      <td>
                        <div v-if="modulo.modulo_estado == 1">
                          Activo
                        </div>
                        
                        <div v-else>
                          Inactivo
                        </div>
                      </td>
                      <td>
                        <?php if(validaAcceso("400-003", $usuario_id) == TRUE){ /// --- Editar Modulo ?>
                          <button type="button" data-toggle="modal" data-target="#modal-modulo" class="btn btn-block bg-gradient-warning btn-xs" @click="showingeditModal = true; selectEstado(modulo);"><i class="fas fa-edit"></i></button>
                        <?php } ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
        </div>
        <!--/.card-body -->
        <div class="card-footer">
          KUMO
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->



      <div class="modal fade" id="modal-modulo">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Editar Modulo {{clickedModulo.modulo_descripcion}}</h4>
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="modulo_descripcion">Descripción</label>
                <input type="text" class="form-control" id="modulo_descripcion" v-model="clickedModulo.modulo_descripcion">
                <input type="hidden" class="form-control" id="modulo_id" v-model="clickedModulo.modulo_id">
              </div>

              <div class="form-group">
                <label for="modulo_numero">Modulo Número</label>
                <input type="text" class="form-control" id="modulo_numero" v-model="clickedModulo.modulo_numero">
              </div>

              <div class="form-group">
                <label for="modulo_estado">Modulo Estado</label>
                <select class="form-control" id="modulo_estado" v-model="clickedModulo.modulo_estado" required>
                  <div v-if="clickedModulo.modulo_estado == 1">
                    <option value="1" selected>Activo</option>
                    <option value="0">Inactivo</option>
                  </div>
                  
                </select>

                <!--<input type="text" class="form-control" id="modulo_estado" v-model="clickedModulo.modulo_estado">-->
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              <button type="button" class="btn btn-success" @click="showingeditModal = false; updateModulo();" data-dismiss="modal"><i class="fas fa-save"></i> Guardar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>




      <div class="modal fade" id="modal-nuevo">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nuevo Modulo</h4>
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!--<img v-if="eurl" :src="eurl" width="200px"><br> -->
              <!--<input class="form-control" type="file" name="usuario_foto_add" ref="usuario_foto_add" id="usuario_foto_add" v-on:change="everImagen();">-->

              {{errorMessage}}
            
              <div class="form-group">
                <label for="modulo_descripcion_add">Descripción</label>
                <input type="text" class="form-control" id="modulo_descripcion_add" v-model="nuevoModulo.modulo_descripcion" required>
              </div>

              <div class="form-group">
                <label for="modulo_numero_add">Modulo Número</label>
                <input type="text" class="form-control" id="modulo_numero_add" v-model="nuevoModulo.modulo_numero" required>
              </div>

              <div class="form-group">
                <label for="modulo_estado_add">Modulo Estado</label>
                <select class="form-control" id="modulo_estado_add" v-model="nuevoModulo.modulo_estado" required>
                  <option>Selecciona...</option>
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
               <!-- <input type="text" class="form-control" id="modulo_estado_add" v-model="nuevoModulo.modulo_estado" required>-->
              </div>

              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
             <button type="button" class="btn btn-success" @click="showingaddModal = false; newModulo();" data-dismiss="modal"><i class="fas fa-save"></i> Guardar</button>
             
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
      listado_modulos: [],
      num_usr_on: "",
      num_usr_off: "",
      num_usr: "",
      showingeditModal: false,
      showingaddModal: false,
      clickedModulo: {},
      url: "",
      eurl: "",
      nuevoModulo: {
        modulo_descripcion_add: "",
        modulo_estado_add: "",
        modulo_numero_add: ""

      }
    },

    mounted: function () {
      console.log("Vue.js esta corriendo...");
      console.log(moment().format('LLLL'));
      this.cargarModulos();
    },

    methods: {
      moment: function () {
      return moment();
      },      

      

      cargarModulos: function () {
        axios.get('<?= $axios_url ?>api/modulos_api.php?accion=listado')
        .then(function (response) {
          console.log(response);

          if (response.data.error) {
            app.errorMessage = response.data.message;
            //console.log(response.data.message);
          } else {
            app.listado_modulos = response.data.listado_modulos;
            app.num_usr_on = response.data.num_usr_on;
            app.num_usr = response.data.num_usr;
            app.num_usr_off = response.data.num_usr_off;
          }
        })
      },
      updateModulo: function () {
        var formData = app.toFormData(app.clickedModulo);
        axios.post('<?= $axios_url ?>api/modulos_api.php?accion=actualizar', formData)
        .then(function (response) {
          console.log(response);
          app.clickedModulo = {};

          if (response.data.error) {
            app.errorMessage = response.data.message;
            //app.notificacionE('top','center');
          } else {
            app.successMessage = response.data.message;
                //app.successMessage2 = response.data.message2;
            app.cargarModulos();
            //app.notificacionS('top','center');
          }
        });
      },
      selectEstado(Modulo) {
        app.clickedModulo = Modulo;


        /*N*/
      },

    toFormData: function (obj) {
        var form_data = new FormData();
        for (var key in obj) {
          form_data.append(key, obj[key]);
        }
        return form_data;
      },

      newModulo: function () {
        let formdata=new FormData();
        formdata.append("modulo_descripcion_add",document.getElementById("modulo_descripcion_add").value);
        formdata.append("modulo_estado_add",document.getElementById("modulo_estado_add").value);
        formdata.append("modulo_numero_add",document.getElementById("modulo_numero_add").value);
        axios.post('<?= $axios_url ?>api/modulos_api.php?accion=agregar', formdata)
        .then(function(response){
          console.log(response);

          if (response.data.error) {
            app.errorMessage = response.data.message;
          } else {
            app.successMessage = response.data.message;
            app.cargarModulos();
          }
        })
      },
      verImagen:function(){
        var _this = this
        _this.file = _this.$refs.usuario_foto.files[0];
        _this.url = URL.createObjectURL(_this.file);
      },
      everImagen:function(){
        var _this = this
        _this.file = _this.$refs.usuario_foto.files[0];
        _this.url = URL.createObjectURL(_this.file);
      },


    }
  });
</script>

<?php
include('parciales/pie.php');
 ?>
