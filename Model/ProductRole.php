<?php
namespace Ethnic\ProductRoleImage\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class ProductRole
 * Model for custom product image roles. Handles dynamic attribute creation/removal.
 */
class ProductRole extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ethnic_productroleimage';

    /**
     * @var EavSetupFactory
     */
    protected EavSetupFactory $eavSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    protected ModuleDataSetupInterface $moduleDataSetup;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Define resource model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Ethnic\ProductRoleImage\Model\ResourceModel\ProductRole::class);
    }

    /**
     * Prepare attribute code from role before saving.
     *
     * @return $this
     */
    public function beforeSave(): self
    {
        $role = $this->getData('role');

        // Strip invisible and formatting characters
        $role = preg_replace('/[\x00-\x1F\x7F\xA0\xAD\x{FEFF}\p{Cf}]/u', '', $role);

        // Convert role to clean attribute code
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $role);
        $code = strtolower($ascii);
        $code = preg_replace('/[^a-z0-9]/', '_', $code);
        $code = preg_replace('/_+/', '_', $code);
        $attributeCode = 'product_role_' . trim($code, '_');

        $this->setData('attribute_code', $attributeCode);

        return parent::beforeSave();
    }

    /**
     * Automatically create product EAV attribute after saving.
     *
     * @return $this
     */
    public function afterSave(): self
    {
        $attributeCode = $this->getData('attribute_code');
        $role = $this->getData('role');

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $existingAttr = $eavSetup->getAttribute(Product::ENTITY, $attributeCode);

        if (!$existingAttr) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                $attributeCode,
                [
                    'type' => 'varchar',
                    'label' => $role,
                    'input' => 'media_image',
                    'frontend' => \Magento\Catalog\Model\Product\Attribute\Frontend\Image::class,
                    'required' => false,
                    'sort_order' => 100,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible' => true,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                ]
            );
        }

        return parent::afterSave();
    }

    /**
     * Automatically remove associated EAV attribute before deletion.
     *
     * @return $this
     */
    public function beforeDelete(): self
    {
        $attributeCode = $this->getData('attribute_code');

        if ($attributeCode) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $existingAttr = $eavSetup->getAttribute(Product::ENTITY, $attributeCode);

            if ($existingAttr) {
                $eavSetup->removeAttribute(Product::ENTITY, $attributeCode);
            }
        }

        return parent::beforeDelete();
    }
}
