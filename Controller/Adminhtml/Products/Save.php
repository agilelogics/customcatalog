<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Controller\Adminhtml\Products;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Agilelogics\CustomCatalog\Model\ProductsFactory as AgilelogicsProdFactory;

class Save extends Action
{
    protected $_prodFactory;

    public function __construct(Context $context, AgilelogicsProdFactory $prodFactory) {
        $this->_prodFactory = $prodFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Agilelogics_CustomCatalog::products');
    }

    public function execute()
    {
        $storeId = (int)$this->getRequest()->getParam('store_id');
        $data = $this->getRequest()->getParams();        
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $params = [];
            $objectInstance = $this->_prodFactory->create();
            $objectInstance->setStoreId($storeId);
            $params['store'] = $storeId;
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            } 
            else {
                $objectInstance->load($data['entity_id']);
                $params['entity_id'] = $data['entity_id'];
            }
            $objectInstance->addData($data);
            $this->_eventManager->dispatch('agilelogics_customcatalog_products_prepare_save', ['object' => $this->_prodFactory, 'request' => $this->getRequest()]);
            try {
                $objectInstance->save();
                $this->messageManager->addSuccessMessage(__('Record saved successfully.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $params['entity_id'] = $objectInstance->getId();
                    $params['_current'] = true;
                    return $resultRedirect->setPath('*/*/edit', $params);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage($e, __('Error, Something went wrong while saving record.'));
            }
            $this->_getSession()->setFormData($this->getRequest()->getPostValue());
            return $resultRedirect->setPath('*/*/edit', $params);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

