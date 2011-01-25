<?php

/**
 * Clansuite Core Class for Image Handling
 *
 * @author     Daniel Winterfeldt
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @subpackage  core
 * @category    image
 */
class Clansuite_Image {
	
	/**
	 * @var string
	 */
	protected $imageSource;
	
	/**
	 * @var string
	 */
	protected $imageName;
	
	/**
	 * @var string
	 */
	protected $imageTarget;
	
	/**
	 * @var string
	 */
	protected $imageExtension;
	
	/**
	 * @var resource
	 */
	protected $originalImage;
	
	/**
	 * @var resource
	 */
	protected $workImage;
	
	/**
	 * @var resource
	 */
	protected $newImage;
	
	/**
	 * @var int
	 */
	protected $originalWidth;
	
	/**
	 * @var int
	 */
	protected $originalHeight;
	
		/**
	 * @var string
	 */
	protected $thumbName;
		
	/**
	 * @var int
	 */
	protected $newWidth;
	
	/**
	 * @var int
	 */
	protected $newHeight;
	
	protected $startX;
	
	protected $startY;
	
	/**
	 * @var float
	 */
	protected $aspectRatio;
	
	/**
	 * @var boolean
	 */
	protected $keepAspectRatio;
	
	/**
	 * @var int
	 */
	protected $jpegQuality;
	
	/**
	 * Construct of Clansuite_Image Core Class
	 */
	public function __construct($source, $target)
	{
		$this->imageSource 			= $source;
		$this->imageName			= basename($source);
		$this->imageTarget 			= $target;
		$this->imageExtension 		= $this->getImageExtension($this->imageName);
		$this->originalImage		= $this->getOriginalImageResource();
		$this->originalWidth		= imagesx($this->originalImage);
		$this->originalHeight		= imagesy($this->originalImage);

	}
	
	public function newCrop($config)
	{
		Clansuite_Crop::__construct($config);
	}
	
	public function newWatermarkImage($config)
	{
		Clansuite_Watermark::__construct('image', $config);
	}
	
	public function newWatermarkText($config)
	{
		Clansuite_Watermark::__construct('text', $config);
	}
	
	public function newThumbnail($config)
	{
		new Clansuite_Thumbnail($config, $this);
	}
	
	/**
	 * Overwrites exist Image/File Name
	 *
	 * @param String $name
	 */
	public function newName($name)
	{
		$this->imageName = $name.'.'.strtolower($this->imageExtension);
	}
	
	/**
	 * Get the extension of given image
	 *
	 * @return string
	 */
	private function getImageExtension($name)
	{
        if (stristr(strtolower($name),'.gif')) 
        {
        	$extension = 'GIF';
        } 
        elseif (stristr(strtolower($name),'.jpg') || stristr(strtolower($name),'.jpeg'))
        {
        	$extension = 'JPEG';
        } 
        elseif (stristr(strtolower($name),'.png'))
        {
        	$extension = 'PNG';
        }
        
        return $extension;
    }
    
    /**
     *	Initialize Original Image Resource
     * 
     * @return resource
     */
    protected function getOriginalImageResource()
    {
        $method = 'createImageFrom'.$this->imageExtension;
        return $this->$method($this->imageSource);
	}
	
	private function createImageFromGIF($source)
	{
		return ImageCreateFromGif($source);
	}
	
	private function createImageFromJPEG($source)
	{
		return ImageCreateFromJpeg($source);
	}
	
	private function createImageFromPNG($source)
	{
		return ImageCreateFromPng($source);
	}
    
    public function getWorkImageResource($width, $height)
    {
    	if(function_exists("ImageCreateTrueColor")) {
			return ImageCreateTrueColor($width, $height);
		} else {
			return ImageCreate($width, $height);
		}
    }
    
    
    public function resample()
    {

		ImageCopyResampled(
			$this->workImage,
			$this->originalImage,
			0,
			0,
			$this->startX,
			$this->startY,
			$this->newWidth,
			$this->newHeight,
			$this->originalWidth,
			$this->originalHeight
		);

		$this->startX 			= 0;
		$this->startY 			= 0;
		$this->originalWidth 	= $this->newWidth;
		$this->originalHeight 	= $this->newHeight;
		$this->originalImage	= $this->workImage;
		$this->newImage 		= $this->workImage;
		
	}
	
	public function save()
	{
		$method = 'save'.$this->imageExtension;
		$this->$method();
	}
	
	private function saveGIF()
	{
		ImageGif($this->newImage, $this->thumbName.$this->imageName);
	}
	
	private function saveJPEG()
	{
		ImageJpeg($this->newImage, $this->thumbName.$this->imageName, $this->jpegQuality);
		
	}
	
	private function savePNG()
	{
		ImageJpeg($this->newImage, $this->thumbName.$this->imageName);
	}

    public function __destruct()
    {
        ImageDestroy($this->originalImage);

    }    
}
?>