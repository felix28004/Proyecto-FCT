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
      <li><a class="active" href="#"><b>Altas y Bajas</b></a></li>
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

  <ul>
  <li><strong>Usuario creado:</strong> Se ha creado el usuario en la base de datos, pero aún no ha comenzado a trabajar.</li>
  <li><strong>Empezó a trabajar:</strong> El usuario ya está activo en su puesto.</li>
  <li><strong>Baja realizada:</strong> El usuario ha finalizado su etapa y se ha procesado su baja.</li>
  <li><strong>Sí / No (entrega material):</strong> Indica si el usuario ha devuelto el material asignado al irse.</li>
</ul>

</div>

  </body>

<?php
include("conexion-usuarios.php");

// Obtener estados desde la tabla 'estado'
$estados = $conn->query("SELECT * FROM estado");
$estados_material = $conn->query("SELECT * FROM estado WHERE nombre IN ('Si', 'No')");

$consulta_estados_alta = "SELECT * FROM estado WHERE nombre != 'Baja realizada'";
$estados_alta = $conn->query($consulta_estados_alta);

$consulta_estados_baja = "SELECT * FROM estado WHERE nombre NOT IN ('Empezo a trabajar', 'Usuario creado')";
$estados_baja = $conn->query($consulta_estados_baja);

// Variables
$mensaje = "";
$modo_edicion = false;
$datos_editar = [];

// Guardar nuevo usuario
if (isset($_POST['guardar_usuario'])) {
    $nombre = $_POST['nombre'];
    $fecha_alta = $_POST['fecha_alta'];
    $estado_alta_id = $_POST['estado_alta_id'];

    // Insertar en la tabla altas
    $sql = "INSERT INTO altas (nombre, fecha_alta, estado_alta_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $fecha_alta, $estado_alta_id);
    $stmt->execute();

    $alta_id = $stmt->insert_id; // ID recién insertado en la tabla altas
    $stmt->close();

    // Si hay datos de baja, insertar en la tabla bajas
    if (!empty($_POST['fecha_baja']) || !empty($_POST['estado_baja_id']) || !empty($_POST['estado_entrega_material_id']) || !empty($_POST['observaciones'])) {
        $fecha_baja = $_POST['fecha_baja'];
        $estado_baja_id = $_POST['estado_baja_id'];
        $estado_entrega_material_id = $_POST['estado_entrega_material_id'];
        $observaciones = $_POST['observaciones'];

        $sql_baja = "INSERT INTO bajas (alta_id, fecha_baja, estado_baja_id, estado_entrega_material_id, observaciones) 
                     VALUES (?, ?, ?, ?, ?)";
        $stmt_baja = $conn->prepare($sql_baja);
        $stmt_baja->bind_param("issis", $alta_id, $fecha_baja, $estado_baja_id, $estado_entrega_material_id, $observaciones);
        $stmt_baja->execute();
        $stmt_baja->close();
    }
}

// Eliminar usuario
if (isset($_POST['eliminar_usuario'])) {
    $id = $_POST['eliminar_id'];

    // Eliminar primero de bajas
    $conn->query("DELETE FROM bajas WHERE alta_id = $id");

    // Luego eliminar de altas
    $conn->query("DELETE FROM altas WHERE id = $id");
}

// Iniciar edición
if (isset($_POST['editar_usuario'])) {
    $modo_edicion = true;
    $id_editar = $_POST['editar_id'];

    $sql_edit = "SELECT 
                    a.*, 
                    b.fecha_baja, b.estado_baja_id, b.estado_entrega_material_id, b.observaciones
                 FROM altas a
                 LEFT JOIN bajas b ON a.id = b.alta_id
                 WHERE a.id = $id_editar";

    $resultado_edit = $conn->query($sql_edit);
    if ($resultado_edit->num_rows > 0) {
        $datos_editar = $resultado_edit->fetch_assoc();
    }
}

// Guardar cambios tras editar
if (isset($_POST['guardar_cambios'])) {
    $id = $_POST['usuario_id'];
    $nombre = $_POST['nombre'];
    $fecha_alta = $_POST['fecha_alta'];
    $estado_alta_id = $_POST['estado_alta_id'];
    $fecha_baja = $_POST['fecha_baja'];
    $estado_baja_id = $_POST['estado_baja_id'];
    $estado_entrega_material_id = $_POST['estado_entrega_material_id'];
    $observaciones = $_POST['observaciones'];

    // Actualizar tabla altas
    $sql_update = "UPDATE altas SET 
        nombre = ?, 
        fecha_alta = ?, 
        estado_alta_id = ?
        WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssii", $nombre, $fecha_alta, $estado_alta_id, $id);
    $stmt->execute();
    $stmt->close();

    // Comprobar si ya existe una baja para este usuario
    $res = $conn->query("SELECT * FROM bajas WHERE alta_id = $id");
    if ($res->num_rows > 0) {
        // Actualizar si existe
        $sql_baja = "UPDATE bajas SET 
            fecha_baja = ?, 
            estado_baja_id = ?, 
            estado_entrega_material_id = ?, 
            observaciones = ?
            WHERE alta_id = ?";
        $stmt_baja = $conn->prepare($sql_baja);
        $stmt_baja->bind_param("siisi", $fecha_baja, $estado_baja_id, $estado_entrega_material_id, $observaciones, $id);
        $stmt_baja->execute();
        $stmt_baja->close();
    } else {
        // Insertar si no existe
        $sql_baja = "INSERT INTO bajas (alta_id, fecha_baja, estado_baja_id, estado_entrega_material_id, observaciones)
                     VALUES (?, ?, ?, ?, ?)";
        $stmt_baja = $conn->prepare($sql_baja);
        $stmt_baja->bind_param("issis", $id, $fecha_baja, $estado_baja_id, $estado_entrega_material_id, $observaciones);
        $stmt_baja->execute();
        $stmt_baja->close();
    }
}

// Obtener datos de usuarios
$sql = "SELECT 
            a.id, a.nombre, a.fecha_alta, ea.nombre AS estado_alta,
            b.fecha_baja, eb.nombre AS estado_baja, em.nombre AS estado_material, 
            b.observaciones
        FROM altas a
        LEFT JOIN estado ea ON a.estado_alta_id = ea.id
        LEFT JOIN bajas b ON a.id = b.alta_id
        LEFT JOIN estado eb ON b.estado_baja_id = eb.id
        LEFT JOIN estado em ON b.estado_entrega_material_id = em.id";

$resultado = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Altas y Bajas</title>
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
        <td><?= date("d/m/Y", strtotime($fila['fecha_alta'])) ?></td>
        <td><?= htmlspecialchars($fila['estado_alta']) ?></td>
        <td><?= $fila['fecha_baja'] ? date("d/m/Y", strtotime($fila['fecha_baja'])) : '' ?></td>
        <td><?= htmlspecialchars($fila['estado_baja']) ?></td>
        <td><?= htmlspecialchars($fila['estado_material']) ?></td>
        <td><?= htmlspecialchars($fila['observaciones']) ?></td>
        <td>

     
            <form method="POST" style="display:inline;">
                <input type="hidden" name="editar_id" value="<?= $fila['id'] ?>">
                <input type="submit" name="editar_usuario" class="boton" value="✏️Modificar">
            </form>
            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
                <input type="hidden" name="eliminar_id" value="<?= $fila['id'] ?>">
                <input type="submit" name="eliminar_usuario" class="boton eliminar" value="🗑️Eliminar">
            </form>
      

        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

<h2><?= $modo_edicion ? "Editar Usuario" : "Nuevo Usuario" ?></h2>

<form method="POST" class="formulario-principal">
  <h2>Registrar alta/baja de usuario</h2>

    <?php if ($modo_edicion): ?>
        <input type="hidden"  name="usuario_id"  value="<?= $datos_editar['id'] ?>"> <br>
    <?php endif; ?>

    <div class="campo">
    <label>Nombre:</label><br>
    <input  type="text"  name="nombre" required value="<?= $modo_edicion ? $datos_editar['nombre'] : '' ?>"> 
    </div>

    <div class="campo">
    <label>Fecha Alta:</label><br>
    <input type="date" name="fecha_alta" value="<?= $modo_edicion ? $datos_editar['fecha_alta'] : '' ?>">
      </div>

      <div class="campo"></div>
    <label>Estado Alta:</label><br>
    <select name="estado_alta_id">
        <option value="">-- Selecciona estado --</option>
        <?php
        mysqli_data_seek($estados, 0);
        while ($estado = $estados->fetch_assoc()) {
            if (in_array($estado['nombre'], ['Baja realizada', 'Si', 'No'])) continue;
            $selected = ($modo_edicion && $estado['id'] == $datos_editar['estado_alta_id']) ? 'selected' : '';
            echo "<option value='{$estado['id']}' $selected>{$estado['nombre']}</option>";
        }
        ?><br><br>
    </select>
    </div>

    <div class="campo"></div>
    <label>Fecha Baja:</label><br>
    <input type="date" name="fecha_baja" value="<?= $modo_edicion ? $datos_editar['fecha_baja'] : '' ?>"><br><br>
</div>

    <label>Estado Baja:</label><br>
    <select name="estado_baja_id">
        <option value="">-- Selecciona estado --</option>
        <?php
        mysqli_data_seek($estados, 0);
        while ($estado = $estados->fetch_assoc()) {
            if (in_array($estado['nombre'], ['Empezo a trabajar', 'Usuario creado', 'Si', 'No'])) continue;
            $selected = ($modo_edicion && $estado['id'] == $datos_editar['estado_baja_id']) ? 'selected' : '';
            echo "<option value='{$estado['id']}' $selected>{$estado['nombre']}</option>";
        }
        ?><br><br>
    </select>
    </div>

    <div class="campo"></div>
    <label>Material Entregado:</label><br>
    <select name="estado_entrega_material_id">
        <option value="">-- Selecciona estado --</option>
        <?php
        mysqli_data_seek($estados_material, 0);
        while ($estado = $estados_material->fetch_assoc()) {
            $selected = ($modo_edicion && $estado['id'] == $datos_editar['estado_entrega_material_id']) ? 'selected' : '';
            echo "<option value='{$estado['id']}' $selected>{$estado['nombre']}</option>";
        }
        ?><br><br>
    </select>
    </div>

    <div class="campo">
    <label>Observaciones:</label><br>
    <textarea name="observaciones"><?= $modo_edicion ? $datos_editar['observaciones'] : '' ?></textarea><br><br>
</div>
    <input class="botonForm" type="submit" name="<?= $modo_edicion ? 'guardar_cambios' : 'guardar_usuario' ?>" value="<?= $modo_edicion ? 'Guardar Cambios' : 'Guardar' ?>">
</form>

</body>




</html>

<style>
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


.boton {
    padding: 4px 8px;
    text-decoration: none;
    border: 1px solid black;
    background-color: #1976d2;
    color: white;
    border-radius: 4px;
    font-size: 0.9em;
}

.boton:hover {
   
   background-color:rgb(76, 89, 173);
    }

.boton.eliminar {
    background-color: #c62828;
    }

.boton.eliminar:hover{
   background-color:rgb(171, 57, 57);
    }

.formulario-principal {
    width: 20%;
            margin: 20px auto;
            padding: 20px;
           margin-top: 20px;
            text-align: center;
            box-shadow: 3px 0 10px rgba(1,1,1,1);
            border-radius: 10px;
          

}


.botonForm {
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

.botonForm:hover {
  cursor: pointer;
  background-color: #FF9A28;
  border: 2px solid black;
}

.campo {
    margin-top: 20px;

  
}
  </style>