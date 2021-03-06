  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand <?= $_SESSION['config_navbar'] ?>">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="inicio.php?accion=contacto" class="nav-link">Contacto</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start 
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            Message End
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
             Message Start 
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            Message End 
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            Message Start 
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
           Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">En desarrollo...</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!--<span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>-->
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">En desarrollo...</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar <?= $_SESSION['config_sidebar'] ?> elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link <?= $_SESSION['config_brand'] ?>">
      <img src="dist/img/AdminLTELogo.png" alt="KUMO" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ASE | KUMO</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?= $_SESSION['usuario_foto'] ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="inicio.php?accion=cuenta" class="d-block"><?= $_SESSION['usuario_nombre_corto'] ?></a>
        </div>
      </div>

      

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="index.php" class="nav-link <?= $menu_dashboard ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            
          </li>
          <li class="nav-item <?= $menu_instancias_abierto ?>">
         <!-- <li class="nav-item">-->
            <a href="inicio.php?accion=instancias" class="nav-link <?= $menu_instancias ?>">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Instancias
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="inicio.php?accion=instancias" class="nav-link <?= $menu_instancias ?>">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Listado</p>
                </a>
              </li>
            </ul>
          </li>




          <li class="nav-item <?= $menu_usuarios_abierto ?>">
            <a href="inicio.php?accion=usuarios" class="nav-link <?= $menu_usuarios ?>">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Usuarios
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="inicio.php?accion=usuarios" class="nav-link <?= $menu_usuarios ?>">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Listado</p>
                </a>
              </li>
             
            
            </ul>
            
          </li>


          <li class="nav-item <?= $menu_modulos_abierto ?>">
            <a href="inicio.php?accion=modulos" class="nav-link <?= $menu_modulos ?>">
              <i class="nav-icon fas fa-puzzle-piece"></i>
              <p>
                Módulos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="inicio.php?accion=modulos" class="nav-link <?= $menu_modulos ?>">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Listado</p>
                </a>
              </li>
            
            </ul>
            
          </li>

          <li class="nav-item <?= $menu_permisos_abierto ?>">
            <a href="inicio.php?accion=permisos" class="nav-link <?= $menu_permisos ?>">
              <i class="nav-icon fas fa-lock"></i>
              <p>
                Permisos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="inicio.php?accion=permisos" class="nav-link <?= $menu_permisos ?>">
                  <i class="fas fa-key nav-icon"></i>
                  <p>Listado</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="inicio.php?accion=permisos_dar" class="nav-link <?= $menu_permisos ?>">
                  <i class="fas fa-unlock-alt nav-icon"></i>
                  <p>Dar Permisos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="inicio.php?accion=permisos_ver" class="nav-link <?= $menu_permisos ?>">
                  <i class="fas fa-user-lock nav-icon"></i>
                  <p>Ver Asignados</p>
                </a>
              </li>

              
            
            </ul>
            
          </li>


          <li class="nav-item">
            <a href="login.php?accion=salir" class="nav-link">
              <i class="nav-icon fas fa-door-open"></i>
              <p>
                Salir
                
              </p>
            </a>
            
          </li>

          <!--<li class="nav-header">SEPARACION</li>-->
          
          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>