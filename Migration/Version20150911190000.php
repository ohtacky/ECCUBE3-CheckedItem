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

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150911190000 extends AbstractMigration
{

    public function up(Schema $schema)
    {
    }

    public function down(Schema $schema)
    {
      $this->connection->delete('dtb_block', array('file_name' => 'checkeditem'));

    }

    public function postUp(Schema $schema)
    {

        $app = new \Eccube\Application();
        $app->boot();

        $statement = $this->connection->prepare('SELECT block_id FROM dtb_block ORDER BY block_id desc');
        $statement->execute();
        $blockId = $statement->fetchColumn();

        $blockIdNumber = $blockId + 1;
        $deviceTypeId = '10';
        $blockName = 'チェックした商品';
        $fileName = 'checkeditem';
        $datetime = date('Y-m-d H:i:s');
        $logicFlg = '1';
        $deletableFlg = '1';
        $insert = "INSERT INTO dtb_block(
                            block_id, device_type_id, block_name, file_name, create_date, update_date, logic_flg, deletable_flg)
                    VALUES ('$blockIdNumber', '$deviceTypeId', '$blockName', '$fileName', '$datetime', '$datetime', '$logicFlg', '$deletableFlg'
                            );";
        $this->connection->executeUpdate($insert);

    }
}
