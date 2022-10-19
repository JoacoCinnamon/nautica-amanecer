<?php

// Comento las lineas donde "aplico" el patrón singleton porque
// no me permitió usarlo como pensé/hubiera querido. En cambio uso de manera abstracta la clase Conexion

//require_once('./Classes/Class.Conexion.php');
//$conn = Conexion::getConexion();
require_once('./Classes/Class.Amarra.php');


// Por defecto no hay id, ni pasillo y el estado es libre

$id = 0;
$pasillo = 0;
$estado = 0;
$msg = "";

$seSeleccionoAmarra = (isset($_POST["selectAmarras"]) && $_POST["idAmarra"] != 0);

// "Validación" de pasillo
if (isset($_POST["pasillo"])) {
  $seTipeoUnPasillo = ($_POST["pasillo"] != "" && strlen(trim($_POST["pasillo"])) != 0 && $_POST["pasillo"] > 0);
}

if (isset($_POST["botonAmarras"]) && $seTipeoUnPasillo) {

  $id = $_POST["idAmarra"];
  $pasillo = $_POST["pasillo"];
  // Si se cambió el estado que lo cambie, sino que por defecto siga siendo 0 (libre)
  $estado = isset($_POST["estado"]) ? $_POST["estado"] : 0;

  $amarra = new Amarra($id, $pasillo, $estado);

  if ($_POST["botonAmarras"] == "Agregar") {
    $msg = $amarra->insertAmarra();
  } elseif ($_POST["botonAmarras"] == "Editar") {
    $msg = $amarra->updateAmarra();
  } else {
    // Borrar amarra ?
  }
  $id = 0;
  $pasillo = 0;
  $estado = 0;

  // Clear the POST Request
}

if ($seSeleccionoAmarra) {
  $amarra = new Amarra($_POST["idAmarra"], 0, 0);
  $amarra = $amarra->selectAmarraById();
  if ($amarra) {
    $id = $amarra->id;
    $pasillo = $amarra->pasillo;
    $estado = $amarra->estado;
  }
}
