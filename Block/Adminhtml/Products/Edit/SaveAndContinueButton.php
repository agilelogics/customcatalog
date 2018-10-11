<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Block\Adminhtml\Products\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndContinueButton implements ButtonProviderInterface
{

    public function getButtonData()
    {
        $data = [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => ['mage-init' => ['button'    => ['event' => 'saveAndContinueEdit'],],],
                'sort_order' => 50,
                ];
        return $data;
    }
}