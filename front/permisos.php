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
            <?php if(validaAcceso("300-002", $usuario_id) == TRUE){ /// --- Agregar PErmisos?>
              <button type="button" data-toggle="modal" data-target="#modal-nuevo" class="btn bg-gradient-success btn-app" @click="app.showingaddModal = true;"><i class="fas fa-key"></i> Agregar Permiso</button>
            <?php } ?>


          </div>

        </div>
      </div>




      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Listado de Permisos</h3>

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
                      <th>Modulo</th>
                      <th>Número</th>
                      <th style="width: 10px">Editar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="permiso of listado_permisos">
                      <td>{{permiso.permiso_id}}</td>
                      
                      <td>
                        
                     {{permiso.permiso_descripcion}}
                      </td>
                      <td>
                        {{permiso.permiso_modulo}}
                      </td>
                      <td>
                        {{permiso.permiso_numero}}
                      </td>
                      <td>
                        <?php if(validaAcceso("300-003", $usuario_id) == TRUE){ /// --- Editar Permisos ?>
                          <button type="button" data-toggle="modal" data-target="#modal-permiso" class="btn btn-block bg-gradient-warning btn-xs" @click="showingeditModal = true; selectPermiso(permiso);"><i class="fas fa-edit"></i></button>
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



      <div class="modal fade" id="modal-permiso">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Editar Permiso {{clickedPermiso.permiso_descripcion}}</h4>
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="permiso_descripcion">Descripción</label>
                <input type="text" class="form-control" id="permiso_descripcion" v-model="clickedPermiso.permiso_descripcion">
                <input type="hidden" class="form-control" id="permiso_id" v-model="clickedPermiso.permiso_id">
              </div>

              <div class="form-group">
                <label for="permiso_modulo">Permiso Modulo</label>
                <input type="text" class="form-control" id="permiso_modulo" v-model="clickedPermiso.permiso_modulo">
              </div>

              <div class="form-group">
                <label for="permiso_numero">Permiso Numero</label>
                <input type="text" class="form-control" id="permiso_numero" v-model="clickedPermiso.permiso_numero">
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              <button type="button" class="btn btn-success" @click="showingeditModal = false; updatePermiso();" data-dismiss="modal"><i class="fas fa-save"></i> Guardar</button>
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
              <h4 class="modal-title">Nuevo Permiso</h4>
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!--<img v-if="eurl" :src="eurl" width="200px"><br> -->
              <!--<input class="form-control" type="file" name="usuario_foto_add" ref="usuario_foto_add" id="usuario_foto_add" v-on:change="everImagen();">-->

              {{errorMessage}}
            
              <div class="form-group">
                <label for="permiso_descripcion_add">Descripción</label>
                <input type="text" class="form-control" id="permiso_descripcion_add" v-model="nuevoPermiso.permiso_descripcion" required>
              </div>

              <div class="form-group">
                <label for="permiso_modulo_add">Permiso Modulo</label>
                <select class="form-control" id="permiso_modulo_add" v-model="nuevoPermiso.permiso_modulo" required>
                  <option selected>Selecciona...</option>
                  <option v-for="modulo in listado_modulos" v-bind:value="modulo.modulo_numero">{{modulo.modulo_descripcion}}</option>
                </select>
                <!--<input type="text" class="form-control" id="permiso_modulo_add" v-model="nuevoPermiso.permiso_modulo" required>-->
              </div>

              <div class="form-group">
                <label for="permiso_numero_add">Permiso Número</label>
                <input type="text" class="form-control" id="permiso_numero_add" v-model="nuevoPermiso.permiso_numero" required>
              </div>

              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
             <button type="button" class="btn btn-success" @click="showingaddModal = false; newPermiso();" data-dismiss="modal"><i class="fas fa-save"></i> Guardar</button>
             
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
      listado_permisos: [],
      listado_modulos: [],
      modulos: "",
      num_usr_on: "",
      num_usr_off: "",
      num_usr: "",
      showingeditModal: false,
      showingaddModal: false,
      clickedPermiso: {},
      url: "",
      eurl: "",
      nuevoPermiso: {
        permiso_descripcion_add: "",
        permiso_numero_add: "",
        permiso_modulo_add: ""

      }
    },

    mounted: function () {
      console.log("Vue.js esta corriendo...");
      console.log(moment().format('LLLL'));
      this.cargarPermisos();
    },

    methods: {
      moment: function () {
      return moment();
      },      

      

      cargarPermisos: function () {
        axios.get('<?= $axios_url ?>api/permisos_api.php?accion=listado')
        .then(function (response) {
          console.log(response);

          if (response.data.error) {
            app.errorMessage = response.data.message;
            //console.log(response.data.message);
          } else {
            app.listado_permisos = response.data.listado_permisos;
            app.listado_modulos = response.data.listado_modulos;
            app.num_usr_on = response.data.num_usr_on;
            app.num_usr = response.data.num_usr;
            app.num_usr_off = response.data.num_usr_off;
          }
        })
      },
      updatePermiso: function () {
        var formData = app.toFormData(app.clickedPermiso);
        axios.post('<?= $axios_url ?>api/permisos_api.php?accion=actualizar', formData)
        .then(function (response) {
          console.log(response);
          app.clickedPermiso = {};

          if (response.data.error) {
            app.errorMessage = response.data.message;
            //app.notificacionE('top','center');
          } else {
            app.successMessage = response.data.message;
                //app.successMessage2 = response.data.message2;
            app.cargarPermisos();
            //app.notificacionS('top','center');
          }
        });
      },
      selectPermiso(Permiso) {
        app.clickedPermiso = Permiso;


        /*N*/
      },

    toFormData: function (obj) {
        var form_data = new FormData();
        for (var key in obj) {
          form_data.append(key, obj[key]);
        }
        return form_data;
      },

      newPermiso: function () {
        let formdata=new FormData();
        formdata.append("permiso_descripcion_add",document.getElementById("permiso_descripcion_add").value);
        formdata.append("permiso_numero_add",document.getElementById("permiso_numero_add").value);
        formdata.append("permiso_modulo_add",document.getElementById("permiso_modulo_add").value);
        axios.post('<?= $axios_url ?>api/permisos_api.php?accion=agregar', formdata)
        .then(function(response){
          console.log(response);

          if (response.data.error) {
            app.errorMessage = response.data.message;
          } else {
            app.successMessage = response.data.message;
            app.cargarPermisos();
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
