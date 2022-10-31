<?php

require_once('Class.Conexion.php');
require_once('Class.Embarcacion.php');
require_once('./src/Interfaces/IUpdateCascada.php');

class Cliente implements IUpdateCascada
{
  private int $id;
  private string $apellido_nombre;
  private string $email;
  private int $dni;
  private string $movil;
  private string $domicilio;
  private int $estado;

  public function __construct(int $id, string $apellido_nombre, string $email, int $dni, string $movil, string $domicilio, int $estado)
  {
    $this->id = $id;
    $this->apellido_nombre = $apellido_nombre;
    $this->email = $email;
    $this->dni = $dni;
    $this->movil = $movil;
    $this->domicilio = $domicilio;
    $this->estado = $estado;
  }

  /**
   * Eliminar unn cliente de la base de datos
   * @throws PDOException
   * @return boolean True si se eliminó, false si no se eliminó 
   */
  private function deleteCliente(): bool
  {
    try {
      $sentencia = "DELETE FROM `clientes` WHERE id = (?)";
      $delete = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->id);
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
      $clienteAModificar = Cliente::selectClienteById($this->id);
      // (Si se repite este DNI en la base de datos PERO (&&) el DNI ingresado NO es del cliente a modificar)
      if ($this->seRepiteDni() && $this->dni !== $clienteAModificar->dni) return false;

      $this->updateEnCascada();

      $sentencia = "UPDATE `clientes` SET `apellido_nombre` = (?), `email` = (?), `dni` = (?), `movil` = (?), `domicilio` = (?), `estado` = (?)  
      WHERE id = $this->id";
      $update = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->apellido_nombre, $this->email, $this->dni, $this->movil, $this->domicilio, $this->estado);
      $update = $update->execute($datos);

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * Implementación de IDeleteCascada
   */
  public function updateEnCascada()
  {
    if ($this->estado == 0) {
      $embarcacionesCliente = Embarcacion::selectEmbarcacionesByCliente($this->id);
      // Si hay embarcaciones de este cliente
      foreach ((array) $embarcacionesCliente as $embarcacionActual) {
        $embarcacionActual->setEstado(0);
        $embarcacionActual->updateEmbarcacion();
      }
    }
  }

  /**
   * Se obtiene el cliente que coincida con el id pasado por parámetro. 
   * @param int $id Id del cliente que se desea buscar
   * @throws PDOException
   * @return Cliente Cliente que coincida con el id pasado por parámetro, falso si falla.
   */
  public static function selectClienteById(int $id)
  {
    try {
      $sentencia = "SELECT * FROM `clientes` WHERE id = $id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * Se obtiene el cliente que coincida con el DNI pasado por parámetro. 
   * @param int $dni Dni del cliente que se desea buscar
   * @throws PDOException
   * @return Cliente Cliente que coincida con el DNI pasado por parámetro, falso si falla.
   */
  public function selectClienteByDNI(int $dni)
  {
    try {
      $sentencia = "SELECT * FROM `clientes` WHERE dni = $dni";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * Se obtiene una lista con todos los clientes que tengan el estado pasado por parámetro. 
   * @param int<0,1> $estado Estado del cliente. 0 = baja, 1 = activo
   * @throws PDOException
   * @return array<Cliente> Lista de todos los clientes con estado baja o activo pasado por parámetro, falso si falla.
   */
  public function selectClientesByEstado(int $estado): array
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

  /**
   * Se obtiene una lista con todos los clientes registrados. 
   * @throws PDOException
   * @return array<Cliente> Lista de todos los clientes, falso si falla.
   */
  public static function selectAllClientes(): array
  {
    try {
      $sentencia = "SELECT * FROM `clientes` ORDER BY id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * Crea en la base de datos un nuevo cliente con los datos del objeto Cliente 
   * @throws PDOException
   * @return boolean True si se pudo agregar el cliente a la base de datos, false si no se pudo
   */
  public function insertCliente(): bool
  {
    try {
      // Si el DNI ya existe en la base de datos se devuevle para que no deje ingresar DNI repetido.
      if ($this->seRepiteDni()) return false;

      $sentencia = "INSERT INTO `clientes` (`apellido_nombre`, `email`, `dni`, `movil`, `domicilio`, `estado`) VALUES (?,?,?,?,?,?)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      // Estado 1 porque siempre es activo en el alta
      $datos = array($this->apellido_nombre, $this->email, $this->dni, $this->movil, $this->domicilio, 1);
      $insert = $insert->execute($datos);
      $this->id = Conexion::getConexion()->lastInsertId();

      return $insert;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * Busca si el DNI del objeto Cliente es repetido en la base de datos. Si es repetido devuelve true, si no false
   * @return boolean 
   */
  private function seRepiteDni(): bool
  {
    $clientes = Cliente::selectAllClientes();
    $index = 0;
    $cantClientes = count($clientes);
    $seRepite = false;
    while ($index < $cantClientes && !$seRepite) {
      if ($this->dni === $clientes[$index]->dni) {
        $seRepite = true;
      }
      $index++;
    }

    return $seRepite;
  }


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
