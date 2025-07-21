<?php
namespace Ethnic\ProductRoleImage\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 * Controller for Product Role Image grid view in admin.
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * Constructor.
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute method to render the grid page.
     *
     * @return ResultInterface|Page
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ethnic_ProductRoleImage::main_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Role Image'));

        return $resultPage;
    }

    /**
     * Check ACL permissions.
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Ethnic_ProductRoleImage::main_menu');
    }
}
