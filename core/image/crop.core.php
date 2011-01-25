<?php
class Clansuite_Crop extends Clansuite_Image
{	
	public function __construct($config)
	{
		$this->startX 			= $config['start_x'];
		$this->startY 			= $config['start_y'];
		$this->newWidth 		= $config['width'];
		$this->newHeight 		= $config['height'];
		$this->originalWidth	= $config['width'];
		$this->originalHeight	= $config['height'];
		$this->jpegQuality		= 100;

		$this->workImage = $this->getWorkImageResource($this->newWidth, $this->newHeight);
	}
}
?>