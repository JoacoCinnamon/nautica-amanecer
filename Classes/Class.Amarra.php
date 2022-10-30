<?php

require_once('./Classes/Class.Conexion.php');

class Amarra
{
  private int $id;
  private int $pasillo;
  private int $estado;

  public function __construct($id, $pasillo, $estado)
  {
    $this->id = $id;
    $this->pasillo = $pasillo;
    $this->estado = $estado;
  }

  public function deleteAmarra(): bool
  {
    try {
      return false;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * Actualiza todo los datos de la amarra en la base de datos
   * @throws PDOException
   * @return boolean True si se actualizó, false si no 
   */
  public function updateAmarra(): bool
  {
    try {
      $sentencia = "UPDATE `amarras` SET `pasillo` = (?), `estado` = (?) WHERE id = (?)";
      $update = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->pasillo, $this->estado, $this->id);
      $update = $update->execute($datos);
      // $estado = ($this->estado == 0)
      //   ? "libre"
      //   : "ocupada";

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
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
      $sentencia = "SELECT * FROM `amarras` WHERE id = $id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
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
      $sentencia = "SELECT * FROM `amarras` WHERE estado = $estado ORDER BY pasillo";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
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
      $sentencia = "SELECT * FROM `amarras` ORDER BY pasillo";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
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
      $sentencia = "INSERT INTO `amarras` (`pasillo`, `estado`) VALUES (?,?)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->pasillo, $this->estado);
      $insert = $insert->execute($datos);
      $this->id = Conexion::getConexion()->lastInsertId();

      return $insert;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  // GetId method

  public function getId()
  {
    return $this->id;
  }

  // GetPasillo method

  public function getPasillo()
  {
    return $this->pasillo;
  }

  // GetEstado method

  public function getEstado()
  {
    return $this->estado;
  }
}
