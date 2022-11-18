<?php
require_once('Classes/Class.Cliente.php');
require_once('Classes/Class.Movimiento.php');

$id = 0;
$id_embarcacion = 0;
$id_amarra = 0;
$fecha_desde = date("Y-m-d", strtotime("tomorrow"));
$fecha_hasta = "0000-00-00";



/**
 * Boton para agregar movimiento
 */
if (isset($_POST["guardarMovimiento"])) {
  $id_embarcacion = $_POST["idEmbarcacion"];
  $id_amarra = $_POST["idAmarra"];

  $movimiento = new Movimiento($id, $id_embarcacion, $id_amarra, $fecha_desde, $fecha_hasta);
  $msg = $movimiento->insertMovimiento();

  $id = 0;
  $id_embarcacion = 0;
  $id_amarra = 0;
  $fecha_desde = date("Y-m-d", strtotime("tomorrow"));
  $fecha_hasta = "0000-00-00";
}
