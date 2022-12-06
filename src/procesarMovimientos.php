<?php
require_once('Classes/Class.Cliente.php');
require_once('Classes/Class.Movimiento.php');

function parsearFecha(string $fecha): string
{
  $timestamp = strtotime($fecha);
  $fechaParseada = date("d/m/Y", $timestamp);
  return ($fecha == "0000-00-00")
    ? "Vigente"
    : $fechaParseada;
}

function setAlertAgregar(bool $response, int $id_embarcacion, int $id_amarra, string $fecha_desde): array
{
  $embarcacion = Embarcacion::selectEmbarcacionById($id_embarcacion);
  $amarra = Amarra::selectAmarraById($id_amarra);
  $fecha_desde = parsearFecha($fecha_desde);
  return $response
    ? [
      "msg" => "Se ha generado correctamente el movimiento. Se registró a '$embarcacion->nombre' - REY: $embarcacion->rey en la amarra N°$amarra->id desde el $fecha_desde...",
      "icon" => "check-circle-fill",
      "strong" => "",
      "status" => "success"
    ]
    : [
      "msg" => "No se pudo generar el movimiento...",
      "icon" => "exclamation-triangle-fill",
      "strong" => "AVISO:",
      "status" => "danger"
    ];
}

$id = $id_embarcacion = $id_amarra = 0;
$fecha_desde = date("Y-m-d", strtotime("+1 day"));
$fecha_hasta = "0000-00-00";
$alert = [];


/**
 * Boton para agregar movimiento
 */
if (isset($_POST["guardarMovimiento"]) && $_POST["idEmbarcacion"] != 0 && $_POST["idAmarra"] != 0) {
  $id_embarcacion = $_POST["idEmbarcacion"];
  $id_amarra = $_POST["idAmarra"];

  $movimiento = new Movimiento($id, $id_embarcacion, $id_amarra, $fecha_desde, $fecha_hasta);
  $alert["res"] = $movimiento->insertMovimiento();
  $alert["res"] = setAlertAgregar($alert["res"], $id_embarcacion, $id_amarra, $fecha_desde);

  $id = 0;
  $id_embarcacion = 0;
  $id_amarra = 0;
  $fecha_desde = date("Y-m-d", strtotime("+1 day"));
  $fecha_hasta = "0000-00-00";
}
