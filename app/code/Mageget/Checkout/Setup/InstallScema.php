 <?php
// namespace Mageget\Checkout\Setup;

// class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
// {
//     public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
//     {
//         $installer = $setup;
//         $installer->startSetup();

//         $eavTable1 = $installer->getTable('quote');
//         $eavTable2 = $installer->getTable('sales_order');

//         $columns = [
//             'comment' => [
//                 'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
//                 'nullable' => true,
//                 'comment' => 'Comment'
//             ],
//             'image' => [
//                 'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
//                 255,
//                 'nullable' => true,
//                 'comment' => 'Comment'
//             ]
//         ];

//         $connection = $installer->getConnection();

//         foreach($columns as $name => $definition) {
//             $connection->addColumn($eavTable1, $name, $definition);
//             $connection->addColumn($eavTable2, $name, $definition);
//         }

//         $installer->endSetup();
//     }
// }
