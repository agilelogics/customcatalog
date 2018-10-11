<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Controller\Adminhtml\Products;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Agilelogics\CustomCatalog\Model\ProductsFactory as AgilelogicsProdFactory;

class Edit extends \Magento\Backend\App\Action
{
    protected $_coreRegistry = null;
    protected $_resultPageFactory;
    protected $_prodFactory;


    public function __construct(Context $context, PageFactory $resultPageFactory, Registry $registry, AgilelogicsProdFactory $prodFactory) 
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_registry = $registry;
        $this->_prodFactory = $prodFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Agilelogics_CustomCatalog::products');
    }

    public function execute()
    {
        $entity_id = $this->getRequest()->getParam('entity_id');
        $objectInstance = $this->_prodFactory->create();
        if ($entity_id) {
            $objectInstance->load($entity_id);
            if (!$objectInstance->getId()) {
                $this->messageManager->addErrorMessage(__('Record not exist.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }        
        $data = $this->_session->getFormData(true);
        if (!empty($data)) {
            $objectInstance->addData($data);
        }
        $this->_registry->register('entity_id', $entity_id);
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Agilelogics_CustomCatalog::products');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Edit'));
        return $resultPage;
    }
}
