<?php

require_once('Class.Conexion.php');
require_once('Class.Embarcacion.php');
require_once('./src/Interfaces/IUpdateCascada.php');

/**
 * Clientes del sistema.
 */
class Cliente implements IUpdateCascada
{
  private int $id;

  private string $apellido_nombre;

  private string $email;

  /**
   * Campo único
   */
  private int $dni;

  private string $movil;

  private string $domicilio;

  /**
   * Estado del cliente.
   * 1 = activo, 0 = baja
   */
  private int $estado;

  /**
   * Eliminar un cliente de la base de datos
   * @throws PDOException
   * @return boolean
   */
  public function deleteCliente()
  {
    try {
      $sentencia = "DELETE FROM `clientes` WHERE id = :id";
      $delete = Conexion::getConexion()->prepare($sentencia);
      $delete = $delete->execute([":id" => $this->id]);

      return $delete;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Actualizar un cliente de la base de datos
   * @return bool En principio devuelve true/false
   */
  public function updateCliente()
  {
    try {
      $clienteAModificar = Cliente::selectClienteById($this->id);
      // (Si se repite este DNI en la base de datos PERO (&&) el DNI ingresado NO es del cliente a modificar)
      if ($this->seRepiteDni() && $this->dni != $clienteAModificar->dni) return false;

      $this->updateEnCascada();

      $sentencia =
        "UPDATE `clientes` 
        SET `apellido_nombre` = :apellido_nombre, `email` = :email, `dni` = :dni, `movil` = :movil, `domicilio` = :domicilio, `estado` = :estado
        WHERE id = $this->id";
      $update = Conexion::getConexion()->prepare($sentencia);
      $update = $update->execute([
        ":apellido_nombre" => $this->apellido_nombre,
        ":email" => $this->email,
        ":dni" => $this->dni,
        ":movil" => $this->movil,
        ":domicilio" => $this->domicilio,
        ":estado" => $this->estado
      ]);

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * @inheritDoc
   * 
   * Implementación de IDeleteCascada
   * 
   * Se consulta por el estado del cliente, asumiendo que si es 0 (se quiere dar de baja) se debe:
   * -Dar de baja al cliente y la/las embarcaciones a su nombre
   * 
   * @return void
   */
  public function updateEnCascada()
  {
    if ($this->estado == 0) {
      $embarcacionesCliente = Embarcacion::selectEmbarcacionesByCliente($this->id);
      // Si hay embarcaciones de este cliente
      foreach ((array) $embarcacionesCliente as $embarcacionActual) {
        $embarcacionActual = new Embarcacion(
          $embarcacionActual->id,
          $embarcacionActual->nombre,
          $embarcacionActual->rey,
          $embarcacionActual->id_cliente,
          $embarcacionActual->estado
        );
        $embarcacionActual->setEstado(0);
        $embarcacionActual->updateEmbarcacion();
      }
    }
  }

  /**
   * Se obtiene el cliente que coincida con el id pasado por parámetro. 
   * @param int $id Id del cliente que se desea buscar
   * @throws PDOException
   * @return Cliente|boolean Cliente que coincida con el id pasado por parámetro, falso si falla.
   */
  public static function selectClienteById(int $id)
  {
    try {
      $sentencia = "SELECT * FROM `clientes` WHERE id = $id LIMIT 1";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Se obtiene el cliente que coincida con el DNI pasado por parámetro. 
   * @param int $dni Dni del cliente que se desea buscar
   * @throws PDOException
   * @return Cliente|boolean Cliente que coincida con el DNI pasado por parámetro, falso si falla.
   */
  public function selectClienteByDNI(int $dni)
  {
    try {
      $sentencia = "SELECT * FROM `clientes` WHERE dni = $dni LIMIT 1";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Se obtiene una lista con todos los clientes que tengan el estado pasado por parámetro. 
   * @param int<0,1> $estado Estado del cliente. 0 = baja, 1 = activo
   * @throws PDOException
   * @return array<Cliente>|boolean Lista de todos los clientes con estado baja o activo pasado por parámetro, falso si falla.
   */
  public static function selectClientesByEstado(int $estado): array
  {
    try {
      if ($estado == 0 || $estado == 1) {
        $sentencia = "SELECT * FROM `clientes` WHERE estado = $estado ORDER BY id";
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
   * Se obtiene una lista con todos los clientes registrados. 
   * @throws PDOException
   * @return array<Cliente>|boolean Lista de todos los clientes, falso si falla.
   */
  public static function selectAllClientes(): array
  {
    try {
      $sentencia = "SELECT * FROM `clientes` ORDER BY id DESC";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
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

      $sentencia =
        "INSERT INTO `clientes` (`apellido_nombre`, `email`, `dni`, `movil`, `domicilio`, `estado`) VALUES (:apellido_nombre,:email,:dni,:movil,:domicilio,:estado)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      $insert = $insert->execute([
        ":apellido_nombre" => $this->apellido_nombre,
        ":email" => $this->email,
        ":dni" => $this->dni,
        ":movil" => $this->movil,
        ":domicilio" => $this->domicilio,
        ":estado" => $this->estado
      ]);
      $this->id = Conexion::getConexion()->lastInsertId();

      return $insert;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Busca si el DNI del objeto Cliente es repetido en la base de datos. 
   * @return boolean 
   */
  private function seRepiteDni(): bool
  {
    $clientes = Cliente::selectAllClientes();
    $index = 0;
    $cantClientes = count($clientes);
    $seRepite = false;
    while ($index < $cantClientes && !$seRepite) {
      if ($this->dni == $clientes[$index]->dni) {
        $seRepite = true;
      }
      $index++;
    }

    return $seRepite;
  }

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
}
