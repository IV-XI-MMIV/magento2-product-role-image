<?php
namespace Ethnic\ProductRoleImage\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ProductRole
 * Resource model for the Product Role entity.
 */
class ProductRole extends AbstractDb
{
    /**
     * Initialize main table and primary key.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('ethnic_product_image_role', 'id'); // Table name and primary key
    }
}
