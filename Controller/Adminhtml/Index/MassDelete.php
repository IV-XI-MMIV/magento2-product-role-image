<?php
namespace Ethnic\ProductRoleImage\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\ResultFactory;
use Ethnic\ProductRoleImage\Model\ResourceModel\ProductRole\CollectionFactory;
use Ethnic\ProductRoleImage\Model\ProductRoleFactory;

/**
 * Class MassDelete
 * Handles mass deletion of product role records and their associated attributes.
 */
class MassDelete extends Action
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ProductRoleFactory
     */
    protected $productRoleFactory;

    /**
     * Constructor.
     *
     * @param Action\Context $context
     * @param CollectionFactory $collectionFactory
     * @param ProductRoleFactory $productRoleFactory
     */
    public function __construct(
        Action\Context $context,
        CollectionFactory $collectionFactory,
        ProductRoleFactory $productRoleFactory
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->productRoleFactory = $productRoleFactory;
    }

    /**
     * Execute mass delete action.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $selected = $this->getRequest()->getParam('selected', []);
        $excluded = $this->getRequest()->getParam('excluded');
        $deletedCount = 0;
        $deletedAttributes = [];

        try {
            $collection = $this->collectionFactory->create();

            if ($excluded === 'false') {
                // "Select All" case: delete all
                foreach ($collection as $item) {
                    $deletedCount += $this->deleteItemById((int) $item->getId(), $deletedAttributes);
                }
            } elseif (is_array($selected) && !empty($selected)) {
                // Selected IDs only
                foreach ($selected as $id) {
                    $deletedCount += $this->deleteItemById((int) $id, $deletedAttributes);
                }
            } else {
                $this->messageManager->addErrorMessage(__('Please select item(s) to delete.'));
            }

            if ($deletedCount > 0) {
                $attrList = !empty($deletedAttributes) ? implode(', ', $deletedAttributes) : __('N/A');
                $this->messageManager->addSuccessMessage(
                    __('Deleted %1 record(s) and removed attribute(s): %2.', $deletedCount, $attrList)
                );
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Unexpected error: %1', $e->getMessage()));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }

    /**
     * Deletes a Product Role by ID and stores attribute code if available.
     *
     * @param int $id
     * @param array $deletedAttributes
     * @return int
     */
    protected function deleteItemById(int $id, array &$deletedAttributes): int
    {
        try {
            $model = $this->productRoleFactory->create()->load($id);
            if ($model->getId()) {
                $attributeCode = $model->getAttributeCode();
                $model->delete(); // triggers beforeDelete()
                if ($attributeCode) {
                    $deletedAttributes[] = $attributeCode;
                }
                return 1;
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error deleting ID %1: %2', $id, $e->getMessage()));
        }

        return 0;
    }
}
