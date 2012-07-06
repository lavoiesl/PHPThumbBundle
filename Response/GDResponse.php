<?php

namespace Lavoiesl\PhpThumbBundle\Response;

use Symfony\Component\HttpFoundation\Response;
use PHPThumb\GD;

class GDResponse extends Response
{
    public function __construct(GD $thumb) {
        parent::__construct($thumb->getImageAsString());

        switch ($thumb->getFormat()) {
            case 'GIF':
                $mime = 'image/gif';
                break;
            case 'JPG':
                $mime = 'image/jpeg';
                break;
            case 'PNG':
            case 'STRING':
                $mime = 'image/png';
                break;
            default:
                return;
        }
        $this->headers->set('Content-Type', $mime);
    }
}