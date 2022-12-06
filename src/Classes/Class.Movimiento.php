<?php

require_once('Class.Conexion.php');
require_once('Class.Embarcacion.php');
require_once('Class.Amarra.php');
require_once('./src/Interfaces/IUpdateCascada.php');

/**
 * Movimiento de las embarcaciones y amarras.
 */
class Movimiento
{
  /**
   * Id del Movimiento (PK).
   *
   * @var integer
   */
  private int $id;

  /**
   * Id de la embarcacion.
   *
   * @var integer
   */
  private int $id_embarcacion;

  /**
   * Id de la amarra.
   *
   * @var integer
   */
  private int $id_amarra;

  /**
   * Fecha de desde cuándo la embarcación está ocupando la amarra.
   *
   * @var string
   */
  private string $fecha_desde;

  /**
   * Fecha en la que se desocupo la amarra junto a la embarcación.
   *
   * @var string
   */
  private string $fecha_hasta;

  private static function deleteMovimiento($movimiento)
  {
    try {
      $sentencia = "DELETE FROM `embarcacion-amarra` WHERE id = (:id)";
      $delete = Conexion::getConexion()->prepare($sentencia);
      $delete = $delete->execute([":id" => $movimiento->id]);

      return $delete;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  public static function updateMovimiento($movimiento)
  {
    try {
      $sentencia = "UPDATE `embarcacion-amarra`
      SET `id_embarcacion` = :id_embarcacion, `id_amarra` = :id_amarra, `fecha_desde` = :fecha_desde, `fecha_hasta` = :fecha_hasta
      WHERE id = $movimiento->id";
      $update = Conexion::getConexion()->prepare($sentencia);
      $update = $update->execute([
        ":id_embarcacion" => $movimiento->id_embarcacion,
        ":id_amarra" => $movimiento->id_amarra,
        ":fecha_desde" => $movimiento->fecha_desde,
        ":fecha_hasta" => date('Y-m-d', strtotime("today"))
      ]);

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Consulta a la DB con el id de la embarcacion pasado por parámetro para obt 
   *
   * @param integer $id_embarcacion Id de la embarcacion que deseamos saber desde cuando y donde está embarcada
   * @return Movimiento|boolean
   */
  public static function selectEmbarcado(int $id_embarcacion)
  {
    try {
      $sentencia = "SELECT * FROM `embarcacion-amarra` WHERE `id_embarcacion` = $id_embarcacion AND `fecha_hasta` = '0000-00-00' LIMIT 1";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  public static function selectEmbarcados()
  {
    try {
      $sentencia = "SELECT * FROM `embarcacion-amarra` WHERE `fecha_hasta` = '0000-00-00'";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  public static function selectMovimientoById(int $id)
  {
    try {
      $sentencia = "SELECT * FROM `embarcacion-amarra` WHERE id = $id LIMIT 1";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * @return array<Movimiento>
   */
  public static function selectAllMovimientos(): array
  {
    try {
      $sentencia = "SELECT * FROM `embarcacion-amarra` ORDER BY fecha_desde DESC, fecha_hasta ASC"; // ID DESC
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }


  public function insertMovimiento()
  {
    try {
      $movimiento = Movimiento::selectEmbarcado($this->id_embarcacion);

      // Si está embarcado (osea trae un registro) y se quiere agregar otro movimiento del mismo barco significa que lo quiere mover
      // asi que tenemos que desocupar la antigua amarra y actualizar la fecha_hasta 
      if ($movimiento) {
        Movimiento::updateMovimiento($movimiento);
      }
      $sentencia = "INSERT INTO `embarcacion-amarra` (`id_embarcacion`, `id_amarra`,`fecha_desde`, `fecha_hasta`) VALUES (:id_embarcacion,:id_amarra,:fecha_desde,:fecha_hasta)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      $insert = $insert->execute([
        ":id_embarcacion" => $this->id_embarcacion,
        ":id_amarra" => $this->id_amarra,
        ":fecha_desde" => date("Y-m-d", strtotime("+1 day")),
        ":fecha_hasta" => $this->fecha_hasta
      ]);
      $this->id = Conexion::getConexion()->lastInsertId();

      // Ocupamos la amarra
      $amarraAOcupar = Amarra::selectAmarraById($this->id_amarra);
      $amarraAOcupar = new Amarra(
        $amarraAOcupar->id,
        $amarraAOcupar->pasillo,
        $amarraAOcupar->estado
      );
      $amarraAOcupar->setEstado(1);
      $amarraAOcupar->updateAmarra();

      return $insert;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
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
}
