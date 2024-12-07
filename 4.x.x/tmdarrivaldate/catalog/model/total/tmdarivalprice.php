<?php
namespace Opencart\Catalog\Model\Extension\Tmdarrivaldate\Total;
class Tmdarivalprice extends \Opencart\System\Engine\Model {

	public function getTotal(array &$totals, array &$taxes, float &$total): void {
	  $modulestatus=$this->config->get('module_arivaldatetime_status');
	   if(!empty($modulestatus)){
        $arivaltotal = 0;
        $option_data =[];

	$products = $this->cart->getProducts();
    $module_product_id = $this->config->get('arivaldatetime_product');

	foreach($products as $product) {
	$cart_query   = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cart` WHERE cart_id='".$product['cart_id']."'");
	if(isset($cart_query->row['option'])){
	$product_options = (array)json_decode($cart_query->row['option'], true);
	foreach ($product_options as $product_option_id => $value) {
	  if(!empty($product_option_id == 'express_delivery')) {
          $arivaltotal +=  $value;
	     }
	   }
		
	  }
	  }
	
	 $this->load->language('extension/tmdarrivaldate/tmd/arivaldatetime'); 
    if(!empty($this->config->get('total_tmdarivalprice_title')[$this->config->get('config_language_id')]['name'])){
	   $title =($this->config->get('total_tmdarivalprice_title')[$this->config->get('config_language_id')]['name']);
	}else{
	  $title =$this->language->get('text_express_delivery');
	}

        $totals[] = [
			'extension'  => 'tmdarrivaldate',
			'code'       => 'tmdarivalprice',
			'title'      => $title,
			'value'      => $arivaltotal,
			'sort_order' => (int)$this->config->get('total_tmdarivalprice_sort_order')
		];

        $total += $arivaltotal;
	
	 }
	}
}