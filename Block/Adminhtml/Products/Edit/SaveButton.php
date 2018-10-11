<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Block\Adminhtml\Products\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{

    public function getButtonData()
    {
        $data = [
                'label' => __('Save'),
                'class' => 'save primary',
                'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],'form-role' => 'save',],
                'sort_order' => 60,
                ];
        return $data;
    }
}