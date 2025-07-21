<?php
namespace Ethnic\ProductRoleImage\Setup\Patch\Schema;

use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class CreateTable
 * Schema patch to create `ethnic_product_image_role` table.
 */
class CreateTable implements SchemaPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * Constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Apply schema changes to create the table.
     *
     * @return void
     */
    public function apply(): void
    {
        $setup = $this->moduleDataSetup;
        $connection = $setup->getConnection();

        $setup->startSetup();

        if (!$connection->isTableExists('ethnic_product_image_role')) {
            $table = $connection->newTable('ethnic_product_image_role')
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'role',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Role Name'
                )
                ->addColumn(
                    'attribute_code',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Attribute Code'
                )
                ->addColumn(
                    'show_in_frontend',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => 0],
                    'Show In Frontend'
                )
                ->setComment('Product Role Image Table');

            $connection->createTable($table);
        }

        $setup->endSetup();
    }

    /**
     * Get patch dependencies.
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get patch aliases.
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
