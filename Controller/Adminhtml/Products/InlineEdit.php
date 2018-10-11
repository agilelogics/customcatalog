<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Controller\Adminhtml\Products;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Agilelogics\CustomCatalog\Model\ResourceModel\Products\Collection as AgilelogicsCollection;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $_jsonFactory;
    protected $_collection;

    public function __construct(Context $context, AgilelogicsCollection $objCollection, JsonFactory $jsonFactory) 
    {
        parent::__construct($context);
        $this->_jsonFactory = $jsonFactory;
        $this->_collection = $objCollection;
    }

    public function execute()
    {
        $resultJson = $this->_jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData(['messages' => [__('Error: data is not correct.')],'error' => true,]);
        }
        try {
            $this->_collection
                ->setStoreId($this->getRequest()->getParam('store', 0))
                ->addFieldToFilter('entity_id', ['in' => array_keys($postItems)])
                ->walk('saveCollection', [$postItems]);
        } catch (\Exception $e) {
            $messages[] = __('There was an error saving the data: ') . $e->getMessage();
            $error = true;
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
