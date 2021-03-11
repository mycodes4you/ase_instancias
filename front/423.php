<?php
include('parciales/cabecera.php');
include('parciales/menu.php');
include('parciales/titulo.php');
?>


    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-danger">423</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Algo esta mal!.</h3>

          <p>
          <?php

          //print_r($permisos_l);
          echo '<br>';
    
        
/*
          if($validaAcceso('100-001') == 1){
            echo 'si';
          }
          else{
            echo 'no';
          }*/

          $funnum = '100-001';
          $retorno = validaAcceso($funnum);
          echo $retorno;
         /* if($retorno == 1){
            echo 'si';
          }
          else{
            echo 'no';
          }*/

         
          ?>
<br>
          NO TIENES PERMISOS PARA VISUALIZAR ESTA PAGINA
          </p>

          <form class="search-form">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search">

              <div class="input-group-append">
                <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            <!-- /.input-group -->
          </form>
        </div>
      </div>
      <!-- /.error-page -->

    </section>
    <!-- /.content -->


  <?php
include('parciales/pie.php');
 ?>
