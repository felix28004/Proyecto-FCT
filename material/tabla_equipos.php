<?php
include("conexion.php");

// Inicializar mensaje
$mensaje = "";
$modo_edicion = false;
$equipo_editar = null;

// Obtener tipos de equipo
$consulta_tipos = "SELECT * FROM tipos_equipos";
$resultado_tipos = $conn->query($consulta_tipos);

// 🔁 Eliminar equipo
if (isset($_GET['eliminarM'])) {
    $id = intval($_GET['eliminarM']);
    $conn->query("DELETE FROM equipos WHERE id = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 🔁 Cargar equipo a editar
if (isset($_GET['editar'])) {
    $modo_edicion = true;
    $id_editar = intval($_GET['editar']);
    $resultado_editar = $conn->query("SELECT * FROM equipos WHERE id = $id_editar");
    $equipo_editar = $resultado_editar->fetch_assoc();
}

// 🔁 Guardar edición
if (isset($_POST['actualizar_equipo'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre_equipo'];
    $tipo_id = $_POST['tipo_id'];
    $numero_serie = $_POST['numero_serie'];
    $usuario = $_POST['usuario_asignado'];

    $stmt = $conn->prepare("UPDATE equipos SET nombre_equipo=?, tipo_id=?, numero_serie=?, usuario_asignado=? WHERE id=?");
    $stmt->bind_param("sissi", $nombre, $tipo_id, $numero_serie, $usuario, $id);
    if ($stmt->execute()) {
        $mensaje = "";
    } else {
        $mensaje = "Error al actualizar.";
    }
    $modo_edicion = false;
}

// Añadir nuevo equipo
if (isset($_POST['guardar_equipo'])) {
    $nombre = $_POST['nombre_equipo'];
    $tipo_id = $_POST['tipo_id'];
    $serie = $_POST['numero_serie'];
    $usuario = $_POST['usuario_asignado'];

    if (!empty($nombre) && !empty($tipo_id) && !empty($serie)) {
        $sql = "INSERT INTO equipos (nombre_equipo, tipo_id, numero_serie, usuario_asignado) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siss", $nombre, $tipo_id, $serie, $usuario);
        if ($stmt->execute()) {
            $mensaje = " ";
        } else {
            $mensaje = "❌ Error al guardar: " . $conn->error;
        }
        $stmt->close();
    } else {
        $mensaje = "⚠️ Completa todos los campos requeridos.";
    }
}

// Obtener tabla actualizada
$sql = "SELECT equipos.id, equipos.nombre_equipo, tipos_equipos.tipo AS tipo, equipos.numero_serie, equipos.usuario_asignado
        FROM equipos   
        JOIN tipos_equipos ON equipos.tipo_id = tipos_equipos.id";
$resultado = $conn->query($sql);
?>

<script>
function mostrarFormulario() {
    document.getElementById("formulario").style.display = "block";
}
</script>

<body>
<?php if (!empty($mensaje)): ?>
    <p class='mensaje'><?= $mensaje ?></p>
<?php endif; ?>

<!-- TABLA -->
<div class="tablaEquipos">
    <h2 class="subtitulo">Listado de Equipos</h2>

    <?php if ($resultado->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Nombre del equipo</th>
                <th>Tipo de equipo</th>
                <th>Número de serie</th>
                <th>Nombre usuario asignado</th>
                <th>Acciones</th>
            </tr>
            <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila["nombre_equipo"]) ?></td>
                    <td><?= htmlspecialchars($fila["tipo"]) ?></td>
                    <td><?= htmlspecialchars($fila["numero_serie"]) ?></td>
                    <td><?= htmlspecialchars($fila["usuario_asignado"]) ?></td>
                    <td>
                        
                        <a href="?editar=<?= $fila['id'] ?>" class="boton">Modificar</a>
                        <a href="?eliminarM=<?= $fila['id'] ?>" class="boton eliminar" onclick="return confirm('¿Eliminar este equipo?')">Eliminar</a>
                       
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No hay registros en la tabla.</p>
    <?php endif; ?>

    <br>
    <?php if (!$modo_edicion): ?>
        <button class="btn-añadir" onclick="mostrarFormulario()"> Añadir equipo</button>
    <?php endif; ?>
</div>

<!-- FORMULARIO -->
<div id="formulario" class="formulario" style="display: <?= $modo_edicion ? 'block' : 'none' ?>;">
    <h2><?= $modo_edicion ? 'Modificar equipo' : 'Registrar nuevo equipo' ?></h2>
    <form method="post">
        <?php if ($modo_edicion): ?>
            <input type="hidden" name="id" value="<?= $equipo_editar['id'] ?>">
        <?php endif; ?>

        <label>Nombre del equipo:</label><br>
        <input type="text" name="nombre_equipo" required value="<?= $modo_edicion ? $equipo_editar['nombre_equipo'] : '' ?>"><br><br>

        <label>Tipo:</label><br>
        <select name="tipo_id" required>
            <option value="">-- Selecciona un tipo --</option>
            <?php
            mysqli_data_seek($resultado_tipos, 0);
            while ($tipo = $resultado_tipos->fetch_assoc()) {
                $selected = ($modo_edicion && $equipo_editar['tipo_id'] == $tipo['id']) ? 'selected' : '';
                echo "<option value='" . $tipo['id'] . "' $selected>" . $tipo['tipo'] . "</option>";
            }
            ?>
        </select><br><br>

        <label>Número de serie:</labe><br>
        <input type="text" name="numero_serie" required value="<?= $modo_edicion ? $equipo_editar['numero_serie'] : '' ?>"><br><br>

        <label>Usuario asignado:</label><br>    
        <input type="text" name="usuario_asignado" value="<?= $modo_edicion ? $equipo_editar['usuario_asignado'] : '' ?>"><br><br>

        <input class="btn2" type="submit" name="<?= $modo_edicion ? 'actualizar_equipo' : 'guardar_equipo' ?>" value="<?= $modo_edicion ? 'Actualizar' : 'Guardar' ?>">
    </form>
</div>


<style>


    .formulario {
        width: 40%;
            margin: 20px auto;
            padding: 20px;
           margin-top: 20px;
            text-align: center;
            box-shadow: 3px 0 10px rgba(1,1,1,1);
            border-radius: 10px;
    }

    table {
        border-collapse: collapse;

    }

    .subtitulo {
        text-align: center;
    }

    .mensaje {
        margin-left: 18%;
        margin-top: 10px;
      
    }

th {
    padding: 2%;
    border: 3px solid rgb(0, 0, 0);
    background-color: rgb(255, 125, 3);
    color: black;
}

td {
    border: 3px solid rgb(0, 0, 0);
    background-color: rgb(247, 147, 53);
    color: black;
    text-align: center;
    padding-bottom: 2%;
    padding-top: 2%;
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



th:nth-child(2) {
    background-color: #FF9A28;
}

td:nth-child(2) {
  background-color: #FFB74D;
}

th:nth-child(4) {
    background-color: #FF9A28;
}

td:nth-child(4) {
  background-color: #FFB74D;
}


.btn-añadir{
 
   background-color: #3949AB;
    border: 2px solid black;
    border-radius: 5px;
    background-color:rgb(253, 132, 34);
    padding-bottom: 2%;
    padding-top: 2%;
    padding-left: 2%;
    padding-right: 2%;
    margin-left: 40%;
    font-size: 15px;
}

.btn-añadir:hover{
    cursor: pointer;
    background-color: #FF9A28;
    border: 2px solid black;  
}

.btn2{
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

.btn2:hover{
    cursor: pointer;
    background-color: #FF9A28;
    border: 2px solid black;  
}






</style>
</body>
