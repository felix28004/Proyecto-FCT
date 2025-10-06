<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="0inicio.css">
</head>
<body>


  <div class="menu">
    <h2>Menú</h2>
      <ul>
      <li><a href="../0inicio.php">Inicio</a></li>
      <li><a href="../manuales/0M-inicio.php">Manuales</a></li>
      <li><a href="../inci/0I-inicio.php">Incidencias</a></li>
      <li><a href="../material/0Mat-inicio.php">Material asignado</a></li>
      <li><a class="active" href="#"><b>Alatas y Bajas</b></a></li>
    </ul>
  </div>



  <div class="titulo"> 
  <h1>Altas y Bajas</h1>
  </div>

  <div class="intro">
  <p>Base de datos donde se puedra ver los usuarios que entran nuevos y hay que hacerles el alta antes de
    de que vengan, tambien se podra ver y poner cuando se de el alta y poder darle de alta cuando ya no este.
  </p>

  <h3>Explicacion sobre los estados</h3>
</div>

<?php
include("conexion-usuarios.php");

// Obtener estados desde la tabla 'estado'
$estados = $conn->query("SELECT * FROM estado");

// Obtener estados para entrega de material (solo Sí y No)
$estados_material = $conn->query("SELECT * FROM estado WHERE nombre IN ('Sí', 'No')");


//excluir Baja realizada del campo estado altas
$consulta_estados_alta = "SELECT * FROM estado WHERE nombre != 'Baja realizada'";
$estados_alta = $conn->query($consulta_estados_alta);

//excluir Empezo a trabajar del campo estado bajas
$consulta_estados_baja = "SELECT * FROM estado WHERE nombre != 'Empezo a trabajar'";
$estados_baja = $conn->query($consulta_estados_baja);

$consulta_estados_baja = "SELECT * FROM estado WHERE nombre != 'Usuario creado'";
$estados_baja = $conn->query($consulta_estados_baja);

// Guardar nuevo registro
$mensaje = "";


if (isset($_POST['guardar_usuario'])) {
    $nombre = $_POST['nombre'];
    $fecha_alta = $_POST['fecha_alta'];
    $estado_alta_id = $_POST['estado_alta_id'];
    $fecha_baja = $_POST['fecha_baja'];
    $estado_baja_id = $_POST['estado_baja_id'];
    $estado_entrega_material_id = $_POST['estado_entrega_material_id'];
    $observaciones = $_POST['observaciones'];

    $sql = "INSERT INTO altas_bajas (nombre, fecha_alta, estado_alta_id, fecha_baja, estado_baja_id, estado_entrega_material_id, observaciones)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiisis", $nombre, $fecha_alta, $estado_alta_id, $fecha_baja, $estado_baja_id, $estado_entrega_material_id, $observaciones);

    if ($stmt->execute()) {
        $mensaje = " ";
    } else {
        $mensaje = "❌ Error al guardar: " . $conn->error;
    }

    $stmt->close();
}



// Obtener los datos con los nombres de los estados
$sql = "SELECT ab.id, ab.nombre, ab.fecha_alta, ea.nombre AS estado_alta, ab.fecha_baja,
               eb.nombre AS estado_baja, em.nombre AS estado_material, ab.observaciones
        FROM altas_bajas ab
        LEFT JOIN estado ea ON ab.estado_alta_id = ea.id
        LEFT JOIN estado eb ON ab.estado_baja_id = eb.id
        LEFT JOIN estado em ON ab.estado_entrega_material_id = em.id";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Altas y Bajas de Usuarios</title>

</head>
<body>



<div class="tabla">
<table>
    <tr>
        <th>Nombre</th>
        <th>Fecha Alta</th>
        <th>Estado Alta</th>
        <th>Fecha Baja</th>
        <th>Estado Baja</th>
        <th>Material Entregado</th>
        <th>Observaciones</th>
        <th>Acciones</th> 
    </tr>


    <?php while ($fila = $resultado->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($fila['nombre']) ?></td>
    <td><?= htmlspecialchars($fila['fecha_alta']) ?></td>
    <td><?= htmlspecialchars($fila['estado_alta']) ?></td>
    <td><?= htmlspecialchars($fila['fecha_baja']) ?></td>
    <td><?= htmlspecialchars($fila['estado_baja']) ?></td>
    <td><?= htmlspecialchars($fila['estado_material']) ?></td>
    <td><?= htmlspecialchars($fila['observaciones']) ?></td>
    <td>
        <a href="editar_usuario.php?id=<?= $fila['id'] ?>">✏️ Editar</a> |
        <a href="eliminar_usuario.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">🗑️ Eliminar</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>

<?php if ($mensaje): ?>
    <p class="mensaje"><?= $mensaje ?></p>
<?php endif; ?>

<form method="POST">
    <h2>Registrar alta/baja de usuario</h2>

    <label>Nombre del usuario:</label>
    <input type="text" name="nombre" required>

    <label>Fecha de alta:</label>
    <input type="date" name="fecha_alta">



    <label>Estado del alta:</label>
<select name="estado_alta_id">
  <option value="">-- Selecciona estado --</option>
  <?php
  mysqli_data_seek($estados, 0);
  while ($estado = $estados->fetch_assoc()) {
      if (
          $estado['nombre'] === 'Baja realizada' ||
          $estado['nombre'] === 'Si' ||
          $estado['nombre'] === 'No'
      ) continue;
      echo "<option value='{$estado['id']}'>{$estado['nombre']}</option>";
  }
  ?>
</select>

 <label>Fecha de la baja:</label>
    <input type="date" name="fecha_baja">

<label>Estado de la baja:</label>
<select name="estado_baja_id">
  <option value="">-- Selecciona un estado --</option>
  <?php
  mysqli_data_seek($estados, 0);
  while ($estado = $estados->fetch_assoc()) {
      if (
          $estado['nombre'] === 'Empezo a trabajar' ||
          $estado['nombre'] === 'Si' ||
          $estado['nombre'] === 'No' ||
          $estado['nombre'] === 'Usuario creado'
      ) continue;
      echo "<option value='{$estado['id']}'>{$estado['nombre']}</option>";
  }
  ?>
</select>


<label>Estado entrega material:</label>
<select name="estado_entrega_material_id">
    <option value="">-- Selecciona un estado --</option>
    <?php
    while ($estado = $estados_material->fetch_assoc()) {
        echo "<option value='{$estado['id']}'>{$estado['nombre']}</option>";
    }
    ?>
</select>



<?php 


?>

    <label>Observaciones:</label>
    <textarea name="observaciones" rows="3"></textarea>

    <input class="btn2" type="submit" name="guardar_usuario" value="Guardar">
</form>


</body>
</html>



<style>
  
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');


/* Estilo para el fondo */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #3949AB;
    color: #FFFFFF; /* Texto blanco para contraste */
  }

  .titulo {
    color: rgb(255, 125, 3);
    border-bottom: 2px solid #FFA726;
    font-size: 30px;
    margin-bottom: 50px;
    width: 75%;
    font-family: "Space Grotesk", sans-serif;
    margin-left: 15%;
  }

.intro{
  border-left: 4px solid #FF7300; /* mismo naranja del título */
  padding-left: 1%;
  margin-bottom: 1em;
  font-size: 20px;
  padding-bottom: 1px;
  padding-left: 1px ;
  padding-top: 1px;
  font-family: "Sansation", sans-serif;
  font-weight: 400;
  font-style: normal;
  margin-left: 15%;
  width: 78%;
}

h3{
  color: rgb(253, 137, 27) ;
}

  
  /* Menú lateral */
  .menu {
    position: fixed;
    left: 0;
    top: 0;
    width: 220px;
    height: 100%;
    background-color: #1A237E;
    color: white;
    padding-top: 20px;
    box-shadow: 3px 0 10px rgba(1,1,1,1);
    font-size: 18px;
  }
  
  .menu h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #FFA726; /* Naranja */
  }
  
  .menu a{
    text-decoration: none;
    color: white;
    padding: 6%;
  }

  .menu ul {
  list-style-type: none;
  margin: 1;
  padding: 0;
  height: 100%;
  width: 220px;
  position: fixed;
  overflow: auto;
}


li a {
  display: block;
  color: #000;
  padding: 8px 16px;
  text-decoration: none;
}

li a.active {
  background-color: #FF7300;
  color: black;
}

li a:hover:not(.active) {
  background-color: #3949AB;
  color: white;
}


.tabla{
        margin-left: 10%;
}


table {
  border-collapse: collapse;
  width: 80%;
  margin: 20px auto;
  color: #000;

        }
        th, td {
            border: 2px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #FF9A28;
        }


        td {
            background-color: #FFB74D;
        }

          form {
            width: 20%;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 10px;
        }

        
        label, input, select, textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

input[type="submit"] {
  background-color: #3949AB;
  border: 2px solid black;
  border-radius: 5px;
  background-color:rgb(253, 132, 34);
  padding-bottom: 2%;
  padding-top: 2%;
  padding-left: 2%;
  padding-right: 2%;
  font-size: 15px;
}

input[type="submit"]:hover {
  cursor: pointer;
  background-color: #FF9A28;
  border: 2px solid black;
}

.mensaje {
  text-align: center;
  color: green;
  font-weight: bold;
}


  
  </style>
</body>
</html>