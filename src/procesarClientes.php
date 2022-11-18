<?php
require_once('Classes/Class.Cliente.php');

// Se valida para que no haya espacios, ni / y tampoco codigo HTML que puedan meter
function validarInput($cadena)
{
  $cadena = trim($cadena);
  $cadena = stripslashes($cadena);
  $cadena = htmlspecialchars($cadena);
  return $cadena;
}

$id = 0;
$apellido_nombre = $email = $dni = $movil = $domicilio = "";
$modal_apellido_nombre = $modal_email = $modal_dni = $modal_movil = $modal_domicilio = "";
$estado = 1;


/**
 * Boton para agregar cliente
 */
if (isset($_POST["agregarClientes"])) {
  $apellido_nombre = validarInput($_POST["apellido_nombre"]);
  $email = validarInput($_POST["email"]);
  $dni = validarInput($_POST["dni"]);
  $movil = validarInput($_POST["movil"]);
  $domicilio = validarInput($_POST["domicilio"]);

  $cliente = new Cliente(0, $apellido_nombre, $email, $dni, $movil, $domicilio, $estado);
  $msg = $cliente->insertCliente();
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
    //http_response_code(404);
    header('Location: clientes.php');
    exit();
  } else {
    // Else por las dudas, hay veces que no redirigia bien el header
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
  $apellido_nombre = validarInput($_POST["apellido_nombre"]);
  $email = validarInput($_POST["email"]);
  $dni = validarInput($_POST["dni"]);
  $movil = validarInput($_POST["movil"]);
  $domicilio = validarInput($_POST["domicilio"]);
  $estado = $_POST["estado"];

  $cliente = new Cliente($id, $apellido_nombre, $email, $dni, $movil, $domicilio, $estado);
  $msg = $cliente->updateCliente();

  $id = 0;
  $apellido_nombre = $email = $dni = $movil = $domicilio = "";
  $estado = 1;
}




function getEstadoToString($cliente): string
{
  return ($cliente->estado == 1)
    ? "Activo"
    : "Baja";
}
