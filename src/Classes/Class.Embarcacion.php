<?php

require_once('Class.Conexion.php');
require_once('Class.Movimiento.php');
require_once('./src/Interfaces/IUpdateCascada.php');

/**
 * Embarcaciones de los distintos clientes.
 */
class Embarcacion implements IUpdateCascada
{
  /**
   * Id de la embarcacion (PK)
   * @var int
   */
  private int $id;

  /**
   * Nombre de la embarcacion
   * @var string
   */
  private string $nombre;

  /**
   * Patente de la embarcacion
   * @var string
   */
  private string $rey;

  /**
   * Id del cliente dueño de la embarcacion
   * @var int
   */
  private int $id_cliente;

  /**
   * Estado de la embarcacion.
   * 1 = activo, 0 = baja
   *
   * @var integer
   */
  private int $estado;


  /**
   * Eliminar una embarcacion de la base de datos
   * @throws PDOException
   * @return boolean True si se eliminó, false si no se eliminó 
   */
  private function deleteEmbarcacion()
  {
    try {
      $sentencia = "DELETE FROM `embarcaciones` WHERE id = (:id)";
      $delete = Conexion::getConexion()->prepare($sentencia);
      $delete = $delete->execute([":id" => $this->id]);

      return $delete;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
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
      if ($this->seRepiteRey() && $this->rey != $embarcacionAModificar->rey) return false;

      $this->updateEnCascada();

      $sentencia = "UPDATE `embarcaciones` SET `nombre` = :nombre, `rey` = :rey, `id_cliente` = :id_cliente, `estado` = :estado WHERE id = $this->id";
      $update = Conexion::getConexion()->prepare($sentencia);
      $update = $update->execute([
        ":nombre" => $this->nombre,
        ":rey" => $this->rey,
        ":id_cliente" => $this->id_cliente,
        ":estado" => $this->estado
      ]);

      return $update;
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Implementación de IDeleteCascada
   * 
   * Se consulta por el estado de la embarcacion, asumiendo que si es 0 (se quiere dar de baja) se debe:
   * -Desocupar la amarra en la que se encontraban la embarcacion
   */
  public function updateEnCascada()
  {
    if ($this->estado == 0) {
      $movimiento = Movimiento::selectEmbarcado($this->id);
      // Si realmente hay un movimiento, por lo tanto esta embarcacion está embarcada
      if ($movimiento) {
        Movimiento::updateMovimiento($movimiento);
      }
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

  /**
   * Se obtiene la embarcacion que coincida con el id pasado por parámetro. 
   * @param int $id Id de la embarcación que se desea buscar
   * @throws PDOException
   * @return Embarcacion Embarcacion que coincida con el id pasado por parámetro, falso si falla.
   */
  public static function selectEmbarcacionById(int $id)
  {
    try {
      $sentencia = "SELECT * FROM `embarcaciones` WHERE id = $id LIMIT 1";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  public static function selectEmbarcacionByRey(string $rey)
  {
    try {
      $sentencia = "SELECT * FROM `embarcaciones` WHERE rey = $rey LIMIT 1";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetch();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  public static function selectEmbarcacionesByEstado(int $estado)
  {
    try {
      // El estado es un numero pasado por parametro, 0 = baja, 1 = activo
      if ($estado == 0 || $estado == 1) {
        $sentencia = "SELECT * FROM `embarcaciones` WHERE estado = $estado ORDER BY id";
        $select = Conexion::getConexion()->query($sentencia);

        return $select->fetchAll();
      }
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  /**
   * Se obtiene una lista con todas las embarcaciones del cliente con el id pasado por parámetro. 
   * @param int $id_cliente Id del cliente del que se desea saber sus embarcaciones
   * @throws PDOException
   * @return array<Embarcacion>|Embarcacion Lista de todas las embarcaciones que tiene el cliente con id pasado por parámetro, falso si falla.
   */
  public static function selectEmbarcacionesByCliente($id_cliente): array|Embarcacion
  {
    try {
      $sentencia = "SELECT * FROM `embarcaciones` WHERE `id_cliente` = $id_cliente";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  public static function selectAllEmbarcaciones()
  {
    try {
      $sentencia = "SELECT * FROM `embarcaciones` ORDER BY id DESC";
      $select = Conexion::getConexion()->query($sentencia);

      return $select->fetchAll();
    } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
      die();
    }
  }

  public function insertEmbarcacion()
  {
    try {
      if ($this->seRepiteRey()) return false;

      $sentencia = "INSERT INTO `embarcaciones` (`nombre`, `rey`, `id_cliente`, `estado`) VALUES (:nombre,:rey,:id_cliente,:estado)";
      $insert = Conexion::getConexion()->prepare($sentencia);
      $insert = $insert->execute([
        ":nombre" => $this->nombre,
        ":rey" => $this->rey,
        ":id_cliente" => $this->id_cliente,
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
   * Busca si hay un REY repetido en la base de datos.
   * @return boolean 
   */
  private function seRepiteRey(): bool
  {
    $embarcaciones = $this->selectAllEmbarcaciones();
    $index = 0;
    $cantEmbarcaciones = count($embarcaciones);
    $seRepite = false;
    while ($index < $cantEmbarcaciones && !$seRepite) {
      if ($this->rey == $embarcaciones[$index]->rey) {
        $seRepite = true;
      }
      $index++;
    }

    return $seRepite;
  }

  public function __construct(int $id, string $nombre, string $rey, int $id_cliente, int $estado)
  {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->rey = $rey;
    $this->id_cliente = $id_cliente;
    $this->estado = $estado;
  }
}
