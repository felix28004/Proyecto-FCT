<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="0I-inicio.css">

</head>
<body>
  
    
<div class="menu">
    <h2>Menú</h2>
    <ul>
      <li><a href="../0inicio.php">Inicio</a></li>
      <li><a href="../manuales/0M-inicio.php">Manuales</a></li>
      <li><a class="active" href="#"><b>Incidencias</b></a></li>
      <li><a href="../material/0Mat-inicio.php">Material asignado</a></li>
      <li><a href="../usuarios/usuarios.php">Altas y Bajas</a></li>

    </ul>
  </div>

 
  <div class="contenido">

    <div class="titulo"> 
    <h1>Incidencias</h1>
    </div>

  </div>

<div class="intro"> 
  <p>En este apartado estan todas las incidencias mas comunues que suelen tener los usuarios, con 
    la explicación de como solucionarlos.
  </p>
  <h3>Usuarios</h3>
  <p>En la sección de los usuarios estaran varios manuales explicativos para poder compartarselo al
    usuario y el usuario intente seguir las instrucciones para resolver por si mismo la incidencia.
  </p>
  <h3>Técnicos</h3>
  <p>En la sección de técnicos se encotraran las incidencias que solo los tecnicos pueden solucionar</p>
</div>

<!-- Botones para los filtros -->
<div class="filtro-manuales">
  <button class="boton active" onclick="filtrarManuales('todos', this)">Todos</button>
  <button class="boton" onclick="filtrarManuales('usuarios', this)">Usuarios</button>
  <button class="boton" onclick="filtrarManuales('tecnicos', this)">Técnicos </button>
</div>


<div class="contenedor">

<div class="manual" data-categoria="usuarios">
  <a href="PDF/Audio no funciona en el meet.pdf" target="_blank">
    <img src="img/1.png">
    <p>Problemas con el audio en el meet</p>
</a>
</div>

<div class="manual" data-categoria="usuarios">
  <a href="PDF/Cambiar la contraseña.pdf" target="_blank">
    <img src="img/2.png">
    <p>Cambio de contraseña</p>
</a>
</div>

<div class="manual" data-categoria="tecnicos">
  <a href="PDF/TActivar Licencia GOOGLE WORKSPACE.pdf" target="_blank">
    <img src="img/t1.png">
    <p>Activar licencias Google WORKSPACE</p>
</a>
</div>

<div class="manual" data-categoria="tecnicos">
  <a href="PDF/TCuenta deshabilitada GOOGLE WORKSPACE.pdf" target="_blank">
    <img src="img/t2.png">
    <p>Habilitar cuenta Google WORKSPACE</p>
</a>
</div>


</div>


<script src="../botones.js"></script>

    <style>
  
  </style>
 

</body>
</html>