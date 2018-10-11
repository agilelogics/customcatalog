<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Controller\Adminhtml\Products;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Agilelogics\CustomCatalog\Model\ResourceModel\Products\Collection as AgilelogicsProductCollection;


class MassDelete extends Action
{
    protected $_filter;
    protected $_collection;

    public function __construct(Context $context, Filter $filter, AgilelogicsProductCollection $prodCollection)
    {
        $this->_filter = $filter;
        $this->_collection = $prodCollection;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->_collection);
        $collection_size = $collection->getSize();
        $collection->walk('delete');
        $this->messageManager->addSuccessMessage(__('Total %1 record(s) have been deleted.', $collection_size));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}