<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Controller\Adminhtml\Products;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Validate extends \Magento\Backend\App\Action
{
    protected $_jsonFactory;
    protected $_response;

    public function __construct(Context $context, JsonFactory $jsonFactory) {
        parent::__construct($context);
        $this->_jsonFactory = $jsonFactory;
        $this->_response = new \Magento\Framework\DataObject();
    }

    public function validateRequireEntries(array $data)
    {
        $requiredFields = [
            'product_id'      => __('ProductID'),
			'copywrite_info'  => __('CopyWriteInfo'),
			'vpn'             => __('VPN'),
			'sku'             => __('SKU')
        ];
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $this->_addErrorMessage(__('Error: Please fill all "%1" fields', $requiredFields[$field]));
            }
        }
    }

    protected function _addErrorMessage($message)
    {
        $this->_response->setError(true);
        if (!is_array($this->_response->getMessages())) {
            $this->_response->setMessages([]);
        }
        $messages = $this->_response->getMessages();
        $messages[] = $message;
        $this->_response->setMessages($messages);
    }

    public function execute()
    {
        $this->_response->setError(0);
        $this->validateRequireEntries($this->getRequest()->getParams());
        $resultJson = $this->_jsonFactory->create()->setData($this->_response);
        return $resultJson;
    }
}
