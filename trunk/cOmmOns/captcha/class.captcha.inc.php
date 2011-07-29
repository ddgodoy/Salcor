<?php
	class clsCaptcha{
	  var $image_width = 170;
	  var $image_height= 40;
	  var $code_length = 5;
	  var $ttf_file    = '';
	  var $font_size   = 20;
	  var $text_x_start= 9;
	  var $text_angle_minimum   = -20;
	  var $text_angle_maximum   = 20;
	  var $text_minimum_distance= 30;
	  var $text_maximum_distance= 33;
	  var $image_bg_color = array("red" => 244, "green" => 245, "blue" => 236);
	  var $text_color = array("red" => 88, "green" => 100, "blue" => 105);
	  var $shadow_text= false;
	  var $use_transparent_text = true;
	  var $text_transparency_percentage = 15;
	  var $draw_lines   = TRUE;
	  var $line_color   = array("red" => 220, "green" =>220, "blue" => 220);
	  var $line_distance= 12;
	  var $draw_angled_lines = TRUE;
	  var $draw_lines_over_text = true;
	  var $data_directory = '';
	  var $prune_minimum_age = 15;
	  var $hash_salt = "fg7hg3yg3fd90oi4i";
	
	  var $im;
	  var $bgimg;
	  var $code;
	  var $code_entered;
	  var $correct_code;  
//--------------------------------------------------------------------
	  function setFuente($valor){
	    $this->ttf_file = $valor;
	  }
	  function setDirectorio($valor){
	    $this->data_directory = $valor;
	  }
//--------------------------------------------------------------------
	  function show($background_image = ""){
	    if($background_image != "" && is_readable($background_image)){
	      $this->bgimg = $background_image;
	    }
	    $this->doImage();
	  }
	  function prune(){
	    $this->pruneOld();
	  }
	  function check($code){
	    $this->code_entered = $code;
	    $this->validate();
	    return $this->correct_code;
	  }
	  function doImage(){
	    if($this->use_transparent_text == TRUE || $this->bgimg != ""){
	      $this->im = imagecreatetruecolor($this->image_width, $this->image_height);
	      $bgcolor = imagecolorallocate($this->im, $this->image_bg_color['red'], $this->image_bg_color['green'], $this->image_bg_color['blue']);
	      imagefilledrectangle($this->im, 0, 0, imagesx($this->im), imagesy($this->im), $bgcolor);
	    } else {
	      $this->im = imagecreate($this->image_width, $this->image_height);
	      $bgcolor = imagecolorallocate($this->im, $this->image_bg_color['red'], $this->image_bg_color['green'], $this->image_bg_color['blue']);
	    }
	    if($this->bgimg != ""){
	    	$this->setBackground();
	    }
	    $this->code = $this->generateCode($this->code_length);
	
	    if (!$this->draw_lines_over_text && $this->draw_lines){
	    	$this->drawLines();
	    }
	    $this->drawWord();
	
	    if ($this->draw_lines_over_text && $this->draw_lines){
	    	$this->drawLines();
	    }
	    $this->saveData();
	    $this->output();
	  }
	  function setBackground(){
	    $dat = @getimagesize($this->bgimg);
	    if($dat == FALSE){return;}
	
	    switch($dat[2]){
	      case 1: $newim  = @imagecreatefromgif($this->bgimg); break;
	      case 2: $newim  = @imagecreatefromjpeg($this->bgimg); break;
	      case 3: $newim  = @imagecreatefrompng($this->bgimg); break;
	      case 15: $newim = @imagecreatefromwbmp($this->bgimg); break;
	      case 16: $newim = @imagecreatefromxbm($this->bgimg); break;
	      default: return;
	    }
	    if(!$newim) return;
	
	    imagecopy($this->im, $newim, 0, 0, 0, 0, $this->image_width, $this->image_height);
	  }
	  function drawLines(){
	    $linecolor = imagecolorallocate($this->im, $this->line_color['red'], $this->line_color['green'], $this->line_color['blue']);
	
	    for($x = 1; $x < $this->image_width; $x += $this->line_distance){
	      imageline($this->im, $x, 0, $x, $this->image_height, $linecolor);
	    }
	    for($y = 11; $y < $this->image_height; $y += $this->line_distance){
	      imageline($this->im, 0, $y, $this->image_width, $y, $linecolor);
	    }
	    if ($this->draw_angled_lines == TRUE){
	      for ($x = -($this->image_height); $x < $this->image_width; $x += $this->line_distance){
	        imageline($this->im, $x, 0, $x + $this->image_height, $this->image_height, $linecolor);
	      }
	      for ($x = $this->image_width + $this->image_height; $x > 0; $x -= $this->line_distance){
	        imageline($this->im, $x, 0, $x - $this->image_height, $this->image_height, $linecolor);
	      }
	    }
	  }
	  function drawWord(){
	    if($this->use_transparent_text == TRUE) {
	      $alpha = floor($this->text_transparency_percentage / 100 * 127);
	      $font_color = imagecolorallocatealpha($this->im, $this->text_color['red'], $this->text_color['green'], $this->text_color['blue'], $alpha);
	    } else {
	      $font_color = imagecolorallocate($this->im, $this->text_color['red'], $this->text_color['green'], $this->text_color['blue']);
	    }
	    $x = $this->text_x_start;
	    $strlen= strlen($this->code);
	    $y_min = ($this->image_height / 2) + ($this->font_size / 2) - 2;
	    $y_max = ($this->image_height / 2) + ($this->font_size / 2) + 2;
	    for($i = 0; $i < $strlen; ++$i){
	      $angle = rand($this->text_angle_minimum, $this->text_angle_maximum);
	      $y = rand($y_min, $y_max);
	      imagettftext($this->im, $this->font_size, $angle, $x, $y, $font_color, $this->ttf_file, $this->code{$i});
	      if($this->shadow_text == TRUE){
	        imagettftext($this->im, $this->font_size, $angle, $x + 2, $y + 2, $font_color, $this->ttf_file, $this->code{$i});
	      }
	      $x += rand($this->text_minimum_distance, $this->text_maximum_distance);
	    }
	  }
	  function generateCode($len){
	    $code = "";
	    for($i = 1; $i <= $len; ++$i){
	      $code .= chr(rand(65, 90));
	    }
	    return $code;
	  }
	  function output(){
	    header("Expires: Sun, 1 Jan 2000 12:00:00 GMT");
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	    header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header("Content-Type: image/jpeg");
	    imagejpeg($this->im);
	    imagedestroy($this->im);
	  }
	  function saveData(){
	    $filename = md5($this->hash_salt . $_SERVER['REMOTE_ADDR']);
	    $fp = fopen($this->data_directory . "/" . $filename, "w+");
	    fwrite($fp, md5( $this->hash_salt . strtolower($this->code) )  );
	    fclose($fp);
	  }
	  function validate(){
	    $filename  = md5($this->hash_salt . $_SERVER['REMOTE_ADDR']);
	    $enced_code= trim(@file_get_contents($this->data_directory . "/" . $filename));
	    $check = md5($this->hash_salt . strtolower($this->code_entered));
	    
	    if($check == $enced_code){
	      $this->correct_code = TRUE;
	      @unlink($this->data_directory . "/" . $filename);
	    } else {
	      $this->correct_code = FALSE;
	    }
	  }
	  function checkCode(){
	    return $this->correct_code;
	  }
	  function pruneOld(){
	    if ($handle = @opendir($this->data_directory)){
	      while (($filename = readdir($handle)) !== false){
	        if(time() - filemtime($this->data_directory . "/" . $filename) > $this->prune_minimum_age * 60){
	          @unlink($this->data_directory . "/" . $filename);
	        } 
	      }
	      closedir($handle);
	    }
	  }
	}
?>