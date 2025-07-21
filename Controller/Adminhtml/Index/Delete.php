<?php
namespace Ethnic\ProductRoleImage\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\ResultFactory;
use Ethnic\ProductRoleImage\Model\ProductRoleFactory;

/**
 * Class Delete
 * Deletes a product role and its associated attribute.
 */
class Delete extends Action
{
    /**
     * @var ProductRoleFactory
     */
    protected $productRoleFactory;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param ProductRoleFactory $productRoleFactory
     */
    public function __construct(
        Context $context,
        ProductRoleFactory $productRoleFactory
    ) {
        parent::__construct($context);
        $this->productRoleFactory = $productRoleFactory;
    }

    /**
     * Execute delete action.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $id = (int) $this->getRequest()->getParam('id');

        /** @var ResultFactory $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$id) {
            $this->messageManager->addErrorMessage(__('Invalid ID.'));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $model = $this->productRoleFactory->create()->load($id);

            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This role does not exist.'));
                return $resultRedirect->setPath('*/*/');
            }

            $attributeCode = $model->getData('attribute_code');

            $model->delete();

            $this->messageManager->addSuccessMessage(
                __('Role and attribute "%1" have been deleted.', $attributeCode)
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: %1', $e->getMessage()));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
