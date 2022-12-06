<?php
require_once('Classes/Class.Cliente.php');

// Se valida para que no haya espacios, ni / y tampoco codigo HTML que puedan meter
function procesarInput($cadena)
{
  $cadena = trim($cadena);
  $cadena = stripslashes($cadena);
  $cadena = htmlspecialchars($cadena);
  return $cadena;
}

function getEstadoToString($cliente): string
{
  return ($cliente->estado == 1)
    ? "Activo"
    : "Baja";
}

function setAlertAgregar(bool $response, int $dni, string $apellido_nombre): array
{
  return $response
    ? [
      "msg" => "Se agregó correctamente a $apellido_nombre - DNI: $dni",
      "icon" => "check-circle-fill",
      "strong" => "",
      "status" => "success"
    ]
    : [
      "msg" => "No se pudo agregar a $apellido_nombre - DNI: $dni... Verifique los campos (no se puede repetir el DNI).",
      "icon" => "exclamation-triangle-fill",
      "strong" => "AVISO:",
      "status" => "danger"
    ];
}

function setAlertEditar(bool $response, int $dni, string $apellido_nombre): array
{
  return $response
    ? [
      "msg" => "Se actualizó correctamente a $apellido_nombre - DNI: $dni",
      "icon" => "check-circle-fill",
      "strong" => "",
      "status" => "success"
    ]
    : [
      "msg" => "No se pudo actualizar a $apellido_nombre con DNI: $dni... Verifique los campos (no se puede repetir el DNI).",
      "icon" => "exclamation-triangle-fill",
      "strong" => "AVISO:",
      "status" => "danger"
    ];
}

$id = 0;
$apellido_nombre = $email = $dni = $movil = $domicilio = "";
$estado = 1;
$alert = [];


/**
 * Boton para agregar cliente
 */
if (isset($_POST["agregarClientes"])) {
  $apellido_nombre = procesarInput($_POST["apellido_nombre"]);
  $email = procesarInput($_POST["email"]);
  $dni = procesarInput($_POST["dni"]);
  $movil = procesarInput($_POST["movil"]);
  $domicilio = procesarInput($_POST["domicilio"]);

  $cliente = new Cliente(0, $apellido_nombre, $email, $dni, $movil, $domicilio, $estado);
  $alert["res"] = $cliente->insertCliente();
  $alert["res"] = setAlertAgregar($alert["res"], $dni, $apellido_nombre);

  $id = 0;
  $apellido_nombre = $email = $dni = $movil = $domicilio = "";
  $estado = 1;
}

/**
 * Boton para seleccionar un cliente
 */
if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $cliente = Cliente::selectClienteById($id);
  if (!$cliente) {
    // Si no existe el cliente lo redirijo de nuevo a clientes.php
    header('Location: clientes.php');
    exit();
  } else {
    $apellido_nombre = $cliente->apellido_nombre;
    $email = $cliente->email;
    $dni = $cliente->dni;
    $movil = $cliente->movil;
    $domicilio = $cliente->domicilio;
    $estado = $cliente->estado;
  }
}

/**
 * Boton para editar cliente
 */
if (isset($_POST["editarClientes"])) {
  $id = $_POST["id"];
  $apellido_nombre = procesarInput($_POST["apellido_nombre"]);
  $email = procesarInput($_POST["email"]);
  $dni = procesarInput($_POST["dni"]);
  $movil = procesarInput($_POST["movil"]);
  $domicilio = procesarInput($_POST["domicilio"]);
  $estado = $_POST["estado"];

  $cliente = new Cliente($id, $apellido_nombre, $email, $dni, $movil, $domicilio, $estado);
  $alert["res"] = $cliente->updateCliente();
  $alert["res"] = setAlertEditar($alert["res"], $dni, $apellido_nombre);

  $id = 0;
  $apellido_nombre = $email = $dni = $movil = $domicilio = "";
  $estado = 1;
}
