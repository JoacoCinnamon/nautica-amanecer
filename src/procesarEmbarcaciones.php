<?php
require_once('Classes/Class.Embarcacion.php');
require_once('Classes/Class.Cliente.php');

// Se valida para que no haya espacios, ni / y tampoco codigo HTML que puedan meter
function procesarInput($cadena)
{
  $cadena = trim($cadena);
  $cadena = stripslashes($cadena);
  $cadena = htmlspecialchars($cadena);
  return $cadena;
}

function getEstadoToString($embarcacion): string
{
  return ($embarcacion->estado == 1)
    ? "Activo"
    : "Baja";
}

function setAlertAgregar(bool $response, int $rey, string $nombre, string $apellido_nombre): array
{
  return $response
    ? [
      "msg" => "Se agregó correctamente a '$nombre' - REY: $rey de $apellido_nombre",
      "strong" => "",
      "status" => "success"
    ]
    : [
      "msg" => "No se pudo agregar a '$nombre' con REY: $rey de $apellido_nombre...  Verifique los campos (no se puede repetir el REY).",
      "strong" => "AVISO:",
      "status" => "danger"
    ];
}

function setAlertEditar(bool $response, int $rey, string $nombre, string $apellido_nombre): array
{
  return $response
    ? [
      "msg" => "Se actualizó correctamente a '$nombre' - REY: $rey de $apellido_nombre",
      "strong" => "",
      "status" => "success"
    ]
    : [
      "msg" => "No se pudo actualizar a '$nombre' con REY: $rey de $apellido_nombre...  Verifique los campos (no se puede repetir el REY).",
      "strong" => "AVISO:",
      "status" => "danger"
    ];
}

$id = $id_cliente = 0;
$nombre = $rey = "";
$estado = 1;
$alert = [];

/**
 * Boton para agregar embarcación
 */
if (isset($_POST["agregarEmbarcaciones"])) {
  $nombre = procesarInput($_POST["nombre"]);
  $rey = procesarInput($_POST["rey"]);
  $id_cliente = $_POST["idCliente"];

  $embarcacion = new Embarcacion(0, $nombre, $rey, $id_cliente, $estado);
  $alert["res"] = $embarcacion->insertEmbarcacion();
  $alert["res"] = setAlertAgregar($alert["res"], $rey, $nombre, CLiente::selectClienteById($id_cliente)->apellido_nombre);

  $id = $id_cliente = 0;
  $nombre = $rey = "";
  $estado = 1;
}

/**
 * Boton para seleccionar una embarcación
 */
if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $embarcacion = Embarcacion::selectEmbarcacionById($id);
  if (!$embarcacion) {
    // Si no existe el cliente lo redirijo de nuevo a embarcaciones.php
    header('Location: embarcaciones.php');
    exit();
  } else {
    $nombre = $embarcacion->nombre;
    $rey = $embarcacion->rey;
    $id_cliente = $embarcacion->id_cliente;
    $estado = $embarcacion->estado;
  }
}

/**
 * Boton para editar embarcación
 */
if (isset($_POST["editarEmbarcaciones"])) {
  $id = $_POST["id"];
  $nombre = procesarInput($_POST["nombre"]);
  $rey = procesarInput($_POST["rey"]);
  $id_cliente = $_POST["idCliente"];
  $estado = $_POST["estado"];

  $embarcacion = new Embarcacion($id, $nombre, $rey, $id_cliente, $estado);
  $alert["res"] = $embarcacion->updateEmbarcacion();
  $alert["res"] = setAlertEditar($alert["res"], $rey, $nombre, CLiente::selectClienteById($id_cliente)->apellido_nombre);
  $id = $id_cliente = 0;
  $nombre = $rey = "";
  $estado = 1;
}
