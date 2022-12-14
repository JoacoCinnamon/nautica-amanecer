<?php

class Conexion
{
  private const DB = "nautica_amanecer";
  private const HOST_PORT = "3308";
  private const HOST_NAME = "localhost";
  private const HOST_USER = "root";
  private const HOST_PASS = "";

  /**
   * Summary of pdo
   * @var PDO
   */
  public static ?PDO $pdo = null;

  private function __construct()
  {
  }

  // Singleton instance
  public static function getConexion(): PDO
  {
    try {
      if (self::$pdo == null) {

        $dsn = 'mysql:dbname=' . self::DB . ';port=' . self::HOST_PORT . ',host=' . self::HOST_NAME;
        $options = array(
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // The default fetch mode is PDO::FETCH_OBJ and PDO::FETCH_ASSOC
          PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_STRINGIFY_FETCHES => false
        );

        self::$pdo = new PDO($dsn, self::HOST_USER, self::HOST_PASS, $options);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }

      return self::$pdo;
    } catch (PDOException $e) {
      throw new PDOException("Error al inicializar conexion: " . $e->getMessage());
    }
  }
}
