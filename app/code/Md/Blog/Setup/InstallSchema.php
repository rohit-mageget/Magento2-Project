<?php
/**
* Copyright © Magento, Inc. All rights reserved.
* See COPYING.txt for license details.
*/
namespace Md\Blog\Setup;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
/**
* @codeCoverageIgnore
*/
class InstallSchema implements InstallSchemaInterface {
/**
* {@inheritdoc}
* @SuppressWarnings(PHPMD.ExcessiveMethodLength)
*/
public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
$installer = $setup;
$installer->startSetup();
/**
* Create table 'Md_blog'
*/
if (!$installer->tableExists('Md_blog')) {
$table = $installer->getConnection()->newTable(
$installer->getTable('Md_blog')
)->addColumn(
'id',
Table::TYPE_INTEGER,
null,
[
'identity' => true,
'nullable' => false,
'primary' => true,
'unsigned' => true,
],
'ID'
)->addColumn(
'name',
Table::TYPE_TEXT,
255,
['nullable => false'],
'Name'
)->addColumn(
'title',
Table::TYPE_TEXT,
'255',
['nullable => false'],
'Blog Title'
)->addColumn(
'content',
Table::TYPE_TEXT,
'2M',
[],
'Blog Content'
)->addColumn(
'status',
Table::TYPE_SMALLINT,
null,
['nullable' => false],
'Status'
)->addColumn(
'created_at',
Table::TYPE_TIMESTAMP,
null,
['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
'Created At'
)->setComment('Blog Table');
$installer->getConnection()->createTable($table);
}
$installer->endSetup();
}
}