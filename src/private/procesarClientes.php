<?php
require_once('./Classes/Class.Cliente.php');

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
 * Boton para agregar un cliente
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
 * GRANDES AVANCES
 * TENGO QUE CORREGIR LA ACTUALIZACION/BAJA
 * PORQUE NO ME ESTARÏA DEJANDO BIEN POR EL TEMA DE QUE SE ME ESTA MEZCLANDO EL GET CON EL POST Y ETC
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


  // Lo re-dirijo de nuevo al menu principal para que se reinicie la URL por el GET, sino siempre se va a quedar el ?id= algo 
  header('Location: clientes.php');
}


/**
 * Boton para seleccionar un cliente
 */
if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $cliente = Cliente::selectClienteById($id);
  $apellido_nombre = $cliente->apellido_nombre;
  $email = $cliente->email;
  $dni = $cliente->dni;
  $movil = $cliente->movil;
  $domicilio = $cliente->domicilio;
  $estado = $cliente->estado;
}


function getEstadoToString($cliente)
{
  return ($cliente->estado == 1)
    ? "Activo"
    : "Baja";
}
