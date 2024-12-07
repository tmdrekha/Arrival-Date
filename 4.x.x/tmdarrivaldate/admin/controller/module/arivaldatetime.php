<?php
namespace Opencart\Admin\Controller\Extension\Tmdarrivaldate\Module;
// Lib Include 
require_once(DIR_EXTENSION.'/tmdarrivaldate/system/library/tmd/system.php');
// Lib Include 
use \Opencart\System\Helper as Helper;
class Arivaldatetime extends \Opencart\System\Engine\Controller {

	private $error = [];

	public function index() {
		
		$this->registry->set('tmd', new  \Tmdarrivaldate\System\Library\Tmd\System($this->registry));
		$keydata=array(
		'code'=>'tmdkey_arivaldatetime',
		'eid'=>'NDQ0NTY=',
		'route'=>'extension/tmdarrivaldate/module/arivaldatetime',
		);
		$arivaldatetime=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->load->language('extension/tmdarrivaldate/module/arivaldatetime');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		
		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs']   = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdarrivaldate/module/arivaldatetime', 'user_token=' . $this->session->data['user_token'])
		];
		
		$data['days'] = [];

		$data['days'][] = [
			'text' => $this->language->get('text_sunday'),
			'value' => 'Sun'
		];
		$data['days'][] = [
			'text' => $this->language->get('text_monday'),
			'value' => 'Mon'
		];
		$data['days'][] = [
			'text' => $this->language->get('text_tuesday'),
			'value' => 'Tue'
		];
		$data['days'][] = [
			'text' => $this->language->get('text_wednesday'),
			'value' => 'Wed'
		];
		$data['days'][] = [
			'text' => $this->language->get('text_thursday'),
			'value' => 'Thu'
		];
		$data['days'][] = [
			'text' => $this->language->get('text_friday'),
			'value' => 'Fri'
		];
		$data['days'][] = [
			'text' => $this->language->get('text_saturday'),
			'value' => 'Sat'
		];
		
        $data['VERSION']   = VERSION;
        $data['save']      = $this->url->link('extension/tmdarrivaldate/module/arivaldatetime|save', 'user_token=' . $this->session->data['user_token']);

		$data['cancel']    = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data['staysave']  = $this->url->link('extension/tmdarrivaldate/module/arivaldatetime', '&status=1&user_token=' . $this->session->data['user_token']);

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['module_arivaldatetime_status'])) {
			$data['module_arivaldatetime_status'] = $this->request->post['module_arivaldatetime_status'];
		} else {
			$data['module_arivaldatetime_status'] = $this->config->get('module_arivaldatetime_status');
		}

		if (isset($this->request->post['arivaldatetime_week'])) {
			$data['arivaldatetime_week'] = $this->request->post['arivaldatetime_week'];
		} elseif(!empty($this->config->get('arivaldatetime_week'))) {
			$data['arivaldatetime_week'] = $this->config->get('arivaldatetime_week');
		} else{
			$data['arivaldatetime_week']=[];
		}

		if (isset($this->request->post['arivaldatetime_multi'])) {
			$data['arivaldatetime_multi'] = $this->request->post['arivaldatetime_multi'];
		} elseif ($this->config->get('arivaldatetime_multi')) {
			$data['arivaldatetime_multi'] = $this->config->get('arivaldatetime_multi');
		} else {
			$data['arivaldatetime_multi'] = [];
		}

		if (isset($this->request->post['arivaldatetime_setting'])) {
			$data['arivaldatetime_setting'] = $this->request->post['arivaldatetime_setting'];
		} elseif ($this->config->get('arivaldatetime_setting')) {
			$data['arivaldatetime_setting'] = $this->config->get('arivaldatetime_setting');
		} else {
			$data['arivaldatetime_setting'] = [];
		}

		if (isset($this->request->post['arivaldatetimeg_ex_status'])) {
			$data['arivaldatetimeg_ex_status'] = $this->request->post['arivaldatetimeg_ex_status'];
		} else {
			$data['arivaldatetimeg_ex_status'] = $this->config->get('arivaldatetimeg_ex_status');
		}

		if (isset($this->request->post['arivaldatetime_express_value'])) {
			$data['express_values'] = $this->request->post['arivaldatetime_express_value'];
		} else {
			$data['express_values'] = $this->config->get('arivaldatetime_express_value');
		}

		if (isset($this->request->post['arivaldatetime_text_setting'])) {
			$data['text_settings'] = $this->request->post['arivaldatetime_text_setting'];
		} else {
			$data['text_settings'] = $this->config->get('arivaldatetime_text_setting');
		}

		$data['weeks']   = [];

		$data['weeks'][] = [
			'text' => $this->language->get('text_sunday'),
			'value' => 0
		];
		$data['weeks'][] = [
			'text' => $this->language->get('text_monday'),
			'value' => 1
		];
		$data['weeks'][] = [
			'text' => $this->language->get('text_tuesday'),
			'value' => 2
		];
		$data['weeks'][] = [
			'text' => $this->language->get('text_wednesday'),
			'value' => 3
		];
		$data['weeks'][] = [
			'text' => $this->language->get('text_thursday'),
			'value' => 4
		];
		$data['weeks'][] = [
			'text' => $this->language->get('text_friday'),
			'value' => 5
		];
		$data['weeks'][] = [
			'text' => $this->language->get('text_saturday'),
			'value' => 6
		];

		$data['timezones'] = [];
		$time_infos = timezone_identifiers_list();
		foreach ($time_infos as $key => $result) {
			$data['timezones'][] = [
				'id'   => $key,
				'name' => $result
			];
		}

		if (isset($this->request->post['arivaldatetime_datestatus'])) {
			$data['arivaldatetime_datestatus'] = $this->request->post['arivaldatetime_datestatus'];
		} else {
			$data['arivaldatetime_datestatus'] = $this->config->get('arivaldatetime_datestatus');
		}

		if (isset($this->request->post['arivaldatetime_timestatus'])) {
			$data['arivaldatetime_timestatus'] = $this->request->post['arivaldatetime_timestatus'];
		} else {
			$data['arivaldatetime_timestatus'] = $this->config->get('arivaldatetime_timestatus');
		}

		if (isset($this->request->post['arivaldatetime_datestart'])) {
			$data['arivaldatetime_datestart'] = $this->request->post['arivaldatetime_datestart'];
		} else {
			$data['arivaldatetime_datestart'] = $this->config->get('arivaldatetime_datestart');
		}

		if (isset($this->request->post['arivaldatetime_dateend'])) {
			$data['arivaldatetime_dateend'] = $this->request->post['arivaldatetime_dateend'];
		} else {
			$data['arivaldatetime_dateend'] = $this->config->get('arivaldatetime_dateend');
		}

		if (isset($this->request->post['arivaldatetime_timezone'])) {
			$data['arivaldatetime_timezone'] = $this->request->post['arivaldatetime_timezone'];
		} else {
			$data['arivaldatetime_timezone'] = $this->config->get('arivaldatetime_timezone');
		}

		if (isset($this->request->post['arivaldatetime_typetime'])) {
			$data['arivaldatetime_typetime'] = $this->request->post['arivaldatetime_typetime'];
		} else if($this->config->get('arivaldatetime_typetime')) {
			$data['arivaldatetime_typetime'] = $this->config->get('arivaldatetime_typetime');
		} else {
			$data['arivaldatetime_typetime'] ='custom';
		}

		if (isset($this->request->post['arivaldatetime_cutoftime'])) {
			$data['arivaldatetime_cutoftime'] = $this->request->post['arivaldatetime_cutoftime'];
		} else {
			$data['arivaldatetime_cutoftime'] = $this->config->get('arivaldatetime_cutoftime');
		}

		if (isset($this->request->post['arivaldatetime_store'])) {
			$data['arivaldatetime_store'] = $this->request->post['arivaldatetime_store'];
		} elseif(!empty($this->config->get('arivaldatetime_store'))) {
			$data['arivaldatetime_store'] = $this->config->get('arivaldatetime_store');
		} else{
			$data['arivaldatetime_store'] = [];
		}

		if (isset($this->request->post['arivaldatetime_datedeactive'])) {
			$data['deactive_dates'] = $this->request->post['arivaldatetime_datedeactive'];
		} elseif(!empty($this->config->get('arivaldatetime_datedeactive'))) {
			$data['deactive_dates'] = $this->config->get('arivaldatetime_datedeactive');
		} else{
			$data['deactive_dates'] = [];
		}

		if (isset($this->request->post['arivaldatetime_delivery_time'])) {
			$data['deli_times'] = $this->request->post['arivaldatetime_delivery_time'];
		} elseif(!empty($this->config->get('arivaldatetime_delivery_time'))) {
			$data['deli_times'] = $this->config->get('arivaldatetime_delivery_time');
		} else{
			$data['deli_times'] = [];
		}
		
		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('setting/store');

		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		$data['datestatus']   = [];

		$data['datestatus'][] = [
			'text' => $this->language->get('text_dis_req_yes'),
			'value' => 2
		];
		$data['datestatus'][] = [
			'text' => $this->language->get('text_dis_yes'),
			'value' => 1
		];
		$data['datestatus'][] = [
			'text' => $this->language->get('text_disable'),
			'value' => 0
		];
        
        //Category
		$this->load->model('catalog/category');
		if (isset($this->request->post['arivaldatetime_category'])) {
			$tmd_categories = $this->request->post['arivaldatetime_category'];
		} elseif ($this->config->get('arivaldatetime_category')) {
			$tmd_categories = $this->config->get('arivaldatetime_category');
		} else {
			$tmd_categories = [];
		}

		$data['tmdcategories'] = [];

		foreach ($tmd_categories as $category_id) {
			$cate_info = $this->model_catalog_category->getCategory($category_id);

			if ($cate_info) {
				$data['tmdcategories'][] = [
					'category_id' => $cate_info['category_id'],
					'name'        => ($cate_info['path']) ? $cate_info['path'] . ' &gt; ' . $cate_info['name'] : $cate_info['name']
				];
			}
		}
        

        //Product
		$this->load->model('catalog/product');
		if (isset($this->request->post['arivaldatetime_product'])) {
			$tmd_products = $this->request->post['arivaldatetime_product'];
		} elseif ($this->config->get('arivaldatetime_product')) {
			$tmd_products = $this->config->get('arivaldatetime_product');
		} else {
			$tmd_products = [];
		}

		$data['tmd_products'] = [];

		foreach ($tmd_products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['tmd_products'][] = [
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				];
			}
		}
        
        //Manufacturer
		$this->load->model('catalog/manufacturer');
		if (isset($this->request->post['arivaldatetime_manufacturer'])) {
			$tmd_manufacturers = $this->request->post['arivaldatetime_manufacturer'];
		} elseif ($this->config->get('arivaldatetime_manufacturer')) {
			$tmd_manufacturers = $this->config->get('arivaldatetime_manufacturer');
		} else {
			$tmd_manufacturers = [];
		}

		$data['tmd_manufacturers'] = [];

		foreach ($tmd_manufacturers as $manufacturer_id) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

			if ($manufacturer_info) {
				$data['tmd_manufacturers'][] = [
					'manufacturer_id' => $manufacturer_info['manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($manufacturer_info['name'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdarrivaldate/module/arivaldatetime', $data));
	}

    public function save(): void {
		$this->load->language('extension/tmdarrivaldate/module/arivaldatetime');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdarrivaldate/module/arivaldatetime')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		  }

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}
		
		$arivaldatetime=$this->config->get('tmdkey_arivaldatetime');
		if (empty(trim($arivaldatetime))) {			
		$json['error'] ='Module will Work after add License key!';
		}
		
		if (!$json) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('module_arivaldatetime', $this->request->post);
			$this->model_setting_setting->editSetting('arivaldatetime', $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		   }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_arivaldatetime',
			'eid'=>'NDQ0NTY=',
			'route'=>'extension/tmdarrivaldate/module/arivaldatetime',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new  \Tmdarrivaldate\System\Library\Tmd\System($this->registry));
		
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
      


public function install(): void {
	$this->load->model('setting/event');
		
	// Fix permissions
	$this->load->model('user/user_group');
	$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdarrivaldate/module/arivaldatetime');
	$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdarrivaldate/module/arivaldatetime');

		//menu event
	   if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdarrivaldate/module/arivaldatetime.menu';
		}else{
			$eventaction='extension/tmdarrivaldate/module/arivaldatetime|menu';
		}
		
       $eventrequest=[
			'code'		  => 'tmd_ArivaldateMenu',
			'description' => 'TMD Arivaldate Menu',
			'trigger'	  => 'admin/view/common/column_left/before',
			'action'	  => $eventaction,
			'status'	  => '1',
			'sort_order'  => '1',
		];

		if(VERSION=='4.0.0.0'){
	         $this->model_setting_event->addEvent('tmd_ArivaldateMenu', 'TMD Arivaldate Menu', 'admin/view/common/column_left/before','extension/tmdarrivaldate/module/arivaldatetime|menu', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

     

        // admin(sale/order_info) view option event
        if(VERSION >= '4.0.2.0'){
          $eventaction ='extension/tmdarrivaldate/module/arivaldatetime.tmdarivaldateorderinfo';
        }else{
          $eventaction ='extension/tmdarrivaldate/module/arivaldatetime|tmdarivaldateorderinfo';
        }
        $eventrequest=[
                'code'        => 'tmd_ArivaldateSaleinfo',
                'description' => 'TMD Arivaldate sale order Info',
                'trigger'     => 'admin/view/sale/order_info/before',
                'action'      => $eventaction,
                'status'      => '1',
                'sort_order'  => '1',
                ];
                
        if(VERSION=='4.0.0.0'){
          $this->model_setting_event->addEvent('tmd_ArivaldateSaleinfo', 'TMD Arivaldate sale order Info', 'admin/view/sale/order_info/before','extension/tmdarrivaldate/module/arivaldatetime|tmdarivaldateorderinfo', true, 1);
        }else{
          $this->model_setting_event->addEvent($eventrequest);
        }



       // Front Product events(view)
		if(VERSION>='4.0.2.0'){
            $eventaction='extension/tmdarrivaldate/tmd/arivaldatetime.productcontroller';
        }else{
            $eventaction='extension/tmdarrivaldate/tmd/arivaldatetime|productcontroller';
        }
       
        $eventrequest=[
                'code'		  => 'tmd_ArivaldateCatalogproductView',
                'description' => 'TMD Arivaldate Catalogproduct View',
                'trigger'	  => 'catalog/view/product/product/before',
                'action'	  => $eventaction,
                'status'	  => '1',
                'sort_order'  => '1',
                ];
                
        if(VERSION=='4.0.0.0'){
        $this->model_setting_event->addEvent('tmd_ArivaldateCatalogproductView', 'TMD Arivaldate Catalogproduct View', 'catalog/view/product/product/before', 'extension/tmdarrivaldate/tmd/arivaldatetime|productcontroller', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}


      

      	//catalog product(thumb)  event
	    if(VERSION>='4.0.2.0'){
          $eventaction = 'extension/tmdarrivaldate/tmd/arivaldatetime.productthumb';
		}else{
         $eventaction = 'extension/tmdarrivaldate/tmd/arivaldatetime|productthumb';
		}	

		$eventrequest=[
					'code'       => 'tmd_ArivaldateProductthumb',
					'description'=> 'TMD Arivaldate Product thumb',
					'trigger'    => 'catalog/view/product/thumb/before',
					'action'     => $eventaction,
					'status'     => '1',
					'sort_order' => '1',
				];
				
		if(VERSION=='4.0.0.0'){
	       $this->model_setting_event->addEvent('tmd_ArivaldateProductthumb', 'TMD Arivaldate Product thumb', 'catalog/view/product/thumb/before', 'extension/tmdarrivaldate/tmd/arivaldatetime|productthumb', true, 1);
		}else{
		   $this->model_setting_event->addEvent($eventrequest);
		}	

     
     }

  public function uninstall(): void {
     	$this->load->model('setting/event');

        //Delete Event
        $this->model_setting_event->deleteEventByCode('tmd_ArivaldateCatalogproductView');
        $this->model_setting_event->deleteEventByCode('tmd_ArivaldateMenu');
      
        $this->model_setting_event->deleteEventByCode('tmd_ArivaldateSaleinfo');
        $this->model_setting_event->deleteEventByCode('tmd_ArivaldateProductthumb');
       

		// Fix permissions
		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdarrivaldate/module/arivaldatetime');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdarrivaldate/module/arivaldatetime');
		
	   }


 public function menu(string&$route, array&$args, mixed&$output):void {
	   $modulestatus=$this->config->get('module_arivaldatetime_status');
		if(!empty($modulestatus)){
			$this->load->language('extension/tmdarrivaldate/module/arivaldatetime');
			
			$arrivaltime = [];
		
			if ($this->user->hasPermission('access', 'extension/tmdarrivaldate/module/arivaldatetime')) {		
				$arrivaltime[] = [
					'name'	   => $this->language->get('text_arrivaltime'),
					'href'     => $this->url->link('extension/tmdarrivaldate/module/arivaldatetime', 'user_token=' . $this->session->data['user_token']),
					'children' => []	
				];					
			}	
				
			if ($arrivaltime) {					
				$args['menus'][] = [
					'id'       => 'menu-timeslot',
					'icon'	   => 'fas fa fa-clock fw', 
					'name'	   => $this->language->get('text_arrivaltimeslote'),
					'href'     => '',
					'children' => $arrivaltime
				];		
			}
		}
	}


	public function tmdarivaldateorderinfo(string &$route, array &$args, mixed &$output): void {
        $modulestatus = $this->config->get('module_arivaldatetime_status');
        if(!empty($modulestatus)){
            foreach($args['order_products'] as $key => $result){
                $orderproduct_ids = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$args['order_id'] . "' AND product_id ='".$result['product_id']."'"); 
       
                foreach($orderproduct_ids->rows as $key2 => $orderproduct_id){
      
                $p_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$args['order_id'] . "' AND order_product_id = '" .$orderproduct_id['order_product_id'] . "'");
                    foreach($p_query->rows as $key2 =>  $value){
                        if(!empty($value)){
                            $args['order_products'][$key]['option'][$key2]['value']  = $value['value'];         
                            $args['order_products'][$key]['option'][$key2]['name']  = $value['name'];       
                        }
                    }
                }
            }
        }
    }



}
