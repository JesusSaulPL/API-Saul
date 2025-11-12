<?php
include("php/conexion.php");

header("Content-Type: application/json; charset=UTF-8");

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        consulta($conexion);
        break;

    case 'POST':
        insertar($conexion);
        break;

    case 'PUT':
        Update($conexion);
        break;

    case 'DELETE':
        Delete($conexion);
        break;

    default:
        echo json_encode(["mensaje" => "Método no permitido"]);
        break;
}

function consulta($conexion)
{
    $sql = "SELECT * FROM alumnos";
    $resultado = $conexion->query($sql);

    if ($resultado) {
        $datos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }
        echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["mensaje" => "Error en la consulta"]);
    }
}

function insertar($conexion)
{
    // Verificar si los datos vienen del formulario en POST normal//
    if (!empty($_POST)) {
        $Matricula = $_POST['Matricula'];
        $Nombre = $_POST['Nombre'];
        $Apaterno = $_POST['Apaterno'];
        $Amaterno = $_POST['Amaterno'];
        $Email = $_POST['Email'];
        $Celular = $_POST['Celular'];
        $CP = $_POST['CP'];
        $Sexo = $_POST['Sexo'];
 
    }
    //Validar que la matrícula no esté vacía
    if (empty($Matricula)) {
        echo json_encode(["mensaje" => "La matrícula no puede estar vacía."]);
        exit;
    }

    // Verificar que la matrícula no exista
    $check = $conexion->prepare("SELECT COUNT(*) FROM alumnos WHERE Matricula = ?");
    $check->bind_param("s", $Matricula);
    $check->execute();
    $check->bind_result($existe);
    $check->fetch();
    $check->close();

    if ($existe > 0) {
        echo json_encode(["mensaje" => "La matrícula '$Matricula' ya existe."]);
        exit;
    }

    // Insertar nuevo registro
    $sql = "INSERT INTO alumnos (Matricula, Nombre, Apaterno, Amaterno, Email, Celular, CP, Sexo)
            VALUES ('$Matricula', '$Nombre', '$Apaterno', '$Amaterno', '$Email', '$Celular', '$CP', '$Sexo')";

    if ($conexion->query($sql)) {
        echo json_encode([
            "mensaje" => " Alumno registrado correctamente",
            "id_insertado" => $conexion->insert_id
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["mensaje" => " Error: " . $conexion->error]);
    }
}

function Update($conexion)
{
    $datos = json_decode(file_get_contents("php://input"), true);

    $id = (int)$datos['id'];
    $Matricula = $datos['Matricula'];
    $Nombre = $datos['Nombre'];
    $Apaterno = $datos['Apaterno'];
    $Amaterno = $datos['Amaterno'];
    $Email = $datos['Email'];
    $Celular = $datos['Celular'];
    $CP = $datos['CP'];
    $Sexo = $datos['Sexo'];

    if (empty($Matricula)) {
        echo json_encode(["mensaje" => "La matrícula no puede estar vacía."]);
        exit;
    }
// Verificar si la matrícula ha cambiado
    $sql1 = "SELECT Matricula FROM alumnos WHERE id = $id";
    $resultado = $conexion->query($sql1);

    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $matriculaActual = $fila['Matricula'];

    // Verificar si la nueva matrícula ya existe
        if ($matriculaActual !== $Matricula) {
            $check = $conexion->prepare("SELECT COUNT(*) FROM alumnos WHERE Matricula = ?");
            $check->bind_param("s", $Matricula);
            $check->execute();
            $check->bind_result($existe);
            $check->fetch();
            $check->close();

            if ($existe > 0) {
                echo json_encode(["mensaje" => "La matrícula '$Matricula' ya existe."]);
                exit;
            }
        }
    }

    $sql = "UPDATE alumnos SET 
                Matricula='$Matricula',
                Nombre='$Nombre',
                Apaterno='$Apaterno',
                Amaterno='$Amaterno',
                Email='$Email',
                Celular='$Celular',
                CP='$CP',
                Sexo='$Sexo'
            WHERE id=$id";

    if ($conexion->query($sql)) {
        echo json_encode(["mensaje" => "Se actualizaron los datos correctamente"]);
    } else {
        echo json_encode(["mensaje" => "Error al actualizar los datos"]);
    }
}


function Delete($conexion)
{


    
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data["id"];

    if ($id) {
        $sql = "DELETE FROM alumnos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Eliminado correctamente"]);
        } else {
            echo json_encode(["mensaje" => "Error al eliminar"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["mensaje" => "ID no válido"]);
    }
    exit;
}
}
?>
