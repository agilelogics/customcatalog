<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
/* RabbitMQ Library Usage: refrence from:
 * Github: 
 * - monsieurbiz/monsieurbiz_amqp
 * - rabbitmq/rabbitmq-delayed-message-exchange
 */

namespace Agilelogics\CustomCatalog\Model;
use Agilelogics\CustomCatalog\Api\ProductManagementInterface;
use Agilelogics\CustomCatalog\Model\ProductsFactory as AgilelogicsProductFactory;

class ProductManagement implements ProductManagementInterface
{
    protected $_prodFactory;	
	protected $_amqp;
	
    public function __construct(AgilelogicsProductFactory $ProductsFactory, Agilelogics\Amqp\Helper\Amqp $amqp)
    {
        $this->_prodFactory = $ProductsFactory;
		$this->_amqp = $amqp;
	}

    public function getByVPN($vpn)
    {
		try {
            $collection = $this->_prodFactory->create()->getCollection();
            $collection->addAttributeToSelect(array('product_id','copywrite_info','vpn','sku'), 'inner')->addAttributeToFilter('vpn', array('eq' => $vpn));
			$products = [];
            foreach ($collection as $item) {
				$products[] = $item->getData();
            }
            $result = ['products' => $products];
            return json_encode($result);
        } catch (\Exception $e) {
            return false;
        }
    }
	
    public function update($entity_id,$copywrite_info,$vpn)
    {
       
	   try {
            $item = $this->_prodFactory->create();
            $item->load($entity_id);

            if (!$item->getId()) {               
				$this->_amqp->sendMessage('consume-me', ['enity_id not found entity_id = '.$entity_id.'']);				
				return false;
            }			
            
            $item->setCopyWriteInfo($copywrite_info);
			$item->setVpn($vpn);
            $item->save();
			
			$this->_amqp->sendMessage(
				'consume-me',
				['product updated with entity_id = '.$entity_id.' copywrite_info = '.$copywrite_info.' vpn = '.$vpn]
			);
			
			
            return json_encode($item->getData());
        } catch (\Exception $e) {
            return false;
        }
    }
}
