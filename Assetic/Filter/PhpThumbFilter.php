<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2012 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LavoieSl\PhpThumbBundle\Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Assetic\Exception\FilterException;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PhpThumbFilter implements FilterInterface
{
  private $router;

  public function __construct(UrlGeneratorInterface $router) {
    $this->router = $router;
  }

  public function __sleep() {
    return array();
  }

  function filterLoad(AssetInterface $asset) {
      $this->filterDump($asset);
  }

  function filterDump(AssetInterface $asset) {
    $router = $this->router;

    $pattern = '/-php-thumb\((["\']?)(?<url>.*?)(\\1)(?<options>(,\s*[a-zA-Z_0-9]+(=[a-zA-Z_0-9|]+)?)+)?\)/';

    $content = preg_replace_callback($pattern, function($matches) use ($router) {
      $options = array('resource' => $matches['url']);

      if (!empty($matches['options'])) {
        $m = preg_match_all('/,\s*(?<option>[a-zA-Z_0-9]+)(=(?<value>[a-zA-Z_0-9|]+))?/', $matches['options'], $o_matches);
        for ($i=0; $i < $m; $i++) { 
          $option           = $o_matches['option'][$i];

          if (empty($o_matches['value'][$i])) {
            $values = array();
          } else {
            $values = explode("|", $o_matches['value'][$i]);
          }

          $options[$option] = implode("|", $values);
        }
      }

      return "url('" . $router->generate('phpthumb_image', $options) . "')";
    }, $asset->getContent());
    $asset->setContent($content);
  }
}
