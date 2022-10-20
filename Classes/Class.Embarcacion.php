<?php

require_once('./Classes/Class.Conexion.php');

class Embarcacion
{
    private $id;
    private $nombre;
    private $rey; // como una patente
    private $id_cliente;
    private $estado;

    public static function selectEmbarcacionByEstado($estado)
    {
        try {
            // El estado es un numero pasado por parametro, 0 = baja, 1 = activo
            $sentencia = "SELECT * FROM `embarcacion` WHERE estado = $estado ORDER BY id";
            $select = Conexion::getConexion()->query($sentencia);

            return $select->fetchAll();
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }

    public static function selectEmbarcacionByRey($rey)
    {
        try {
            $sentencia = "SELECT * FROM `embarcacion` WHERE rey = $rey";
            $select = Conexion::getConexion()->query($sentencia);

            return $select->fetch();
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }

    public static function selectEmbarcacionById($id)
    {
        try {
            $sentencia = "SELECT * FROM `embarcacion` WHERE id = $id";
            $select = Conexion::getConexion()->query($sentencia);

            return $select->fetch();
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }

    public static function selectAllEmbarcaciones()
    {
        try {
            $sentencia = "SELECT * FROM `embarcacion` ORDER BY id";
            $select = Conexion::getConexion()->query($sentencia);

            return $select->fetchAll();
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }

    public function insertEmbarcacion()
    {
        try {
            if ($this->seRepiteRey()) {
                $reyRepetido = $this->rey;
                return $reyRepetido;
            }
            $sentencia = "INSERT INTO `embarcaciones` (`nombre`, `rey`, `id_cliente`, `estado`) VALUES (?,?,?,?)";
            $insert = Conexion::getConexion()->prepare($sentencia);
            // El 1 en estado es porque está activo
            $datos = array($this->nombre, $this->rey, $this->id_cliente, 1);
            $insert = $insert->execute($datos);
            $this->id = Conexion::getConexion()->lastInsertId();

            // return $this->id;
            return "Se ha ingresado correctamente a $this->nombre";
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }

    private function seRepiteRey()
    {
        $embarcaciones = $this->selectAllEmbarcaciones();
        $index = 0;
        $cantEmbarcaciones = count($embarcaciones);
        $seRepite = false;
        while ($index < $cantEmbarcaciones && !$seRepite) {
            if ($this->rey === $embarcaciones[$index]->rey) {
                $seRepite = $embarcaciones[$index]->nombre;
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
