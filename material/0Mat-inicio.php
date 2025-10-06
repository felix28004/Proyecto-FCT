<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Material asignado</title>
    <link rel="stylesheet" href="0Mat-inicio.css">
   
    <script>
        function mostrarFormulario() {
            const form = document.getElementById('formulario');
            form.style.display = (form.style.display === 'none') ? 'block' : 'none';
        }
    </script>
</head>
<bod>
  

<div class="menu">
    <h2>Menú</h2>
    <ul>
      <li><a href="../0inicio.php">Inicio</a></li>
      <li><a href="../manuales/0M-inicio.php">Manuales</a></li>
      <li><a href="../inci/0I-inicio.php">Incidencias</a></li>
      <li><a class="active" href="#"><b>Material asignado</b></a></li>
      <li><a href="../usuarios/usuarios.php">Altas y Bajas</a></li>
    </ul>
</div>

<div class="titulo"> 
    <h1>Material asignado</h1>
</div>

<div class="intro">
  <p>Esto es una base de datos sobre los materiales como los equipos que tiene cada usuario y así 
    tener un mayor control de lo que tiene cada usuario.
  </p>
</div>

<div class="tablas">
<div class="equipos">
<?php
include("tabla_equipos.php");
?>
</div>

<div class="material">
<?php
include("tabla_material.php");
?>
</div>
</div>




 <style>


        .intro{
          padding-left: 1%;
        }

      .tablas {
  display: flex;
  justify-content: space-between; /* O usa center, start, etc. según quieras */
  gap: 30px; /* Espacio entre las tablas */
  margin-left: 14%;
 
}

.titulo{
  text-align: center;
}

.material{
  margin-right: 1%;
   width: 50%;
}

    </style>


</body>
</html>
