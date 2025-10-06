<?php
include("conexion.php");

// Inicializar mensaje
$mensaje = "";
$modo_edicion = false;
$editarMaterial = null;

// Obtener tipos de material
$consulta_tipos = "SELECT * FROM tipos_material";
$resultado_tipos = $conn->query($consulta_tipos);

// 🔁 Eliminar material
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM material WHERE id = $id");
    echo "<script>location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
exit();

}

// 🔁 Cargar material a editarMaterial
if (isset($_GET['editarMaterial'])) {
    $modo_edicion = true;
    $id_editarMaterial = intval($_GET['editarMaterial']);
    $resultado_editarMaterial = $conn->query("SELECT * FROM material WHERE id = $id_editarMaterial");
    $editarMaterial = $resultado_editarMaterial->fetch_assoc();
}

// 🔁 Guardar edición
if (isset($_POST['actualizar_material'])) {
    $id = $_POST['id'];
    $nombre = $_POST['fecha_recibido'];
    $tipo_id = $_POST['tipo_id'];
    $marca = $_POST['marca'];
    $usuario = $_POST['usuario_asignado'];

    $stmt = $conn->prepare("UPDATE material SET fecha_recibido=?, tipo_id=?, marca=?, usuario_asignado=? WHERE id=?");
    $stmt->bind_param("sissi", $nombre, $tipo_id, $marca, $usuario, $id);
    if ($stmt->execute()) {
        $mensaje = "";
    } else {
        $mensaje = "Error al actualizar.";
    }
    $modo_edicion = false;
}

// Añadir nuevo material
if (isset($_POST['guardar_material'])) {
    $nombre = $_POST['fecha_recibido'];
    $tipo_id = $_POST['tipo_id'];
    $marca = $_POST['marca'];
    $usuario = $_POST['usuario_asignado'];

    if (!empty($nombre) && !empty($tipo_id) && !empty($marca)) {
        $sql = "INSERT INTO material (fecha_recibido, tipo_id, marca, usuario_asignado) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siss", $nombre, $tipo_id, $marca, $usuario);
        if ($stmt->execute()) {
            $mensaje = "";
        } else {
            $mensaje = "❌ Error al guardar: " . $conn->error;
        }
        $stmt->close();
    } else {
        $mensaje = "⚠️ Completa todos los campos requeridos.";
    }
}

// Obtener tabla actualizada
$sql = "SELECT material.id, material.fecha_recibido, tipos_material.tipo AS tipo, material.marca, material.usuario_asignado
        FROM material   
        JOIN tipos_material ON material.tipo_id = tipos_material.id";
$resultado = $conn->query($sql);
?>

<script>
function mostrarFormMaterial() {
    document.getElementById("formMaterial").style.display = "block";
}
</script>

<body>
<?php if (!empty($mensaje)): ?>
    <p class='mensaje'><?= $mensaje ?></p>
<?php endif; ?>

<!-- TABLA -->
<div class="ok">
    <h2 class="subtitulo">Listado de material</h2>

    <?php if ($resultado->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Fecha material recibido</th>
                <th>Tipo de material</th>
                <th>Marca</th>
                <th>Nombre usuario asignado</th>
                <th>Acciones</th>
            </tr>
            <?php while($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= date("d/m/Y", strtotime($fila['fecha_recibido'])) ?></td>
                    <td><?= htmlspecialchars($fila["tipo"]) ?></td>
                    <td><?= htmlspecialchars($fila["marca"]) ?></td>
                    <td><?= htmlspecialchars($fila["usuario_asignado"]) ?></td>
                    <td>
                        
                        <a href="?editarMaterial=<?= $fila['id'] ?>" class="boton">Modificar</a>
                        <a href="?eliminar=<?= $fila['id'] ?>" class="boton eliminar" onclick="return confirm('¿Eliminar este material?')">Eliminar</a>
                       
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No hay registros en la tabla.</p>
    <?php endif; ?>

    <br>
    <?php if (!$modo_edicion): ?>
        <button class="btn-añadir" onclick="mostrarFormMaterial()"> Añadir material</button>
    <?php endif; ?>
</div>

<!-- FORMMaterial -->
<div id="formMaterial" class="formMaterial" style="display: <?= $modo_edicion ? 'block' : 'none' ?>;">
    <h2><?= $modo_edicion ? 'Modificar material' : 'Registrar nuevo material' ?></h2>
    <form method="post">
        <?php if ($modo_edicion): ?>
            <input type="hidden" name="id" value="<?= $editarMaterial['id'] ?>">
        <?php endif; ?>

        <label>Fecha material recibido:</label><br>
        <input type="date" name="fecha_recibido" required value="<?= $modo_edicion ? $editarMaterial['fecha_recibido'] : '' ?>"><br><br>

        <label>Tipo:</label><br>
        <select name="tipo_id" required>
            <option value="">-- Selecciona un tipo --</option>
            <?php
            mysqli_data_seek($resultado_tipos, 0);
            while ($tipo = $resultado_tipos->fetch_assoc()) {
                $selected = ($modo_edicion && $editarMaterial['tipo_id'] == $tipo['id']) ? 'selected' : '';
                echo "<option value='" . $tipo['id'] . "' $selected>" . $tipo['tipo'] . "</option>";
            }
            ?>
        </select><br><br>

        <label>Marca:</labe><br>
        <input type="text" name="marca" required value="<?= $modo_edicion ? $editarMaterial['marca'] : '' ?>"><br><br>

        <label>Usuario asignado:</label><br>    
        <input type="text" name="usuario_asignado" value="<?= $modo_edicion ? $editarMaterial['usuario_asignado'] : '' ?>"><br><br>

        <input class="btn2" type="submit" name="<?= $modo_edicion ? 'actualizar_material' : 'guardar_material' ?>" value="<?= $modo_edicion ? 'Actualizar' : 'Guardar' ?>">
    </form>
</div>

<!-- ESTILOS -->
<style>
    .ok {
       
    }

.formMaterial {
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
  
}

.btn-añadir:hover{


}

.btn2{

}

.btn2:hover{

}



</style>
</body>
