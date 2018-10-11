<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Block\Adminhtml\Products\Edit;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    protected $_url;
    public function __construct(Context $context)
    {
        $this->_url = $context->getUrlBuilder();
    }
    public function getButtonData()
    {
        return ['label' => __('Back'), 'on_click' => sprintf("location.href = '%s';", $this->getBackButtonURL()), 'class' => 'back', 'sort_order' => 30 ];
    }
    public function getBackButtonURL()
    {
        return $this->_url->getUrl('*/*/');
    }
}