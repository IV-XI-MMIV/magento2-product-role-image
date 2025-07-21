<?php
namespace Ethnic\ProductRoleImage\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Forms
 * Renders the Product Role Image form in admin panel.
 */
class Forms extends Action
{
    /**
     * Authorization resource
     */
    public const ADMIN_RESOURCE = 'Ethnic_ProductRoleImage::main_menu';

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
     * Execute method to render the form page.
     *
     * @return ResultInterface|Page
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Add Product Role Image'));

        return $resultPage;
    }
}
