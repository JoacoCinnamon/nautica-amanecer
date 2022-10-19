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

$apellido_nombre = $email = $dni = $movil = $domicilio = "";
$modal_apellido_nombre = $modal_email = $modal_dni = $modal_movil = $modal_domicilio = "";
$estado = 1;

// Boton de agregar
if (isset($_POST["agregarClientes"])) {
  $apellido_nombre = validarInput($_POST["apellido_nombre"]);
  $email = validarInput($_POST["email"]);
  $dni = validarInput($_POST["dni"]);
  $movil = validarInput($_POST["movil"]);
  $domicilio = validarInput($_POST["domicilio"]);

  $cliente = new Cliente(0, $apellido_nombre, $email, $dni, $movil, $domicilio, $estado);
  $msg = $cliente->insertCliente();

  $apellido_nombre = $email = $dni = $movil = $domicilio = "";
  $estado = 1;
}

if (isset($_POST["editarCliente"])) {
  
}


function getEstadoToString($cliente)
{
  return ($cliente->estado == 1) ? "Activo" : "Baja";
}
