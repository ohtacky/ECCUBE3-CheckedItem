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

use Eccube\Plugin\AbstractPluginManager;
use Symfony\Component\Filesystem\Filesystem;

class PluginManager extends AbstractPluginManager
{

    public function install($config, $app)
    {
      $this->migrationSchema($app, __DIR__ . '/Migration', $config['code']);

      $file = new Filesystem();
      try {
          $file->copy($app['config']['root_dir']. '/app/Plugin/CheckedItem/checkeditem.twig', $app['config']['root_dir']. '/app/template/default/Block/checkeditem.twig', true);
          return true;
      } catch (\Exception $e) {
          return false;
      }
    }

    public function uninstall($config, $app)
    {
      unlink($app['config']['root_dir']. '/app/template/default/Block/checkeditem.twig');

      $this->migrationSchema($app, __DIR__ . '/Migration', $config['code'], 0);
    }

    public function enable($config, $app)
    {
    }

    public function disable($config, $app)
    {
      $qb = $app['orm.em']->createQueryBuilder();
      $qb->select("c")
         ->from("Eccube\\Entity\\Block", "c")
         ->where("c.file_name = :file_name")
         ->setParameter("file_name", "checkeditem");
      $query = $qb->getQuery();
      $block = $query->getSingleResult();
      $blockId = $block->getId();

      $app['orm.em']->createQueryBuilder()
          ->delete("Eccube\\Entity\\BlockPosition", "d")
          ->where("d.block_id = :block_id")
          ->setParameter("block_id", $blockId)
          ->getQuery()
          ->execute();
    }

    public function update($config, $app)
    {
    }
}
