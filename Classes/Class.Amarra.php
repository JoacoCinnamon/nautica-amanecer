<?php

require_once('./Classes/Class.Conexion.php');

class Amarra
{
  private $id;
  private $pasillo;
  private $estado;

  public function __construct($id, $pasillo, $estado)
  {
    $this->id = $id;
    $this->pasillo = $pasillo;
    $this->estado = $estado;
  }

  public function deleteAmarra()
  {
    try {

      // Tiene que poder borrar de la base de datos amarras? 
      // Porque no tiene un estado de activa o inactiva, sino un estado fisico de si está ocupada o no

      return false;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function updateAmarra()
  {
    try {
      $sentencia = "UPDATE `amarras` SET `pasillo` = (?), `estado` = (?) WHERE id = (?)";
      $update = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->pasillo, $this->estado, $this->id);
      $update = $update->execute($datos);
      $estado = ($this->estado == 0)
        ? "libre"
        : "ocupada";

      $message = "Se ha actualizado correctamente la amarra N°$this->id, pasillo $this->pasillo y está $estado";

      return $message;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function selectAmarraById()
  {
    try {
      $sentencia = "SELECT * FROM `amarras` WHERE id = $this->id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function selectAmarrasByPasillo()
  {
    try {
      $sentencia = "SELECT * FROM `amarras` WHERE `pasillo` = $this->pasillo";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function selectAmarrasByEstado($estado)
  {
    try {
      // El estado es un numero pasado por parametro, 0 = libre, 1 = ocupada
      $sentencia = "SELECT * FROM `amarras` WHERE estado = $estado ORDER BY pasillo";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function selectAllAmarras()
  {
    try {
      $sentencia = "SELECT * FROM `amarras` ORDER BY pasillo";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function insertAmarra()
  {
    try {
      $sentencia = "INSERT INTO `amarras` (`pasillo`, `estado`) VALUES (?,?)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->pasillo, $this->estado);
      $insert = $insert->execute($datos);
      $this->id = Conexion::getConexion()->lastInsertId();

      // return $this->id;
      return "Se ha ingresado correctamente la amarra N°$this->id y pasillo $this->pasillo";
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
