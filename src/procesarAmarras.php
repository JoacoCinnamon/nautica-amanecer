<?php
require_once('Classes/Class.Amarra.php');

function getEstadoToString($amarra): string
{
  return ($amarra->estado == 0)
    ? "Libre"
    : "Ocupado";
}

function setAlertAgregar(bool $response, int $id, int $pasillo): array
{
  return $response
    ? [
      "msg" => "Se agregó correctamente la amarra N° $id en el pasillo $pasillo",
      "strong" => "",
      "status" => "success"
    ]
    : [
      "msg" => "No se pudo agregar la amarra...",
      "strong" => "AVISO:",
      "status" => "danger"
    ];
}

function setAlertEditar(bool $response, int $id, int $pasillo): array
{
  return $response
    ? [
      "msg" => "Se actualizó la amarra N° $id en el pasillo $pasillo",
      "icon" => "check-circle-fill",
      "strong" => "",
      "status" => "success"
    ]
    : [
      "msg" => "No se pudo actualizar la amarra...",
      "icon" => "exclamation-triangle-fill",
      "strong" => "AVISO:",
      "status" => "danger"
    ];
}

$id = $pasillo = $estado =  0;
$alert = [];

$seSeleccionoAmarra = (isset($_POST["selectAmarras"]) && $_POST["idAmarra"] != 0);

// "Validación" de pasillo
if (isset($_POST["pasillo"])) {
  $seTipeoUnPasillo = ($_POST["pasillo"] != "" && strlen(trim($_POST["pasillo"])) != 0 && $_POST["pasillo"] > 0);
}

/**
 * Se quiere agregar/actualizar/borrar una amarra
 */
if (isset($_POST["botonAmarras"]) && $seTipeoUnPasillo) {

  $id = $_POST["idAmarra"];
  $pasillo = $_POST["pasillo"];
  // Si se cambió el estado que lo cambie, sino que por defecto siga siendo 0 (libre)
  $estado = isset($_POST["estado"]) ? $_POST["estado"] : 0;

  $amarra = new Amarra($id, $pasillo, $estado);

  if ($_POST["botonAmarras"] == "Agregar") {
    $alert["res"] = $amarra->insertAmarra();
    $alert["res"] = setAlertAgregar($alert["res"], $id, $pasillo);
  } elseif ($_POST["botonAmarras"] == "Editar") {
    $alert["res"] = $amarra->updateAmarra();
    $alert["res"] = setAlertEditar($alert["res"], $id, $pasillo);
  } else {
    // Borrar amarra ? NO SE PUEDEN BORRAR
  }

  $id = $pasillo = $estado =  0;
}

if ($seSeleccionoAmarra) {
  $amarra = Amarra::selectAmarraById($_POST["idAmarra"]);
  // Si existe
  if ($amarra) {
    $id = $amarra->id;
    $pasillo = $amarra->pasillo;
    $estado = $amarra->estado;
  }
}
