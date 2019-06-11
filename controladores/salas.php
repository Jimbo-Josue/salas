<?php

class salas
{
  const COMPLETADO = 1;
  const ERROR = 2;

  public static function post($peticion)
  {
    $cuerpo = file_get_contents('php://input');
    $datos = json_decode($cuerpo);
    
    $idC = $datos->idContacto;
    $pass = $datos->contrasena;

    $resultado = self::crear_sala($idC, $pass);
    if($resultado == self::COMPLETADO)
    {
      http_response_code(200);
      return ["mensaje" => "SE HA CREADO LA SALA"];
    }
    else
    {
      http_response_code(400);
      return ["mensaje" => "ERROR AL CREAR LA SALA"];
    }
  }

  private function crear_sala($idC, $pass)
  {
    $idUsuario = usuarios::autorizar();

    $cmd = "SELECT COUNT(*) FROM contacto WHERE idContacto=".$idC.
           " AND idUsuario=".$idUsuario;

    $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($cmd);
    $sentencia->execute();
    $ret = $sentencia->fetchColumn();

    if($ret > 0)
    {
      $cmd = "SELECT COUNT(*) FROM salas WHERE idContacto=".$idC.
             " AND idUsuario=".$idUsuario;
  
      $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($cmd);
      $sentencia->execute();
      $ret = $sentencia->fetchColumn();
      if($ret == 0)
      {
        $cmd = "INSERT INTO salas VALUES(null,?,?,?)";
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sentencia = $pdo->prepare($cmd);

        $sentencia->bindParam(1, $idUsuario);
        $sentencia->bindParam(2, $idC);
        $sentencia->bindParam(3, $pass);

        if($sentencia->execute())
          return self::COMPLETADO;
        else
          return self::ERROR;
      }
      else
      {
        return self::ERROR;
      }
    }
    else
    {
      return self::ERROR;
    }
  }

}

