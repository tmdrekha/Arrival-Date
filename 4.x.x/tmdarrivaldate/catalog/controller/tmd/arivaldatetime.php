<?php
namespace Opencart\Catalog\Controller\Extension\Tmdarrivaldate\Tmd;
use \Opencart\System\Helper as Helper;
class Arivaldatetime extends \Opencart\System\Engine\Controller {

	public function index() {

		$this->load->language('extension/tmdarrivaldate/tmd/arivaldatetime');
		

		if(!empty($this->request->get['product_id'])){
			$data['product_id'] = $this->request->get['product_id'];
		}else{
			$data['product_id'] = '';
		}

		$data['tmddeldatetime_status']  = $this->config->get('module_arivaldatetime_status');
		$data['want_to_comment']        = $this->config->get('arivaldatetime_want_to_comment');
		$data['datestatus'] 			      = $this->config->get('arivaldatetime_datestatus');
		$data['timereq'] 	    		      = $this->config->get('arivaldatetime_timestatus');
		$data['typetimes'] 				      = $this->config->get('arivaldatetime_typetime');


		$data['datedeactives'] = [];

		$datedeactives = $this->config->get('arivaldatetime_datedeactive');
		if(!empty($datedeactives)){
		foreach ($datedeactives as $deactive) {
			$data['datedeactives'][]='"'.$deactive['de_date'].'"';
		}
		}

		if(!empty($data['datedeactives'])) {
			$data['datedeactive'] = implode(',',$data['datedeactives']);
		}else{
			$data['datedeactive'] ='';
		}

		$data['selecttimes'] = [];
		$deliverytimes = $this->config->get('arivaldatetime_delivery_time');
		if(!empty($deliverytimes)){
		foreach ($deliverytimes as $time) {
			$data['selecttimes'][] = array(
			'open_time' => $time['open_time'],
			'close_time' => $time['close_time']
			);
		}
		}

		if(!empty($this->config->get('arivaldatetime_store'))) {
			$data['tmddeldatetime_store'] = $this->config->get('arivaldatetime_store');
		}else{
			$data['tmddeldatetime_store'] = [];
		}

		$data['configstore']  = $this->config->get('config_store_id');
		if (in_array($data['configstore'], $data['tmddeldatetime_store'])) {
			$data['tmdstore'] = $data['tmddeldatetime_store'];
		} else {
			$data['tmdstore'] ='';
		}

		$tmdtimezone = $this->config->get('arivaldatetime_timezone');
		date_default_timezone_set($tmdtimezone);

		$cutoftime 	= $this->config->get('arivaldatetime_cutoftime');

		if(!empty($this->config->get('arivaldatetime_datestart'))){
		$datestart 	= $this->config->get('arivaldatetime_datestart');
	    }else{
        $datestart 	= 0;
	    }
	    
		$dateend 	      = $this->config->get('arivaldatetime_dateend');

		$data['today'] 	= date('Y-m-d');
		
		if ((date('H') >= $cutoftime) && $cutoftime!='0') {

			$i=$datestart+1;
			$data['newdate']      = date('Y-m-d', strtotime(date('y-m-d') . ' +'.$i.' day'));
			$data['newdatecount'] = $i;
		} else {
			$data['newdate']      = date('Y-m-d', strtotime(date('y-m-d') . ' +'.$datestart.' day'));
			$data['newdatecount'] = $datestart;
		}
		
		$data['enddate'] = date('Y-m-d', strtotime(date('y-m-d') . ' +'.$dateend.' day'));
		$data['newdate'] = $this->checkdateholiday($data['newdate']);

		$tmddelilables = $this->config->get('arivaldatetime_multi');

		if(!empty($this->config->get('arivaldatetime_week'))) {
			$data['tmddeldatetime_week'] = implode(',',$this->config->get('arivaldatetime_week'));
		}else{
			$data['tmddeldatetime_week'] ='';
		}

		if(!empty($tmddelilables[$this->config->get('config_language_id')]['datetext'])){
			$data['entry_deliverydate'] = $tmddelilables[$this->config->get('config_language_id')]['datetext'];
		} else {
			$data['entry_deliverydate'] = $this->language->get('entry_deliverydate');
		}

		if(!empty($tmddelilables[$this->config->get('config_language_id')]['timetext'])){
			$data['entry_deliverytime'] = $tmddelilables[$this->config->get('config_language_id')]['timetext'];
		} else {
			$data['entry_deliverytime'] = $this->language->get('entry_deliverytime');
		}

		if(!empty($tmddelilables[$this->config->get('config_language_id')]['datetimeheading'])){
			$data['heading_title'] = $tmddelilables[$this->config->get('config_language_id')]['datetimeheading'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}


		$data['tmdsettingsstatus'] = $this->config->get('arivaldatetimeg_ex_status');
		if(!empty($data['tmdsettingsstatus'])){
			$tmddelsettings = $this->config->get('arivaldatetime_setting');
			if(!empty($tmddelsettings[$this->config->get('config_language_id')]['text_heading'])){
				$data['entry_delexp_heading'] = $tmddelsettings[$this->config->get('config_language_id')]['text_heading'];
			} else {
				$data['entry_delexp_heading'] = $this->language->get('entry_delexp_heading');
			}

			$tmddelexpressvalue =$this->config->get('arivaldatetime_express_value');
			if(!empty($tmddelexpressvalue)){
				foreach ($tmddelexpressvalue as $expresult) {

					if(!empty($expresult[$this->config->get('config_language_id')]['express_text'])){
						$express_text = $expresult[$this->config->get('config_language_id')]['express_text'];
					} else {
						$express_text = '';
					}

					$express_price    = $this->currency->format($expresult['express_price'], $this->session->data['currency']);
					$data['tmddelexpressvalues'][] = [					
						'express_text'    => $express_text,
						'express_price'   => $express_price,
						'express_charges' => $expresult['express_price']
					];
				}
			}

			$tmddeltextsetting = $this->config->get('arivaldatetime_text_setting');
			if(!empty($tmddeltextsetting)){
				foreach ($tmddeltextsetting as $textsetting) {
					$text = html_entity_decode($textsetting[$this->config->get('config_language_id')]['tc_text']);

					if(!empty($text)){
						$tc_text = $text;
					} else {
						$tc_text = '';
					}

					$data['tmddeltextsettings'][] = [
						'tc_text'     => $tc_text,
						'sort_order'  => $textsetting['tc_sort_order']
					];
				}
			}
		}

		return $this->load->view('extension/tmdarrivaldate/tmd/arivaldatetime', $data);
	}
	
	public function loadtime() {
		$this->load->model('extension/tmdarrivaldate/tmd/arivaldatetime');
		$tmdtimezone = $this->config->get('arivaldatetime_timezone');
		date_default_timezone_set($tmdtimezone);

		$json['deliverytimes'] = [];

		if(isset($this->request->get['day'])){
			$day = $this->request->get['day'];
		} else {
			$day = '';
		}

		$deliverytimes = $this->config->get('arivaldatetime_delivery_time');

		if(!empty($deliverytimes)){
		foreach ($deliverytimes as $result) {
			if(isset($this->request->get['day'])){
				$postdate        = $this->request->get['day'];
				$day             = $this->request->get['day'];
				$day_of_week     = $day;
				$day_of_week     = date('D', strtotime($day));
			
				if($day_of_week == $result['days']){
					$dayofweek    = date('Y-m-d', strtotime($day));
					$currentdate  = date('Y-m-d', strtotime($day));
					$day_ofweek   = date('Y-m-d');
					$day_ofweek1  = date('Y-m-d', strtotime(date('Y-m-d').' +1 day'));
				    $current_time = date('H:i:s');

					$text         = !empty($result['text'])?$result['text']:false;

					if(!empty($result['ex_charges'])) {
						$extracharges1   = $result['ex_charges'];
						$extracharges    = '( '.$this->currency->format($extracharges1, $this->session->data['currency']).' )';
					} else {
						$extracharges    = '';
						$extracharges1   = false;
					}
					
					$open_sort_time  = $result['open_time'];
					$close_sort_time = $result['close_time'];
					
					if($postdate==date('Y-m-d')){
							
						if($open_sort_time == '00-00-00' || $open_sort_time < $current_time && $close_sort_time == '00-00-00' || $close_sort_time > $current_time){	
							if(empty($text)){
								$text=$open_sort_time.'-'.$close_sort_time;
							}
								
								$json['deliverytimes'][] = [
									'open_time'  => $open_sort_time,
									'close_time' => $close_sort_time,
									'text'       => $text,
									'extra_val'  => $extracharges,
									'charges'    => $extracharges1,
								];
					}   }else{
								
							if(empty($text)){
								$text=$open_sort_time.'-'.$close_sort_time;
							}

							$json['deliverytimes'][] = [
								'open_time'  => $open_sort_time,
								'close_time' => $close_sort_time,
								'text'       => $text,
								'extra_val'  => $extracharges,
								'charges'    => $extracharges1,
							];
						}
						
				    }
			    } 
		    }
	    }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

private function checkdateholiday($date){
		$datedeactives = $this->config->get('arivaldatetime_datedeactive');
		$datedeactiv = [];
		if(!empty($datedeactives)){
			foreach ($datedeactives as $deactive) {
				$datedeactiv[]=$deactive['de_date'];
			}
		}
		
		if(in_array($date,$datedeactiv)){
			$newdate = date('Y-m-d', strtotime($date . ' +1 day'));
			$date    =    $this->checkdateholiday($newdate);
		}

		$module_tmdarivaldatetime_week = $this->config->get('arivaldatetime_week');
		$weekholiday = [];
		if(!empty($module_tmdarivaldatetime_week)){
			foreach ($module_tmdarivaldatetime_week as $tmdarivaldatetime_week) {
				$weekholiday[] = $tmdarivaldatetime_week;
			}
		}
		 
		$day  = date('N', strtotime($date));
		if($day==7){
		  $day=0;
		}

		if(in_array($day,$weekholiday)){
			$newdate = date('Y-m-d', strtotime($date . ' +1 day'));
			$date    = $this->checkdateholiday($newdate);	
		}
	
		return $date;
	}

	public function addprice() {
		$json = [];
		$this->load->model('extension/tmdarrivaldate/tmd/arivaldatetime');

		if(!empty($this->request->get['product_id'])){
			$product_id = $this->request->get['product_id'];
		}else{
			$product_id = '';
		}

		if(!empty($this->request->post['option']['deliverytime'])){
			$deliveryvalue = $this->request->post['option']['deliverytime'];
		}else{
			$deliveryvalue = '';
		}

		$express_delivery = 0;
		if(!empty($this->request->post['option']['express_delivery'])){
			$express_delivery = $this->request->post['option']['express_delivery'];
		}

		 $open_time  = '';
		 $close_time = ''; 

		if (!empty($deliveryvalue)) {
			$resultdata = explode('-', $deliveryvalue);
			if(!empty($resultdata[0])) {
	          $open_time = str_replace(' ', '', $resultdata[0]);
	        } 

	        if(!empty($resultdata[1])) {
	          $close_time = str_replace(' ', '', $resultdata[1]);
	        }
	    }

	    $extracharges=[];

		$deliverytimes = $this->config->get('arivaldatetime_delivery_time');
		if(!empty($deliverytimes)){
		foreach ($deliverytimes as $result) {
			if(!empty($result['ex_charges'])) {
				$extracharges[$result['open_time'].'-'.$result['close_time']]=$result['ex_charges'];
			}
		}
		}

		$ex_charges ='0';
		if(!empty($extracharges[$open_time.'-'.$close_time])) {
			$ex_charges = $extracharges[$open_time.'-'.$close_time];
		}
		
	    $this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		$pprice       ='';
		$specialprice ='';
		if(!empty($product_info)){
			
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $product_info['price'];
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $product_info['special'];
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax  = $this->currency->format($this->tax->calculate((float)$special ? $special : $price, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$tax1 = $this->tax->calculate((float)$special ? $special : $price, $product_info['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$tax  = false;
					$tax1 = false;
				}

				$json['ex_charges'] = $this->currency->format($ex_charges+$express_delivery, $this->session->data['currency']);
				$json['ex_price']   = $this->currency->format($tax1+$ex_charges+$express_delivery, $this->session->data['currency']);
				
		
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	// Events Start

	public function productcontroller(string &$route, array &$args, mixed &$output): void {
			$modulestatus=$this->config->get('module_arivaldatetime_status');
			if(!empty($modulestatus)){
	        $this->load->model('extension/tmdarrivaldate/tmd/arivaldatetime');
	        $this->load->language('extension/tmdarrivaldate/tmd/arivaldatetime');
	        $this->load->model('catalog/product');

		   /*Delivery Date*/
			$datestatus 	         = $this->config->get('arivaldatetime_datestatus');
			$args['language']       = $this->config->get('config_language');
		
			$args['arivaldatetime'] = $this->load->controller('extension/tmdarrivaldate/tmd/arivaldatetime');
			$this->load->model('extension/tmdarrivaldate/tmd/arivaldatetime');
			$this->load->language('extension/tmdarrivaldate/tmd/arivaldatetime');
			$args['link_status']    = $this->model_extension_tmdarrivaldate_tmd_arivaldatetime->getProductlinkStatus($args['product_id']);
	

          //Error Language
		  $tmddelilables=$this->config->get('arivaldatetime_multi');
			if(!empty($tmddelilables[$this->config->get('config_language_id')]['dateerror'])){
				$args['entry_deliverydate'] = $tmddelilables[$this->config->get('config_language_id')]['dateerror'];
			} else {
				$args['entry_deliverydate'] = $this->language->get('error_deliverydate');
			}

			if(!empty($tmddelilables[$this->config->get('config_language_id')]['timeerror'])){
				$args['entry_deliverytime'] = $tmddelilables[$this->config->get('config_language_id')]['timeerror'];
			} else {
				$args['entry_deliverytime'] = $this->language->get('error_deliverytime');
			}
			//Error Language

		  /*Delivery Date*/
			$template_buffer = $this->getTemplateBuffer($route,$output);		
			$find   = '<form id="form-product">';
			$replace= '<form id="form-product">'.'{% if link_status %}
	             {{ arivaldatetime }}
	             {% endif %}';
			$output = str_replace( $find, $replace, $template_buffer );

			if(VERSION >='4.0.2.0'){
			$template_buffer = $this->getTemplateBuffer($route,$output);
			$find= 'index.php?route=checkout/cart.add&language={{ language }}';
			$replace= 'index.php?route=extension/tmdarrivaldate/tmd/arivaldatetime.optionErrors&language={{ language }}';
			$output = str_replace( $find, $replace, $output );
		  }else{
			$template_buffer = $this->getTemplateBuffer($route,$output);
			$find= 'index.php?route=checkout/cart|add&language={{ language }}';
			$replace= 'index.php?route=extension/tmdarrivaldate/tmd/arivaldatetime|optionErrors&language={{ language }}';
			$output = str_replace( $find, $replace, $output );
		 }		  

			}
        }



public function productthumb(&$route, array &$args, mixed &$output): void {  
   $modulestatus = $this->config->get('module_arivaldatetime_status');
    if(!empty($modulestatus)){

             if(VERSION>='4.0.2.0'){
				 $args['add_to_cart'] = $this->url->link('extension/tmdarrivaldate/tmd/arivaldatetime.optionErrors', 'language=' . $this->config->get('config_language'));	
			}else{
				$args['add_to_cart'] = $this->url->link('extension/tmdarrivaldate/tmd/arivaldatetime|optionErrors', 'language=' . $this->config->get('config_language'));				
			}
        
      }
      
   }


 public function optionErrors(): void {
		$this->load->language('checkout/cart');
      $this->load->language('extension/tmdarrivaldate/tmd/arivaldatetime');

		$json = [];

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = (int)$this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		if (isset($this->request->post['option'])) {
			$option = array_filter($this->request->post['option']);
		} else {
			$option = [];
		}

		if (isset($this->request->post['subscription_plan_id'])) {
			$subscription_plan_id = (int)$this->request->post['subscription_plan_id'];
		} else {
			$subscription_plan_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {

        //arival errors start
		$datestatus 	          = $this->config->get('arivaldatetime_datestatus');
		$timerqstatus 	        = $this->config->get('arivaldatetime_timestatus');
		$data['tmddel_status'] 	= $this->config->get('module_arivaldatetime_status');
   
		if($data['tmddel_status'] == 1) {
			$tmddelilables=$this->config->get('arivaldatetime_multi');

			if(!empty($tmddelilables[$this->config->get('config_language_id')]['dateerror'])){
				$data['entry_deliverydate'] = $tmddelilables[$this->config->get('config_language_id')]['dateerror'];
			} else {
				$data['entry_deliverydate'] = $this->language->get('error_deliverydate');
			}

			if(!empty($tmddelilables[$this->config->get('config_language_id')]['timeerror'])){
				$data['entry_deliverytime'] = $tmddelilables[$this->config->get('config_language_id')]['timeerror'];
			} else {
				$data['entry_deliverytime'] = $this->language->get('error_deliverytime');
			}

        $this->load->model('extension/tmdarrivaldate/tmd/arivaldatetime');
        $arivalproducts = $this->model_extension_tmdarrivaldate_tmd_arivaldatetime->getProductlinkStatus($product_id);   

    
			if($datestatus==2 && $arivalproducts) {
				if(empty($this->request->post['option']['deliverydate'])) {
					$json['error']['option_'.'deliverydate'] = $data['entry_deliverydate'];
				}
			}

 
			if ($timerqstatus==2 && $arivalproducts) {
				if(empty($this->request->post['option']['deliverytime']) && !empty($this->request->post['option']['deliverydate'])) {
					$json['error']['option_'.'deliverytime'] = $data['entry_deliverytime'];
				}
			}
		}
        //arival errors end


			// If variant get master product
			if ($product_info['master_id']) {
				$product_id = $product_info['master_id'];
			}

			// Only use values in the override
			if (isset($product_info['override']['variant'])) {
				$override = $product_info['override']['variant'];
			} else {
				$override = [];
			}

			// Merge variant code with options
			foreach ($product_info['variant'] as $key => $value) {
				if (in_array($key, $override)) {
					$option[$key] = $value;
				}
			}

			// Validate options
			$product_options = $this->model_catalog_product->getOptions($product_id);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

			// Validate subscription products
			$subscriptions = $this->model_catalog_product->getSubscriptions($product_id);

			if ($subscriptions) {
				$subscription_plan_ids = [];

				foreach ($subscriptions as $subscription) {
					$subscription_plan_ids[] = $subscription['subscription_plan_id'];
				}

				if (!in_array($subscription_plan_id, $subscription_plan_ids)) {
					$json['error']['subscription'] = $this->language->get('error_subscription');
				}
			}
		} else {
			    $json['error']['warning'] = $this->language->get('error_product');
		}


		if (!$json) {

			$this->cart->add($product_id, $quantity, $option, $subscription_plan_id);

			$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product_id), $product_info['name'], $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));

			// Unset all shipping and payment methods
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
		} else {
	
			$json['redirect'] = html_entity_decode($this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $this->request->post['product_id']));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


   protected function getTemplateBuffer($route, $event_template_buffer) {
        // if there already is a modified template from view/*/before events use that one
        if ($event_template_buffer) {
           return $event_template_buffer;
        }

        // load the template file (possibly modified by ocmod and vqmod) into a string buffer
        if ($this->config->get('config_theme') == 'default') {
            $theme = $this->config->get('theme_default_directory');
        } else {
            $theme = $this->config->get('config_theme');
        }
              
        $dir_template  = DIR_TEMPLATE ;
            
        $template_file = $dir_template . $route . '.twig';
      
        if (file_exists( $template_file ) && is_file( $template_file )) {
           return file_get_contents( $template_file );
        }

        

        $dir_template  = DIR_TEMPLATE . 'default/template/';
        $template_file = $dir_template . $route . '.twig';
        if (file_exists( $template_file ) && is_file( $template_file )) {
           return file_get_contents( $template_file );
        }

        trigger_error("Cannot find template file for route '$route'");
        exit;
        }
}