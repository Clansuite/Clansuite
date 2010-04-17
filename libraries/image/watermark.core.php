<?php
class Clansuite_Watermark extends Clansuite_Image
{
	
	public function __construct($function, $config)
	{
		if($function == 'image')
		{
			$watermark = ImageCreateFromPng($config['file']);
		
			imagecopy(	$this->workImage,
						$watermark,
						$config['pos_x'],
						$config['pos_y'],
						0,
						0,
						imagesx($watermark),
						imagesy($watermark)
					);
		}
	}
}
?>