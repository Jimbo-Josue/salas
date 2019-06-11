<?php

class chats
{
  public static function post($peticion)
  {
    $cuerpo = file_get_contents('php://input');
    $datos = json_decode($cuerpo);

    $idC = $datos->idContacto;
    $pass = $datos->contrasena;

    $resultado = self::crear_chat($idC, $pass);
    if($resultado == self::COMPLETADO)
    {
      http_response_code(200);
      return ["mensaje" => "SE HA CREADO LA CHAT"];
    }
    else
    {
      http_response_code(400);
      return ["mensaje" => "ERROR AL CREAR LA CHAT"];
    }
  }
  
}
