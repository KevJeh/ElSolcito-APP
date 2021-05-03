<?php
    
include '../conexion_db.php';

if(!empty($_POST)){
   if(empty($_POST['nombre']) || empty($_POST['mail']) ||  empty($_POST['asunto']) || empty($_POST['texto'])) {
        echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';
      }
   else {
            $nombre = $_POST['nombre'];
            $mail = $_POST['mail'];
            $asunto = $_POST['asunto'];
            $texto = $_POST['texto'];
            
            $query = mysqli_query($conexion, "INSERT INTO mensajes (nombre, mail, asunto, texto) VALUES ('$nombre', '$mail', '$asunto', ' $texto')");


            if ($query) {
               echo '<script language="javascript">alert("Mensaje enviado correctamente");</script>';
            }
            else {
               echo '<script language="javascript">alert("Error al enviar el mensaje, vuelva a intentarlo nuevamente mas tarde");</script>';
                }
            }
            mysqli_close($conexion);   
}
        
?>


<!doctype html>
<html lang="es" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link  href="../css/estilocontacto.css" rel="stylesheet">
    <title>Contacto</title>

  <?php include '../sistema/includes/scripts.php'?></head>
  
  <body class="body">
      
      <section class = container>
      
      
          <form action="" method="POST">
            <div class = "container">
      <div class ="row justify-content-center h-100">
        <div class ="col-6">
          <div class ="form-box p-5 shadow-sm">
            <h1 class="text-center mb-5">Contacto</h1>
             <form class="pb-4">
              <form action="" method="POST">
                 
                      <div class="form-group">
                         <input type="text" style= "border-radius: 20px" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre">
                         <div class="right-addon">
                         </div>
                      </div>

                       <div class="form-group">
                           <input type="email" style= "border-radius: 20px" class="form-control" id="mail" name="mail"  placeholder="Ingrese su email">
                           <div class="right-addon">
                           
                           </div>
                       </div>

                      <div class="form-group">
                         <input type="text" style= "border-radius: 20px" class="form-control" id="asunto" name="asunto"  placeholder="Asunto">
                         <div class="right-addon">
                         </div>
                       </div>


                      <div class="form-group">
                      <div class="input-group">
                      <textarea class="form-control"  name="texto" style= "border-radius: 20px" aria-label="With textarea" placeholder="Ingrese su mensaje (Máximo 200 caracteres)"></textarea>
                       </div>

                         <div class="right-addon">
                         </div>
                      </div>
                  
                     <button type="submit" class="button btn-block">Enviar</button>
                     <a href="index.html" button type="link" class="button btn-primary btn-block">Volver</button>
                    </a>
                  </form>
              
              <div class="container-fluid">
         <div class="row">
              <div class="col" style="margin-top: 30px;">
                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
                 <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98l4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
                 </svg>
                 <i class="bi bi-map"></i>
                 <h5>Dirección</h5>
                 <h7> Av. Corrientes N°765</h7>
              </div>

              <div class="col" style="margin-top: 30px;">
                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                 <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
                 </svg>
                 <i class="bi bi-envelope"></i>
                 <h5>Email</h5>
                 <h7> elsolcitomusic@gmail.com</h7>
              </div>
              
              <div class="col" style="margin-top: 30px;">
                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                 <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                 </svg>
                 <i class="bi bi-telephone"></i>
                 <h5>Teléfono</h5>
                 <h7> +54 3764 470543</h7>
              </div>
              
              <div class="col" style="margin-top: 30px;">
                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                 <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                 <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                 <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
                 </svg>
                 <i class="bi bi-clock-history"></i>
                 <h5>Horarios</h5>
                 <h7> Lunes a sabado:8:00hs a 18:00hs</h7>
              </div>
              </div>
           </div>
          </div>

              </div>
            </div>
            </div>
            </div>
            </div>

  
      </main>
      
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
     
  </body>
</html>
