<?php

namespace LavoieSl\PhpThumbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/demo")
 */
class DemoController extends Controller
{
  /**
   * @Route("/")
   * @Template()
   */
  public function indexAction()
  {
    $resource = 'bundles/phpthumb/images/test.jpg';
    $demos = array(
      'Original'            => array(),
      'Cropped'             => array('crop' => array(100, 100, 300, 200)),
      'Cropped from center' => array('cropFromCenter' => array(200, 100)),
      'Basic resize'        => array('resize' => array(100, 100)),
      'Percentage resize'   => array('resizePercent' => array(50)),
      'Adaptive resize'     => array('adaptiveResize' => array(175, 175)),
      'CW Rotate'           => array('rotateImage' => array('CW')),
      'Advanced Rotate'     => array('rotateImageNDegrees' => array(60)),
    );

    foreach ($demos as &$demo) {
      foreach ($demo as &$options) {
        $options = implode('|', $options);
      }
      $demo['resource'] =& $resource;
    }

    return array(
      'demos' => $demos
    );
  }
}
