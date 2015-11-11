<?php
/*
* This file is part of EC-CUBE
*
* Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
* http://www.lockon.co.jp/
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Plugin\CheckedItem;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CheckedItemEvent
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }


    public function dispProductData(FilterResponseEvent $event)
    {

      $app = $this->app;
      $request = $event->getRequest();
      $response = $event->getResponse();

      $productId = $app['request']->get('id');

      $checkedItems   = $request->cookies->get('CheckedItemIds');

      $checkedItemArray = array();

      if ($checkedItems !== null) {
        $checkedItemArray = explode(",", $checkedItems);
      }

      $duprecateKeys = array_keys($checkedItemArray, $productId);
      if (count($duprecateKeys) > 0) {
        foreach ($duprecateKeys as $duprecateKey) {
          unset($checkedItemArray[$duprecateKey]);
        }
      }

      $checkedItemArray[] = $productId;

      //表示は12個
      if (count($checkedItemArray) > '12') {
        unset($checkedItemArray[0]);
      }

      //cookie保持の期間の設定(1週間)
      $response->headers->setCookie(new Cookie('CheckedItemIds',implode(",", $checkedItemArray), time() + 3600 * 24 * 7, '/'));

    }

}
