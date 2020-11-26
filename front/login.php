<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KUMO | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
</head>
<body class="hold-transition login-page">
<div class="login-box" id="app">
  <div class="login-logo">
    <a href="index.php"><b>KUMO</b> / 雲 </a>
    
          <div v-if="mostrarRespuesta"> <!-- Ayuda -->
            <p>res: {{ respuesta }}</p>
          </div>
         
      
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Ingresa tus datos para iniciar sesión</p>
      <form id="inicioSesion" autosomplete="off" @submit.prevent="login" class="form">
      <div id="printMe"></div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" v-model="email" placeholder="Usuario" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" v-model="pass" placeholder="Password" name="pass">
          <input type="hidden" class="form-control" v-model="mensajetxt" name="mensajetxt">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Recordarme
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" @click="login();">Entrar</button>
          </div>
          <!-- /.col -->
        </div>

<!--
      <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>-->
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="forgot-password.html">Perdi mi contraseña</a>
      </p>
      <p class="mb-0">
        <!--<a href="register.html" class="text-center">Register a new membership</a>-->
      </p>
    </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script id="login">
    
    Vue.config.devtools = false
    Vue.config.debug = false
    Vue.config.silent = true

    // --- objeto Vue.js  #app ---  
    const app = new Vue({

      el: '#app',
      data: {
        respuesta: '',
        mostrarRespuesta: false,
      },

      methods: {
        login(){
            
          const form = document.getElementById('inicioSesion')
          axios.post('login.php?accion=validar', new FormData(form))
          .then( res =>{
            this.respuesta = res.data
            if(res.data == 'Entrando...'){
              location.href = 'index.php'
            } else{
              this.mostrarRespuesta = true
              if(res.data == 'Se produjo un error al iniciar sesión, verifica tus credenciales e intenta de nuevo.'){
                Swal.fire({
                  type: 'error',
                  title: 'Error',
                  text: 'Correo y/o contraseña incorrectos!',
                })
              } else {
                Swal.fire({
                  type: 'error',
                  title: 'Error',
                  text: res.data,
                })
              }
            }
          })
            
        }
      }
    })

  </script>
</body>
</html>
