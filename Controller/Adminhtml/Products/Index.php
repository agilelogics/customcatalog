<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
*/
namespace Agilelogics\CustomCatalog\Controller\Adminhtml\Products;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $_pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory) {
        parent::__construct($context);
        $this->_pageFactory = $pageFactory;
    }
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Agilelogics_CustomCatalog::products');
    }

    public function execute()
    {
        $pageFactory = $this->_pageFactory->create();
        $pageFactory->setActiveMenu('Agilelogics_CustomCatalog::products');
        $pageFactory->getConfig()->getTitle()->prepend(__('Products'));
        return $pageFactory;
    }
}