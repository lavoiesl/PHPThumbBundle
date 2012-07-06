<?php

namespace LavoieSl\PhpThumbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Response;
use LavoieSl\PhpThumbBundle\Response\GDResponse;
use PHPThumb\GD;

class ImageController extends Controller
{
  /**
   * @Route("/image", name="phpthumb_image")
   * @Cache(expires="tomorrow")
   */
  public function imageAction()
  {
    $query = $this->getRequest()->query;

    $options = array(
      'resizeUp'    => true,
      'jpegQuality' => 100,
      'cache_life'  => '-1 month'
    );
    $filters = array();

    $resource = 'bundles/phpthumb/images/test.jpg';

    foreach ($query->all() as $key => $value) {
      if ($key === 'resource') {
        $resource = $value;
      } elseif (method_exists('PHPThumb\GD', $key)) {
        $args = strlen($value) ? explode('|', $value) : array();
        $filters[$key] = $args;
      } else {
        $options[$key] = $value;
      }
    }

    $image = $this->get('kernel')->getRootDir() . '/../htdocs/' . $resource;
    $thumb = new GD($image, $options);

    foreach ($filters as $filter => $params) {
      call_user_func_array(array($thumb, $filter), $params);
    }

    return new GDResponse($thumb);
  }
}
