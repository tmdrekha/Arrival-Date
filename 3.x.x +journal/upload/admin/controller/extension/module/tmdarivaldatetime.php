<?php
//lib
require_once(DIR_SYSTEM.'library/tmd/system.php');
//lib
class ControllerExtensionModuleTmdarivaldatetime extends Controller {
	private $error = array();

	public function index() {
		$this->registry->set('tmd', new TMD($this->registry));
		$keydata=array(
		'code'=>'tmdkey_tmdarivaldatetime',
		'eid'=>'NDQ0NTY=',
		'route'=>'extension/module/tmdarivaldatetime',
		);
		$tmdarivaldatetime=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		
		$this->load->language('extension/module/tmdarivaldatetime');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_tmdarivaldatetime', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			if(isset($this->request->get['status'])){
				$this->response->redirect($this->url->link('extension/module/tmdarivaldatetime', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/tmdarivaldatetime', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['days'] = array();

		$data['days'][] = array(
			'text' => $this->language->get('text_sunday'),
			'value' => 'Sun'
		);
		$data['days'][] = array(
			'text' => $this->language->get('text_monday'),
			'value' => 'Mon'
		);
		$data['days'][] = array(
			'text' => $this->language->get('text_tuesday'),
			'value' => 'Tue'
		);
		$data['days'][] = array(
			'text' => $this->language->get('text_wednesday'),
			'value' => 'Wed'
		);
		$data['days'][] = array(
			'text' => $this->language->get('text_thursday'),
			'value' => 'Thu'
		);
		$data['days'][] = array(
			'text' => $this->language->get('text_friday'),
			'value' => 'Fri'
		);
		$data['days'][] = array(
			'text' => $this->language->get('text_saturday'),
			'value' => 'Sat'
		);
		
		$data['action'] = $this->url->link('extension/module/tmdarivaldatetime', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['staysave'] = $this->url->link('extension/module/tmdarivaldatetime', '&status=1&user_token=' . $this->session->data['user_token'], true);

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['module_tmdarivaldatetime_status'])) {
			$data['module_tmdarivaldatetime_status'] = $this->request->post['module_tmdarivaldatetime_status'];
		} else {
			$data['module_tmdarivaldatetime_status'] = $this->config->get('module_tmdarivaldatetime_status');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_week'])) {
			$data['module_tmdarivaldatetime_week'] = $this->request->post['module_tmdarivaldatetime_week'];
		} elseif(!empty($this->config->get('module_tmdarivaldatetime_week'))) {
			$data['module_tmdarivaldatetime_week'] = $this->config->get('module_tmdarivaldatetime_week');
		} else{
			$data['module_tmdarivaldatetime_week']=array();
		}

		if (isset($this->request->post['module_tmdarivaldatetime_multi'])) {
			$data['module_tmdarivaldatetime_multi'] = $this->request->post['module_tmdarivaldatetime_multi'];
		} elseif ($this->config->get('module_tmdarivaldatetime_multi')) {
			$data['module_tmdarivaldatetime_multi'] = $this->config->get('module_tmdarivaldatetime_multi');
		} else {
			$data['module_tmdarivaldatetime_multi'] = array();
		}

		if (isset($this->request->post['module_tmdarivaldatetime_setting'])) {
			$data['module_tmdarivaldatetime_setting'] = $this->request->post['module_tmdarivaldatetime_setting'];
		} elseif ($this->config->get('module_tmdarivaldatetime_setting')) {
			$data['module_tmdarivaldatetime_setting'] = $this->config->get('module_tmdarivaldatetime_setting');
		} else {
			$data['module_tmdarivaldatetime_setting'] = array();
		}

		if (isset($this->request->post['module_tmdarivaldatetimeg_ex_status'])) {
			$data['module_tmdarivaldatetimeg_ex_status'] = $this->request->post['module_tmdarivaldatetimeg_ex_status'];
		} else {
			$data['module_tmdarivaldatetimeg_ex_status'] = $this->config->get('module_tmdarivaldatetimeg_ex_status');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_express_value'])) {
			$data['express_values'] = $this->request->post['module_tmdarivaldatetime_express_value'];
		} else {
			$data['express_values'] = $this->config->get('module_tmdarivaldatetime_express_value');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_text_setting'])) {
			$data['text_settings'] = $this->request->post['module_tmdarivaldatetime_text_setting'];
		} else {
			$data['text_settings'] = $this->config->get('module_tmdarivaldatetime_text_setting');
		}

		$data['weeks']   = array();

		$data['weeks'][] = array(
			'text' => $this->language->get('text_sunday'),
			'value' => 0
		);
		$data['weeks'][] = array(
			'text' => $this->language->get('text_monday'),
			'value' => 1
		);
		$data['weeks'][] = array(
			'text' => $this->language->get('text_tuesday'),
			'value' => 2
		);
		$data['weeks'][] = array(
			'text' => $this->language->get('text_wednesday'),
			'value' => 3
		);
		$data['weeks'][] = array(
			'text' => $this->language->get('text_thursday'),
			'value' => 4
		);
		$data['weeks'][] = array(
			'text' => $this->language->get('text_friday'),
			'value' => 5
		);
		$data['weeks'][] = array(
			'text' => $this->language->get('text_saturday'),
			'value' => 6
		);

		$data['timezones'] = array();
		$time_infos = timezone_identifiers_list();
		foreach ($time_infos as $key => $result) {
			$data['timezones'][] = array(
				'id'   => $key,
				'name' => $result
			);
		}

		if (isset($this->request->post['module_tmdarivaldatetime_datestatus'])) {
			$data['module_tmdarivaldatetime_datestatus'] = $this->request->post['module_tmdarivaldatetime_datestatus'];
		} else {
			$data['module_tmdarivaldatetime_datestatus'] = $this->config->get('module_tmdarivaldatetime_datestatus');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_timestatus'])) {
			$data['module_tmdarivaldatetime_timestatus'] = $this->request->post['module_tmdarivaldatetime_timestatus'];
		} else {
			$data['module_tmdarivaldatetime_timestatus'] = $this->config->get('module_tmdarivaldatetime_timestatus');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_datestart'])) {
			$data['module_tmdarivaldatetime_datestart'] = $this->request->post['module_tmdarivaldatetime_datestart'];
		} else {
			$data['module_tmdarivaldatetime_datestart'] = $this->config->get('module_tmdarivaldatetime_datestart');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_dateend'])) {
			$data['module_tmdarivaldatetime_dateend'] = $this->request->post['module_tmdarivaldatetime_dateend'];
		} else {
			$data['module_tmdarivaldatetime_dateend'] = $this->config->get('module_tmdarivaldatetime_dateend');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_timezone'])) {
			$data['module_tmdarivaldatetime_timezone'] = $this->request->post['module_tmdarivaldatetime_timezone'];
		} else {
			$data['module_tmdarivaldatetime_timezone'] = $this->config->get('module_tmdarivaldatetime_timezone');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_typetime'])) {
			$data['module_tmdarivaldatetime_typetime'] = $this->request->post['module_tmdarivaldatetime_typetime'];
		} else if($this->config->get('module_tmdarivaldatetime_typetime')) {
			$data['module_tmdarivaldatetime_typetime'] = $this->config->get('module_tmdarivaldatetime_typetime');
		} else {
			$data['module_tmdarivaldatetime_typetime'] ='custom';
		}

		if (isset($this->request->post['module_tmdarivaldatetime_cutoftime'])) {
			$data['module_tmdarivaldatetime_cutoftime'] = $this->request->post['module_tmdarivaldatetime_cutoftime'];
		} else {
			$data['module_tmdarivaldatetime_cutoftime'] = $this->config->get('module_tmdarivaldatetime_cutoftime');
		}

		if (isset($this->request->post['module_tmdarivaldatetime_store'])) {
			$data['module_tmdarivaldatetime_store'] = $this->request->post['module_tmdarivaldatetime_store'];
		} elseif(!empty($this->config->get('module_tmdarivaldatetime_store'))) {
			$data['module_tmdarivaldatetime_store'] = $this->config->get('module_tmdarivaldatetime_store');
		} else{
			$data['module_tmdarivaldatetime_store']=array();
		}

		if (isset($this->request->post['module_tmdarivaldatetime_datedeactive'])) {
			$data['deactive_dates'] = $this->request->post['module_tmdarivaldatetime_datedeactive'];
		} elseif(!empty($this->config->get('module_tmdarivaldatetime_datedeactive'))) {
			$data['deactive_dates'] = $this->config->get('module_tmdarivaldatetime_datedeactive');
		} else{
			$data['deactive_dates']=array();
		}

		if (isset($this->request->post['module_tmdarivaldatetime_delivery_time'])) {
			$data['deli_times'] = $this->request->post['module_tmdarivaldatetime_delivery_time'];
		} elseif(!empty($this->config->get('module_tmdarivaldatetime_delivery_time'))) {
			$data['deli_times'] = $this->config->get('module_tmdarivaldatetime_delivery_time');
		} else{
			$data['deli_times']=array();
		}
		
		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}

		$data['datestatus'] = array();

		$data['datestatus'][] = array(
			'text' => $this->language->get('text_dis_req_yes'),
			'value' => 2
		);
		$data['datestatus'][] = array(
			'text' => $this->language->get('text_dis_yes'),
			'value' => 1
		);
		$data['datestatus'][] = array(
			'text' => $this->language->get('text_disable'),
			'value' => 0
		);

		$this->load->model('catalog/category');
		if (isset($this->request->post['module_tmdarivaldatetime_category'])) {
			$tmd_categories = $this->request->post['module_tmdarivaldatetime_category'];
		} elseif ($this->config->get('module_tmdarivaldatetime_category')) {
			$tmd_categories = $this->config->get('module_tmdarivaldatetime_category');
		} else {
			$tmd_categories = array();
		}
		$data['tmdcategories'] = array();

		foreach ($tmd_categories as $category_id) {
			$cate_info = $this->model_catalog_category->getCategory($category_id);

			if ($cate_info) {
				$data['tmdcategories'][] = array(
					'category_id'	=> $cate_info['category_id'],
					'name'        	=> ($cate_info['path']) ? $cate_info['path'] . ' &gt; ' . $cate_info['name'] : $cate_info['name']
				);
			}
		}

		$this->load->model('catalog/product');
		if (isset($this->request->post['module_tmdarivaldatetime_product'])) {
			$tmd_products = $this->request->post['module_tmdarivaldatetime_product'];
		} elseif ($this->config->get('module_tmdarivaldatetime_product')) {
			$tmd_products = $this->config->get('module_tmdarivaldatetime_product');
		} else {
			$tmd_products = array();
		}

		$data['tmd_products'] = array();

		foreach ($tmd_products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['tmd_products'][] = array(
					'product_id'	=> $product_info['product_id'],
					'name'        	=> $product_info['name']
				);
			}
		}

		$this->load->model('catalog/manufacturer');
		if (isset($this->request->post['module_tmdarivaldatetime_manufacturer'])) {
			$tmd_manufacturers = $this->request->post['module_tmdarivaldatetime_manufacturer'];
		} elseif ($this->config->get('module_tmdarivaldatetime_manufacturer')) {
			$tmd_manufacturers = $this->config->get('module_tmdarivaldatetime_manufacturer');
		} else {
			$tmd_manufacturers = array();
		}

		$data['tmd_manufacturers'] = array();

		foreach ($tmd_manufacturers as $manufacturer_id) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

			if ($manufacturer_info) {
				$data['tmd_manufacturers'][] = array(
					'manufacturer_id' => $manufacturer_info['manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($manufacturer_info['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/tmdarivaldatetime', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/tmdarivaldatetime')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$tmdarivaldatetime=$this->config->get('tmdkey_tmdarivaldatetime');
		if (empty(trim($tmdarivaldatetime))) {			
		$this->session->data['warning'] ='Module will Work after add License key!';
		$this->response->redirect($this->url->link('extension/module/tmdarivaldatetime', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		return !$this->error;
	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmdarivaldatetime',
			'eid'=>'NDQ0NTY=',
			'route'=>'extension/module/tmdarivaldatetime',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new TMD($this->registry));
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
