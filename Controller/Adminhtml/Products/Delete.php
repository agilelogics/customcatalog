<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Controller\Adminhtml\Products;
use Magento\Backend\App\Action\Context;
use Agilelogics\CustomCatalog\Model\ProductsFactory as AgilelogicsProdFactory;

class Delete extends \Magento\Backend\App\Action
{
    protected $_prodFactory;

    public function __construct(Context $context, AgilelogicsProdFactory $prodFactory) 
    {
        $this->_prodFactory = $prodFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Agilelogics_CustomCatalog::products');
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $entity_id = $this->getRequest()->getParam('entity_id', null);

        try {
            $objectInstance = $this->_prodFactory->create()->load($entity_id);
            if ($objectInstance->getId()) {
                $objectInstance->delete();
                $this->messageManager->addSuccessMessage(__('Record deleted successfully.'));
            } 
            else {
                $this->messageManager->addErrorMessage(__('Error, Record does not exist.'));
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }        
        return $resultRedirect->setPath('*/*');
    }
}
