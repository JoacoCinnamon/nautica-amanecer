<?php

require_once('Class.Conexion.php');
require_once('Class.Embarcacion.php');
require_once('Class.Amarra.php');


class Movimiento
{
  private int $id;
  private int $id_embarcacion;
  private int $id_amarra;
  private string $fecha_desde;
  private string $fecha_hasta;

  public static function estaEmbarcado($embarcacion)
  {
  }

  public function updateMovimiento()
  {
    try {
      $sentencia = "UPDATE `embarcacion-amarra` SET `id_embarcacion` = (?), `id_amarra` = (?), `fecha_desde` = (?), `fecha_hasta` = (?) 
      WHERE id = $this->id";
      $update = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->id_embarcacion, $this->id_amarra, $this->fecha_desde, date('Y-m-d'));
      $update = $update->execute($datos);

      // Una vez desocupada la embarcacion también desocupamos la amarra y actualizamos la BD
      $amarraADesocupar = Amarra::selectAmarraById($this->id_amarra);
      $amarraADesocupar->setEstado(0);
      $amarraADesocupar->updateAmarra();

      // tengo que pensar si la amarra que estoy pasando por arriba es realmente la que tengo que desocupar o
      // estyo desocupando otra

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectEmbarcado(int $id_embarcacion)
  {
    try {
      $sentencia = "SELECT * FROM `embarcacion-amarra` WHERE id_embarcacion = $id_embarcacion AND fecha_hasta = '0000-00-00'";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectEmbarcados()
  {
    try {
      $sentencia = "SELECT * FROM `embarcacion-amarra` WHERE fecha_hasta = '0000-00-00' ORDER BY id DESC";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectMovimientoById(int $id)
  {
    try {
      $sentencia = "SELECT * FROM `embarcacion-amarra` WHERE id = $id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * @return array<Movimiento>
   */
  public static function selectAllMovimientos(int $id, $embarcacion, $amarra, string $fecha_desde, string $fecha_hasta): array
  {
    try {
      $sentencia = "SELECT * FROM `embarcacion-amarra` ORDER BY id DESC";
      $select = Conexion::getConexion()->query($sentencia);
      $select->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Movimiento", array($id, $embarcacion, $amarra, $fecha_desde, $fecha_hasta));

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }


  public function insertMovimiento()
  {
    try {
      $estaEmbarcado = Movimiento::selectEmbarcado($this->embarcacion->id);
      if ($estaEmbarcado) {
        $this->updateMovimiento();
      }
      $sentencia = "INSERT INTO `embarcacion-amarra` (`id_embarcacion`, `id_amarra`,`fecha_desde`, `fecha_hasta`) VALUES (?,?,?,?)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->id_embarcacion, $this->id_amarra, date("Y-m-d", strtotime("tomorrow")), $this->fecha_hasta);
      $insert = $insert->execute($datos);
      $this->id = Conexion::getConexion()->lastInsertId();

      // ocupamos la amarra
      $amarraAOcupar = Amarra::selectAmarraById($this->id_amarra);
      $amarraAOcupar->setEstado(1);
      $amarraAOcupar->updateAmarra();

      return $insert;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function __construct(int $id, int $id_embarcacion, int $id_amarra, string $fecha_desde, string $fecha_hasta)
  {
    $this->id = $id;
    $this->id_embarcacion = $id_embarcacion;
    $this->id_amarra = $id_amarra;
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
  }

  /**
   * Get the value of id_embarcacion
   */
  public function getId_embarcacion()
  {
    return $this->id_embarcacion;
  }

  /**
   * Get the value of id_amarra
   */
  public function getId_amarra()
  {
    return $this->id_amarra;
  }

  /**
   * Get the value of fecha_desde
   */
  public function getFecha_desde()
  {
    return $this->fecha_desde;
  }

  /**
   * Get the value of fecha_hasta
   */
  public function getFecha_hasta()
  {
    return $this->fecha_hasta;
  }
}
