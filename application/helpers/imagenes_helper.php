<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
ini_set ('gd.jpeg_ignore_warning', 1);
function recortarImagen($origen,$destino,$extension,$x,$y,$ancho,$alto,$calidad)
{
  if($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG'){
	$img_r = @imagecreatefromjpeg($origen);
	}
	if($extension == 'png' || $extension == 'PNG'){
	$img_r = @imagecreatefrompng($origen);
	}

	$dst_r = @ImageCreateTrueColor( $ancho, $alto );

	@imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$ancho,$alto,$ancho,$alto);

	@imagejpeg($dst_r,$destino,$calidad);
}
function resizeImagen($rutaImagenOriginal, $alto, $ancho,$nombreN,$extension,$calidad=90){

    $img_original = "";
    if($extension == 'GIF' || $extension == 'gif'){
    $img_original = imagecreatefromgif($rutaImagenOriginal);
    }
    if($extension == 'jpg' || $extension == 'JPG'){
    $img_original = @imagecreatefromjpeg($rutaImagenOriginal);
    }
    if($extension == 'png' || $extension == 'PNG'){
    $img_original = @imagecreatefrompng($rutaImagenOriginal);
    }
    $max_ancho = $ancho;
    $max_alto = $alto;
    list($ancho,$alto)=getimagesize($rutaImagenOriginal);
    $x_ratio = $max_ancho / $ancho;
    $y_ratio = $max_alto / $alto;
    if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){//Si ancho
  	$ancho_final = $ancho;
		$alto_final = $alto;
	} elseif (($x_ratio * $alto) < $max_alto){
		$alto_final = ceil($x_ratio * $alto);
		$ancho_final = $max_ancho;
	} else{
		$ancho_final = ceil($y_ratio * $ancho);
		$alto_final = $max_alto;
	}
    $tmp=imagecreatetruecolor($ancho_final,$alto_final);
    if($img_original!="")
    {
      imagecopyresampled($tmp,$img_original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
      imagedestroy($img_original);
      if(imagejpeg($tmp,$nombreN,$calidad))
        return true;
      else return false;
    }
    else return false;


}

function dimensionar_imagen($rutaImagenOriginal,$max_ancho=900,$max_alto=600){

  $img_original = @imagecreatefromjpeg($rutaImagenOriginal);
  //Ancho y alto de la imagen original
  list($ancho, $alto) = getimagesize($rutaImagenOriginal);
  //Se calcula ancho y alto de la imagen final
  $x_ratio = $max_ancho / $ancho;
  $y_ratio = $max_alto / $alto;
  //Si el ancho y el alto de la imagen no superan los maximos,
  //ancho final y alto final son los que tiene actualmente
  if (($ancho <= $max_ancho) && ($alto <= $max_alto)) {//Si ancho
      $ancho_final = $ancho;
      $alto_final = $alto;
  }
  /*
   * si proporcion horizontal*alto mayor que el alto maximo,
   * alto final es alto por la proporcion horizontal
   * es decir, le quitamos al ancho, la misma proporcion que
   * le quitamos al alto
   *
   */ elseif (($x_ratio * $alto) < $max_alto) {
      $alto_final = ceil($x_ratio * $alto);
      $ancho_final = $max_ancho;
  }
  /*
   * Igual que antes pero a la inversa
   */ else {
      $ancho_final = ceil($y_ratio * $ancho);
      $alto_final = $max_alto;
  }
  //Creamos una imagen en blanco de tama�o $ancho_final  por $alto_final .
  $tmp = imagecreatetruecolor($ancho_final, $alto_final);

  //Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp)
  imagecopyresampled($tmp, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);

  //Se destruye variable $img_original para liberar memoria
  imagedestroy($img_original);

  return $tmp;

}
