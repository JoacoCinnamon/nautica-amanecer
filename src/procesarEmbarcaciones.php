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

$id = $id_cliente = 0;
$nombre = $rey = "";
$estado = 1;

/**
 * Boton para agregar embarcación
 */
if (isset($_POST["agregarEmbarcaciones"])) {
  $nombre = procesarInput($_POST["nombre"]);
  $rey = procesarInput($_POST["rey"]);
  $id_cliente = $_POST["idCliente"];

  $embarcacion = new Embarcacion(0, $nombre, $rey, $id_cliente, 1);
  $msg = $embarcacion->insertEmbarcacion();

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
    header('Location: 404.php');
    // No me estaría dejando cambiarle el estado HTTP
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
  $msg = $embarcacion->updateEmbarcacion();

  $id = $id_cliente = 0;
  $nombre = $rey = "";
  $estado = 1;
}


function getEstadoToString($embarcacion): string
{
  return ($embarcacion->estado == 1)
    ? "Activo"
    : "Baja";
}
