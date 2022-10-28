<?php

require_once('./Classes/Class.Conexion.php');


class Cliente
{
  private $id;
  private $apellido_nombre;
  private $email;
  private $dni;
  private $movil;
  private $domicilio;
  private $estado;

  public function __construct($id, $apellido_nombre, $email, $dni, $movil, $domicilio, $estado)
  {
    $this->id = $id;
    $this->apellido_nombre = $apellido_nombre;
    $this->email = $email;
    $this->dni = $dni;
    $this->movil = $movil;
    $this->domicilio = $domicilio;
    $this->estado = $estado;
  }

  public function deleteCliente()
  {
    try {

      $sentencia = "UPDATE `clientes` SET `estado` = (?) WHERE id = (?)";
      $delete = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->estado, $this->id);
      $delete = $delete->execute($datos);

      return $delete;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }


  /**
   * SE DEBEN DAR DE BAJA LAS EMBARCACIONES TAMBIÉN Y DESOCUPAR LAS RESPECTIVAS AMARRAS
   */
  public function updateCliente()
  {
    try {
      if ($this->seRepiteDni()) {
        $dniRepetido = $this->dni;
        return $dniRepetido;
      }
      $sentencia = "UPDATE clientes SET `apellido_nombre`= (?), `email`= (?), `dni` = (?), `movil` = (?), `domicilio` = (?), `estado` = (?)  
      WHERE id = (?)";
      $update = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->apellido_nombre, $this->email, $this->dni, $this->movil, $this->domicilio, $this->estado, $this->id);
      $update = $update->execute($datos);

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectClienteById($id)
  {
    try {
      $sentencia = "SELECT * FROM `clientes` WHERE id = $id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function selectClientesByDNI()
  {
    try {
      $sentencia = "SELECT * FROM `clientes` WHERE dni = $this->dni";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function selectClientesByEstado($estado)
  {
    try {
      // El estado es un numero pasado por parametro, 0 = baja, 1 = activo
      $sentencia = "SELECT * FROM `clientes` WHERE estado = $estado ORDER BY id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectAllClientes()
  {
    try {
      $sentencia = "SELECT * FROM `clientes` ORDER BY id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function insertCliente()
  {
    try {
      if ($this->seRepiteDni()) {
        $dniRepetido = $this->dni;
        return $dniRepetido;
      }
      $sentencia = "INSERT INTO `clientes` (`apellido_nombre`, `email`, `dni`, `movil`, `domicilio`, `estado`) VALUES (?,?,?,?,?,?)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      // El 1 en estado es porque esta activo
      $datos = array($this->apellido_nombre, $this->email, $this->dni, $this->movil, $this->domicilio, 1);
      $insert = $insert->execute($datos);
      $this->id = Conexion::getConexion()->lastInsertId();

      // return $this->id;
      return "Se ha ingresado correctamente a $this->apellido_nombre";
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  private function seRepiteDni()
  {
    $clientes = $this->selectAllClientes();
    $index = 0;
    $cantClientes = count($clientes);
    $seRepite = false;
    while ($index < $cantClientes && !$seRepite) {
      if ($this->dni === $clientes[$index]->dni) {
        $seRepite = $clientes[$index]->apellido_nombre;
      }
      $index++;
    }

    return $seRepite;
  }

  // public function getEstadoToString()
  // {
  //   $estado = ($this->estado === 1)
  //     ? "Activo"
  //     : "Baja";
  //   return $estado;
  // }

  public function getId()
  {
    return $this->id;
  }

  public function getApellidoNombre()
  {
    return $this->apellido_nombre;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getDni()
  {
    return $this->dni;
  }

  public function getMovil()
  {
    return $this->movil;
  }

  public function getDomicilio()
  {
    return $this->domicilio;
  }
}
