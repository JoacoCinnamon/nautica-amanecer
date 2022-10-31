<?php

require_once('Class.Conexion.php');
require_once('./src/Interfaces/IUpdateCascada.php');

class Embarcacion implements IUpdateCascada
{
  private int $id;
  private string $nombre;
  private string $rey; // como una patente
  private int $id_cliente;
  private int $estado;

  /**
   * Eliminar una embarcacion de la base de datos
   * @throws PDOException
   * @return boolean True si se eliminó, false si no se eliminó 
   */
  private function deleteEmbarcacion(): bool
  {
    try {
      $sentencia = "DELETE FROM `embarcaciones` WHERE id = (?)";
      $delete = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->id);
      $delete = $delete->execute($datos);

      return $delete;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * SE DEBE DAR DE BAJA LA EMBARCACION Y DESOCUPAR LA RESPECTIVA AMARRA
   */
  public function updateEmbarcacion()
  {
    try {
      $embarcacionAModificar = Embarcacion::selectEmbarcacionById($this->id);
      // (Si se repite este REY en la base de datos PERO (&&) el REY ingresado NO es de la embarcación a modificar)
      if ($this->seRepiteRey() && $this->rey !== $embarcacionAModificar->rey) return false;

      $this->updateEnCascada();

      $sentencia = "UPDATE `embarcaciones` SET `nombre` = (?), `rey` = (?), `id_cliente` = (?), `estado` = (?) WHERE id = $this->id";
      $update = Conexion::getConexion()->prepare($sentencia);
      $datos = array($this->nombre, $this->rey, $this->id_cliente, $this->estado);
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
      /**  
       * Si el estado es == 0 significa que se dio de baja la embarcación, por lo tanto también debería de
       * darse de baja en la amarra en la que se encontraba (movimiento)
       */
    }
  }

  /**
   * Metodo Set para dar de baja (o de alta si se quisiese) específicamente la embarcación
   * para cuando el cliente quiere darse de baja, llevándose todas sus embarcaciones
   */
  public function setEstado(int $estado)
  {
    if ($estado == 0 || $estado == 1) {
      $this->estado = $estado;
    }
  }

  public static function selectEmbarcacionById(int $id)
  {
    try {
      $sentencia = "SELECT * FROM `embarcaciones` WHERE id = $id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectEmbarcacionByRey(string $rey)
  {
    try {
      $sentencia = "SELECT * FROM `embarcaciones` WHERE rey = $rey";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectEmbarcacionesByEstado(int $estado)
  {
    try {
      // El estado es un numero pasado por parametro, 0 = baja, 1 = activo
      $sentencia = "SELECT * FROM `embarcaciones` WHERE estado = $estado ORDER BY id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectEmbarcacionesByCliente(int $id_cliente)
  {
    try {
      $sentencia = "SELECT * FROM `embarcaciones` WHERE id_cliente = $id_cliente";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public static function selectAllEmbarcaciones()
  {
    try {
      $sentencia = "SELECT * FROM `embarcaciones` ORDER BY id";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  public function insertEmbarcacion(): bool
  {
    try {
      if ($this->seRepiteRey()) return false;

      $sentencia = "INSERT INTO `embarcaciones` (`nombre`, `rey`, `id_cliente`, `estado`) VALUES (?,?,?,?)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      // El 1 en estado es porque está activo
      $datos = array($this->nombre, $this->rey, $this->id_cliente, 1);
      $insert = $insert->execute($datos);
      $this->id = Conexion::getConexion()->lastInsertId();

      return $insert;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
    }
  }

  /**
   * Busca si hay un REY repetido en la base de datos. Si es repetido devuelve true, si no false
   * @return boolean 
   */
  private function seRepiteRey(): bool
  {
    $embarcaciones = $this->selectAllEmbarcaciones();
    $index = 0;
    $cantEmbarcaciones = count($embarcaciones);
    $seRepite = false;
    while ($index < $cantEmbarcaciones && !$seRepite) {
      if ($this->rey === $embarcaciones[$index]->rey) {
        $seRepite = true;
      }
      $index++;
    }

    return $seRepite;
  }

  /**
   * Get the value of id
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Get the value of nombre
   */
  public function getNombre()
  {
    return $this->nombre;
  }

  /**
   * Get the value of rey
   */
  public function getRey()
  {
    return $this->rey;
  }

  /**
   * Get the value of id_cliente
   */
  public function getId_cliente()
  {
    return $this->id_cliente;
  }

  /**
   * Get the value of estado
   */
  public function getEstado()
  {
    return $this->estado;
  }
}