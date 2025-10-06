<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="0M-inicio.css">
    <div id="manuales-container"></div>

</head>
<body>
  
    
<div class="menu">
    <h2>Menú</h2>
    <ul>
      <li><a href="../0inicio.php">Inicio</a></li>
      <li><a class="active" href="#"><b>Manuales</b></a></li>
      <li><a href="../inci/0I-inicio.php">Incidencias</a></li>
      <li><a href="../material/0Mat-inicio.php">Material asignado</a></li>
      <li><a href="../usuarios/usuarios.php">Altas y Bajas</a></li>
    </ul>
  </div>

  <div class="titulo"> 
    <h1>Manuales</h1>
  </div>

    <div class="intro">
        <p><b>Aquí podrás consultar los manuales paso a paso sobre el funcionamiento de la empresa.</b></p>
        <h3>Programas</h3>
        <p>
          Manuales que explican el uso de software específico que se utiliza en la empresa, tanto por técnicos como por usuarios. Ejemplos: herramientas de reservas, correo electrónico, CRM, etc.
          Y poder pasar los manuales a los usuarios.
        </p>
        <h3>Usuarios</h3>
        <p>Manuales destinados al personal general de la empresa. Explican cómo realizar tareas comunes como abrir tickets, usar programas básicos o acceder a sistemas internos.
        </p>
        <h3>Técnicos</h3>
        <p>
          Guías pensadas para el personal del departamento técnico o IT. Incluyen instrucciones sobre configuraciones avanzadas, como unir equipos al dominio, cifrar discos con BitLocker, etc.
        </p>
  </div>


<!-- Botones para los filtros -->
<div class="filtro-manuales">
  <button class="boton active" onclick="filtrarManuales('todos', this)">Todos</button>
  <button class="boton" onclick="filtrarManuales('pro', this)">Programas</button>
  <button class="boton" onclick="filtrarManuales('usuarios', this)">Usuarios</button>
  <button class="boton" onclick="filtrarManuales('tecnicos', this)">Técnicos </button>
</div>

<!-- los manuales -->
<div class="contenedor">
<div class="manual" data-categoria="pro">
  <a href="PDF/TeamViewer.pdf" target="_blank">
    <img src="img/1.png">
    <p>TeamViewer</p>
</a>
</div>

<div class="manual" data-categoria="tecnicos">
  <a href="PDF/Altas de Usuario.pdf" target="_blank">
    <img src="img/2.png">
    <p>Altas de usuarios</p>
</a>
</div>

  <div class="manual" data-categoria="tecnicos">
    <a href="PDF/Bajas de Usuario.pdf" target="_blank">
      <img src="img/3.png">
    <p>Bajas de usuario</p>
  </a>
  </div>

  <div class="manual" data-categoria="tecnicos">
    <a href="Agregar equipo a dominio TBOJOL.pdf" target="_blank">
      <img src="img/4.png">
    <p>Dominio</p>
  </a>
  </div>

  <div class="manual" data-categoria="tecnicos">
    <a href="PDF/BitLocker.pdf" target="_blank">
      <img src="img/5.png">
    <p>BitLocker</p>
  </a>
  </div>

  <div class="manual" data-categoria="tecnicos">
    <a href="PDF/Incidencias en Jira (técnicos).pdf" target="_blank">
      <img src="img/7tecnicos.png">
    <p>Crear incidencias en Jira</p>
  </a>
  </div>

  <div class="manual" data-categoria="tecnicos">
    <a href="PDF/PASOS A SEGUIR UNIFLOW - DAILY.pdf" target="_blank">
      <img src="img/8.png">
    <p>Daily - Uniflow</p>
  </a>
  </div>

  <div class="manual" data-categoria="tecnicos">
    <a href="PDF/CONEXIÓN ADMIN UNIFLOW ONLINE.pdf" target="_blank">
      <img src="img/9.png">
    <p>Conexion Admin Uniflow</p>
  </a>
  </div>

      <div class="manual" data-categoria="tecnicos">
    <a href="PDF/Crear usuario local en Sophos Firewal.pdf" target="_blank">
      <img src="img/12.png">
    <p>Usuario local en Sophos</p>
  </a>
  </div>

  <div class="manual" data-categoria="usuarios">
    <a href="PDF/Incidencias en Jira (usuario).pdf" target="_blank">
      <img src="img/6 usuario.png">
    <p>Crear incidencias en Jira</p>
  </a>
  </div>

  <div class="manual" data-categoria="usuarios">
    <a href="PDF/SETUP INICIAL UNIFLOW ONLINE - JUMBONLINE .pdf" target="_blank">
      <img src="img/10.png">
    <p>Crear incidencias en Jira</p>
  </a>
  </div>

  <div class="manual" data-categoria="usuarios">
    <a href="PDF/IS-MANUAL VPN JUMBONLINE.pdf" target="_blank">
      <img src="img/11.png">
    <p>VPN conexion de casa a oficina</p>
  </a>
  </div>

</div>

    <script src="../botones.js"></script>

    <style>
  
  </style>


</body>
</html>