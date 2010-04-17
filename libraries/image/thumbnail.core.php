<?php
class Clansuite_Thumbnail extends Clansuite_Image
{
	protected $object;
	
	public function __construct($config, Clansuite_Image $object)
	{
		
		$this->object = $object;
		$this->object->thumbName 			= $config['thumb_name'];
		$this->object->newWidth 			= $config['new_width'];
		$this->object->newHeight 			= $config['new_height'];
		$this->object->keepAspectRatio		= $config['keep_aspect_ratio'];
		$this->object->aspectRatio			= $this->calcAspectRatio();
		$this->object->jpegQuality			= $config['jpeg_quality'];
		$this->object->workImage			= $object->getWorkImageResource($object->newWidth, $object->newHeight);

	}

    public function calcAspectRatio()
    {
    	if ($this->object->newWidth != 0)
    	{
    		$ratio = $this->object->originalWidth / $this->object->newWidth;
    		$this->object->newHeight = ((int)round($this->object->originalHeight / $ratio));
    		return $ratio;
    	}
    	
    	if ($object->newHeight != 0)
    	{
    		$ratio = $object->originalHeight / $object->newHeight;
    		$object->newWidth((int)round($object->originalWidth / $ratio));
    		return $ratio;
    	}
    }
}
?>