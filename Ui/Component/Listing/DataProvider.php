<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Ui\Component\Listing;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as UiDataProvider;

class DataProvider extends UiDataProvider
{

    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $searchResult->setStoreId($this->request->getParam('store', 0))->addAttributeToSelect(['product_id','copywrite_info','vpn','sku']);
        return parent::searchResultToOutput($searchResult);
    }

    protected function prepareUpdateUrl()
    {
        $storeId = $this->request->getParam('store', 0);
        if ($storeId) {
            $this->data['config']['update_url'] = sprintf('%s%s/%s', $this->data['config']['update_url'], 'store', $storeId);
        }
        return parent::prepareUpdateUrl();
    }
}