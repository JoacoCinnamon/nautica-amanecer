<?php

require_once('Class.Conexion.php');

/**
 * Amarra donde se almacenan embarcaciones.
 */
class Amarra
{
  /**
   * Id de la amarra (PK).
   *
   * @var integer
   */
  private int $id;

  /**
   * Pasillo en el que se encuentra la amarra.
   *
   * @var integer
   */
  private int $pasillo;

  /**
   * Estado actual de la amarra.
   * 0 = libre, 1 = ocupada 
   *
   * @var integer
   */
  private int $estado;


  private function deleteAmarra()
  {
    try {
      return false;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Actualiza todo los datos de la amarra en la base de datos
   * @throws PDOException
   * @return boolean True si se actualizó el cliente a la base de datos, false si no se pudo
   */
  public function updateAmarra(): bool
  {
    try {
      $sentencia = "UPDATE `amarras` SET `pasillo` = :pasillo, `estado` = :estado WHERE `id` = $this->id";
      $update = Conexion::getConexion()->prepare($sentencia);
      $update = $update->execute([
        ":pasillo" => $this->pasillo,
        ":estado" => $this->estado
      ]);

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }


  /**
   * Metodo Set para ocupar/desocupar específicamente la amarra
   * para por ejemplo cuando el cliente quiere darse de baja, llevándose todas sus embarcaciones
   */
  public function setEstado(int $estado)
  {
    if ($estado == 0 || $estado == 1) {
      $this->estado = $estado;
    }
  }

  /**
   * Se obtiene la amarra que coincida con el id pasado por parámetro. 
   * @param int $id Id de la amarra que queremos buscar
   * @throws PDOException
   * @return Amarra Amarra que coincida con el id pasado por parámetro, falso si falla.
   */
  public static function selectAmarraById(int $id)
  {
    try {
      $sentencia = "SELECT * FROM `amarras` WHERE id = $id LIMIT 1";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Se obtiene la amarra que coincida con el pasillo pasado por parámetro. 
   * @param int $pasillo Pasillos que queremos buscar
   * @throws PDOException
   * @return array<Amarra> Array de amarras que coincidan con el pasillo pasado por parámetro, falso si falla.
   */
  public static function selectAmarrasByPasillo(int $pasillo): array
  {
    try {
      $sentencia = "SELECT * FROM `amarras` WHERE `pasillo` = $pasillo";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * @param int<0, 1> $estado El estado de la amarra. 0 = libre 1 = ocupado
   * @throws PDOException
   * @return array<Amarra> Array de amarras que coincidan con el estado pasado por parámetro, falso si falla.
   * Amarras[] == array<Amarra>
   */
  public static function selectAmarrasByEstado(int $estado): array
  {
    try {
      if ($estado == 0 || $estado == 1) {
        $sentencia = "SELECT * FROM `amarras` WHERE `estado` = $estado ORDER BY pasillo";
        $select = Conexion::getConexion()->query($sentencia);

        return $select->fetchAll();
      }
      return [];
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Se obtiene una lista con todas las amarras registrados. 
   * @throws PDOException
   * @return array<Amarra> Lista de todas las amarras, falso si falla.
   */
  public static function selectAllAmarras(): array
  {
    try {
      $sentencia = "SELECT * FROM `amarras` ORDER BY id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Crea en la base de datos una nueva amarra con los datos del objeto Amarra
   * @throws PDOException
   * @return boolean True si se pudo agregar el cliente a la base de datos, false si no se pudo
   */
  public function insertAmarra(): bool
  {
    try {
      $sentencia = "INSERT INTO `amarras` (`pasillo`, `estado`) VALUES (:pasillo,:estado)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      $insert = $insert->execute([
        ":pasillo" => $this->pasillo,
        ":estado" => $this->estado
      ]);
      $this->id = Conexion::getConexion()->lastInsertId();

      return $insert;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  public function __construct($id, $pasillo, $estado)
  {
    $this->id = $id;
    $this->pasillo = $pasillo;
    $this->estado = $estado;
  }
}
