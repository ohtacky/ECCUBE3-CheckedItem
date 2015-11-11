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

namespace Plugin\CheckedItem\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;

class CheckedItemController
{
  public function index(Application $app)
  {

    $checkedItemArray = array();

    $checkedItems = $app['request']->cookies->get('CheckedItemIds');

    if ($checkedItems !== null) {

      $checkedItemsArray = array_reverse(explode(",", $checkedItems));

      foreach ($checkedItemsArray as $checkedItemsId) {

        $checkedItem = $app['orm.em']->getRepository('\Eccube\Entity\Product')
              ->findOneBy(
                  array('id' => $checkedItemsId)
                        );

        $checkedItemArray[] = $checkedItem;

      }

      return $app->render('Block/checkeditem.twig', array(
          'checkedItems' => $checkedItemArray
      ));

    } else {

      return $app->render('Block/checkeditem.twig', array(
        'checkedItems' => null
      ));

    }

  }
}
