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

            <button type="button" data-toggle="modal" data-target="#modal-nuevo" class="btn bg-gradient-info btn-app" @click="app.showingaddModal = true;"><i class="fas fa-user-plus"></i> Agregar Usuario</button>


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
                      <th style="width: 20px;">Foto</th>
                      <th>Nombre</th>
                      <th>Usuario</th>
                      <th style="width: 10px">Editar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="usuario of listado_usuarios">
                      <td>{{usuario.usuario_id}}</td>
                      <td>
                    
                         
                            <img class="attachment-img" style="width: 32px;" :src="usuario.usuario_foto">
                     
                       
                      </td>
                      <td>
                        
                     {{usuario.usuario_nombre}}
                      </td>
                      <td>
                        {{usuario.usuario_usuario}}
                      </td>
                      <td>
                        <button type="button" data-toggle="modal" data-target="#modal-usuario" class="btn btn-block bg-gradient-warning btn-xs" @click="showingeditModal = true; selectUsuario(usuario);"><i class="fas fa-edit"></i></button>
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



      <div class="modal fade" id="modal-usuario">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Editar Usuario {{clickedUsuario.usuario_nombre}}</h4>
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="usuario_nombre1">Primer Nombre</label>
                <input type="text" class="form-control" id="usuario_nombre1" v-model="clickedUsuario.usuario_nombre1">
                <input type="hidden" class="form-control" id="usuario_id" v-model="clickedUsuario.usuario_id">
              </div>

              <div class="form-group">
                <label for="usuario_nombre2">Segundo Nombre</label>
                <input type="text" class="form-control" id="usuario_nombre2" v-model="clickedUsuario.usuario_nombre2">
              </div>

              <div class="form-group">
                <label for="usuario_apellido1">Apellido Paterno</label>
                <input type="text" class="form-control" id="usuario_apellido1" v-model="clickedUsuario.usuario_apellido1">
              </div>

              <div class="form-group">
                <label for="usuario_apellido2">Apellido Materno</label>
                <input type="text" class="form-control" id="usuario_apellido2" v-model="clickedUsuario.usuario_apellido2">
              </div>

              <div class="form-group">
                <label for="usuario_usuario">Usuario</label>
                <input type="text" class="form-control" id="usuario_usuario" v-model="clickedUsuario.usuario_usuario">
              </div>

              <div class="form-group">
                <label for="usuario_psswrd">Contraseña</label>
                <input type="text" class="form-control" id="usuario_psswrd" v-model="clickedUsuario.usuario_psswrd">
              </div>
            



            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              <button type="button" class="btn btn-success" @click="showingeditModal = false; updateUsuario();" data-dismiss="modal"><i class="fas fa-save"></i> Guardar</button>
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
              <h4 class="modal-title">Nuevo Usuario</h4>
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!--<img v-if="eurl" :src="eurl" width="200px"><br> -->
              <!--<input class="form-control" type="file" name="usuario_foto_add" ref="usuario_foto_add" id="usuario_foto_add" v-on:change="everImagen();">-->


              <div class="form-group">
                <label for="usuario_nombre1_add">Primer Nombre</label>
                <input type="text" class="form-control" id="usuario_nombre1_add" v-model="nuevoUsuario.usuario_nombre1">
              </div>

              <div class="form-group">
                <label for="usuario_nombre2_add">Segundo Nombre</label>
                <input type="text" class="form-control" id="usuario_nombre2_add" v-model="nuevoUsuario.usuario_nombre2">
              </div>

              <div class="form-group">
                <label for="usuario_apellido1_add">Apellido Paterno</label>
                <input type="text" class="form-control" id="usuario_apellido1_add" v-model="nuevoUsuario.usuario_apellido1">
              </div>

              <div class="form-group">
                <label for="usuario_apellido2_add">Apellido Materno</label>
                <input type="text" class="form-control" id="usuario_apellido2_add" v-model="nuevoUsuario.usuario_apellido2">
              </div>

              <div class="form-group">
                <label for="usuario_usuario_add">Usuario</label>
                <input type="text" class="form-control" id="usuario_usuario_add" v-model="nuevoUsuario.usuario_usuario">
              </div>

              <div class="form-group">
                <label for="usuario_psswrd_add">Contraseña</label>
                <input type="password" class="form-control" id="usuario_psswrd_add" v-model="nuevoUsuario.usuario_psswrd">
              </div>
            



            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
              <button type="button" class="btn btn-success" @click="showingaddModal = false; newUsuario();" data-dismiss="modal"><i class="fas fa-save"></i> Guardar</button>
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
      listado_usuarios: [],
      num_usr_on: "",
      num_usr_off: "",
      num_usr: "",
      showingeditModal: false,
      showingaddModal: false,
      clickedUsuario: {},
      url: "",
      eurl: "",
      nuevoUsuario: {
        usuario_nombre1_add: "",
        usuario_nombre2_add: "",
        usuario_apellido1_add: "",
        usuario_apellido2_add: "",
        usuario_usuario_add: "",
        usuario_psswrd_add: "",
        usuario_foto_add: ""

      }
    },

    mounted: function () {
      console.log("Vue.js esta corriendo...");
      console.log(moment().format('LLLL'));
      this.cargarUsuarios();
    },

    methods: {
      moment: function () {
      return moment();
      },      
      
      cargarUsuarios: function () {
        axios.get('<?= $axios_url ?>api/usuarios_api.php?accion=listado')
        .then(function (response) {
          console.log(response);

          if (response.data.error) {
            app.errorMessage = response.data.message;
            //console.log(response.data.message);
          } else {
            app.listado_usuarios = response.data.listado_usuarios;
            app.num_usr_on = response.data.num_usr_on;
            app.num_usr = response.data.num_usr;
            app.num_usr_off = response.data.num_usr_off;
          }
        })
      },
      updateUsuario: function () {
        var formData = app.toFormData(app.clickedUsuario);
        axios.post('<?= $axios_url ?>api/usuarios_api.php?accion=actualizar', formData)
        .then(function (response) {
          console.log(response);
          app.clickedUsuario = {};

          if (response.data.error) {
            app.errorMessage = response.data.message;
            //app.notificacionE('top','center');
          } else {
            app.successMessage = response.data.message;
                //app.successMessage2 = response.data.message2;
            app.cargarUsuarios();
            //app.notificacionS('top','center');
          }
        });
      },
      selectUsuario(Usuario) {
        app.clickedUsuario = Usuario;


        /*N*/
      },

    toFormData: function (obj) {
        var form_data = new FormData();
        for (var key in obj) {
          form_data.append(key, obj[key]);
        }
        return form_data;
      },

      newUsuario: function () {
        let formdata=new FormData();
        formdata.append("usuario_nombre1_add",document.getElementById("usuario_nombre1_add").value);
        formdata.append("usuario_nombre2_add",document.getElementById("usuario_nombre2_add").value);
        formdata.append("usuario_apellido1_add",document.getElementById("usuario_apellido1_add").value);
        //formdata.append("usuario_foto_add",document.getElementById("usuario_foto_add").files[0]);
        formdata.append("usuario_apellido2_add",document.getElementById("usuario_apellido2_add").value);
        formdata.append("usuario_usuario_add",document.getElementById("usuario_usuario_add").value);
        formdata.append("usuario_psswrd_add",document.getElementById("usuario_psswrd_add").value);

        axios.post('<?= $axios_url ?>api/usuarios_api.php?accion=agregar', formdata)
        .then(function(response){
          console.log(response);

          if (response.data.error) {
            app.errorMessage = response.data.message;
          } else {
            app.successMessage = response.data.message;
            app.cargarUsuarios();
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
