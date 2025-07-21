<?php
namespace Ethnic\ProductRoleImage\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Ethnic\ProductRoleImage\Model\ProductRoleFactory;

/**
 * Class Save
 * Handles saving or updating a Product Role in admin.
 */
class Save extends Action
{
    /**
     * @var ProductRoleFactory
     */
    protected $productRoleFactory;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param ProductRoleFactory $productRoleFactory
     * @param RedirectFactory $redirectFactory
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        Context $context,
        ProductRoleFactory $productRoleFactory,
        RedirectFactory $redirectFactory,
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        parent::__construct($context);
        $this->productRoleFactory = $productRoleFactory;
        $this->redirectFactory = $redirectFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Save Product Role data.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (empty($data['role'])) {
            $this->messageManager->addErrorMessage(__('Please enter a role.'));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $id = $data['id'] ?? null;
            $model = $this->productRoleFactory->create();

            if ($id) {
                $model->load((int) $id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This record no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData('role', $data['role']);
            $model->setData('show_in_frontend', isset($data['show_in_frontend']) ? (int)$data['show_in_frontend'] : 0);

            $model->save(); // Will trigger any observers/logic like attribute creation

            $this->messageManager->addSuccessMessage(__('Product Role saved successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error while saving: %1', $e->getMessage()));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
