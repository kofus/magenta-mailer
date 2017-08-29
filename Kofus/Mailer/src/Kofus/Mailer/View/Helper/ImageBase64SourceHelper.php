<?php

namespace Kofus\Mailer\View\Helper;
use Zend\View\Helper\AbstractHelper;


class ImageBase64SourceHelper extends AbstractHelper
{
    protected $filename;
    
    public function __invoke($filename)
    {
        $this->filename = $filename;
    	return $this;
    }
    
    public function render()
    {
        if (! file_exists($this->filename))
            throw new \Exception('File not found: ' . $this->filename);
        
        switch (exif_imagetype($this->filename)) {
            case IMAGETYPE_GIF:
                $imageType = 'image/gif';
                break;
            case IMAGETYPE_JPEG:
                $imageType = 'image/jpg';
                break;
            case IMAGETYPE_PNG:
                $imageType = 'image/png';
                break;
            case IMAGETYPE_ICO:
                $imageType = 'image/ico';
                break;
            default:
                throw new \Exception('Could not determine image type for ' . $this->filename);
                
        }
        
        $stream = file_get_contents($this->filename);
        $src = 'data:' . $imageType . ';base64,' . base64_encode($stream);
        
        return $src;
    }
    
    public function __toString()
    {
        return $this->render();
    }
    
}


