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

      // Cuándo no lo puedo dar de baja??? Cuándo no hay embarcaciones a su nombre?

      $sentencia = "UPDATE `clientes` SET `estado` = (?) WHERE id = (?)";
      $delete = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->estado, $this->id);
      $delete = $delete->execute($datos);

      return $delete;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function updateCliente()
  {
    try {

      if ($this->seRepiteDni()) {
        $dniRepetido = $this->dni;
        return $dniRepetido;
      }
      // La idea es que lo pueda dar de baja desde acá?
      $sentencia = "UPDATE `clientes` 
      SET `apellido_nombre`= (?),`dni`= (?),`domicilio`= (?),`movil`= (?),`email`= (?),`estado`= (?) 
      WHERE id = $this->id";
      $update = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->apellido_nombre, $this->dni, $this->domicilio, $this->movil, $this->email, $this->estado);
      $update = $update->execute($datos);

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function selectClienteById()
  {
    try {
      $sentencia = "SELECT * FROM `clientes` WHERE id = $this->id";
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

  public function selectAllClientes()
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
        $seRepite = $clientes[$index]->nombre;
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
