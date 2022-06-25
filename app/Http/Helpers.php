<?php

namespace App\Http;

use Illuminate\Support\Facades\Storage;

class Helpers {
   public function strClean(string $strCadena = '')
   {
      $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
      $string = trim($string); //Elimina espacios en blanco al inicio y al final
      $string = stripslashes($string); // Elimina las \ invertidas
      $string = str_ireplace("<script>", "", $string);
      $string = str_ireplace("</script>", "", $string);
      $string = str_ireplace("<script src>", "", $string);
      $string = str_ireplace("<script type=>", "", $string);
      $string = str_ireplace("SELECT * FROM", "", $string);
      $string = str_ireplace("DELETE FROM", "", $string);
      $string = str_ireplace("INSERT INTO", "", $string);
      $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
      $string = str_ireplace("DROP TABLE", "", $string);
      $string = str_ireplace("OR '1'='1", "", $string);
      $string = str_ireplace('OR "1"="1"', "", $string);
      $string = str_ireplace('OR ´1´=´1´', "", $string);
      $string = str_ireplace("is NULL; --", "", $string);
      $string = str_ireplace("is NULL; --", "", $string);
      $string = str_ireplace("LIKE '", "", $string);
      $string = str_ireplace('LIKE "', "", $string);
      $string = str_ireplace("LIKE ´", "", $string);
      $string = str_ireplace("OR 'a'='a", "", $string);
      $string = str_ireplace('OR "a"="a', "", $string);
      $string = str_ireplace("OR ´a´=´a", "", $string);
      $string = str_ireplace("OR ´a´=´a", "", $string);
      $string = str_ireplace("--", "", $string);
      $string = str_ireplace("^", "", $string);
      $string = str_ireplace("[", "", $string);
      $string = str_ireplace("]", "", $string);
      $string = str_ireplace("==", "", $string);
      return trim($string);
   }

   public function respuestaJson($data, int $status = 200){
      return response(json_encode($data), $status)
      ->header('Content-Type', 'application/json');
   }

   public function dateCurrent()
   {
      date_default_timezone_set("America/Bogota");
      return date("Y-m-d H:i:s");
   }

   public function procesarImagen($img)
   {
      $direction =  '../public/img';
      $partes = explode(";base64,", $img);
      $extencion = explode("/", mime_content_type($img))[1];
      $imagen_base64 = base64_decode($partes[1]);
      $file_ = uniqid() . '.' . $extencion;
      $file = $direction . '/'. $file_;
      file_put_contents($file, $imagen_base64);

      return $file_;
   }
}

?>