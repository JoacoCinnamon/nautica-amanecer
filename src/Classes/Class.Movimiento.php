<?php

require_once('Class.Conexion.php');


class Movimiento
{
  private $id;
  private $id_embarcacion;
  private $id_amarra;
  private $fecha_desde;
  private $fecha_hasta;

  public static function selectAllMovimientos()
  {
    try {
      $sentenncia = "SELECT * FROM `embarcacion-amarras` ORDER BY id DESC";
      $select = Conexion::getConexion()->query($sentenncia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function __construct($id, $id_embarcacion, $id_amarra, $fecha_desde, $fecha_hasta)
  {
    $this->id = $id;
    $this->id_embarcacion = $id_embarcacion;
    $this->id_amarra = $id_amarra;
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
  }
}
