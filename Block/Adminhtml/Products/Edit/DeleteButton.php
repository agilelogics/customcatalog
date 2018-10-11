<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Block\Adminhtml\Products\Edit;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton implements ButtonProviderInterface
{
    protected $_url;
    protected $_registry;

    public function __construct( Context $context, Registry $registry) {
        $this->_url     = $context->getUrlBuilder();
        $this->_registry = $registry;
    }

    public function getButtonData()
    {
        $data = [
            'label'          => __('Delete'),
            'class'          => 'delete',
            'id'             => 'products-edit-delete-button',
            'data_attribute' => ['url' => $this->getDeleteUrl()],
            'on_click'       => 'deleteConfirm(\''. __("Are you sure you want to delete this item?").'\', \''.$this->getDeleteUrl().'\')',
            'sort_order'     => 40,
        ];
        return $data;
    }

    public function getDeleteUrl()
    {
        return $this->_url->getUrl('*/*/delete', ['entity_id' => $this->_registry->registry('entity_id')]);
    }
}
