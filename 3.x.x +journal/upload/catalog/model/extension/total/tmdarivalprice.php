<?php
 class ModelExtensionTotalTmdarivalprice extends Model {
   public function getTotal($total) {
	 $module_tmdarivaldatetime_status = $this->config->get('module_tmdarivaldatetime_status');
		if(!empty($module_tmdarivaldatetime_status)){	
	 $this->load->language('extension/total/tmdarivalprice');	
        $arivaltotal = 0;
        $option_data = [];

	$products = $this->cart->getProducts();
    $module_product_id = $this->config->get('module_tmdarivaldatetime_product');

	foreach($products as $product) {
	  $cart_query      = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cart` WHERE cart_id='".$product['cart_id']."'");
	  $product_options = (array)json_decode($cart_query->row['option'], true);
	foreach ($product_options as $product_option_id => $value) {
	  if(!empty($product_option_id == 'express_delivery')) {
          $arivaltotal +=  $value;
	     }
	    }
	  }

    if(!empty($this->config->get('total_tmdarivalprice_title')[$this->config->get('config_language_id')]['name'])){
	   $title = ($this->config->get('total_tmdarivalprice_title')[$this->config->get('config_language_id')]['name']);
	  }else{
	  $title  =  $this->language->get('text_tmdarivalprice_title');
	  }
       $total['totals'][] = [
			'extension'  => 'tmdarrivaldate',
			'code'       => 'tmdarivalprice',
			'title'      => $title,
			'value'      => $arivaltotal,
			'sort_order' => (int)$this->config->get('total_tmdarivalprice_sort_order')
		];

        $total['total'] += $arivaltotal;
	
	  }
  }
}