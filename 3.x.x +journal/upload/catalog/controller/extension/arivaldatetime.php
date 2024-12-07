<?php
class ControllerExtensionArivaldatetime extends Controller {
	public function index() {
		$this->load->language('extension/arivaldatetime');
		

		if(!empty($this->request->get['product_id'])){
			$data['product_id'] = $this->request->get['product_id'];
		}else{
			$data['product_id'] = '';
		}

		$data['tmddeldatetime_status'] 	= $this->config->get('module_tmdarivaldatetime_status');
		$data['want_to_comment']        = $this->config->get('module_tmdarivaldatetime_want_to_comment');
		$data['datestatus'] 			= $this->config->get('module_tmdarivaldatetime_datestatus');
		$data['timereq'] 	    		= $this->config->get('module_tmdarivaldatetime_timestatus');
		$data['typetimes'] 				= $this->config->get('module_tmdarivaldatetime_typetime');

		$data['datedeactives'] = array();

		$datedeactives = $this->config->get('module_tmdarivaldatetime_datedeactive');
		if(!empty($datedeactives)){
		foreach ($datedeactives as $deactive) {
			$data['datedeactives'][] = '"'.$deactive['de_date'].'"';
		  }
		}

		if(!empty($data['datedeactives'])) {
			$data['datedeactive'] = implode(',',$data['datedeactives']);
		}else{
			$data['datedeactive'] ='';
		}

		$data['selecttimes'] = array();
		$deliverytimes = $this->config->get('module_tmdarivaldatetime_delivery_time');
		if(!empty($deliverytimes)){
		foreach ($deliverytimes as $time) {
			$data['selecttimes'][] = array(
			'open_time'  => $time['open_time'],
			'close_time' => $time['close_time']
			);
		  }
		}

		if(!empty($this->config->get('module_tmdarivaldatetime_store'))) {
			$data['tmddeldatetime_store'] = $this->config->get('module_tmdarivaldatetime_store');
		}else{
			$data['tmddeldatetime_store'] = array();
		}

		$data['configstore'] = $this->config->get('config_store_id');
		if (in_array($data['configstore'], $data['tmddeldatetime_store'])) {
			$data['tmdstore'] = $data['tmddeldatetime_store'];
		} else {
			$data['tmdstore'] ='';
		}

		$tmdtimezone = $this->config->get('module_tmdarivaldatetime_timezone');
		date_default_timezone_set($tmdtimezone);

		$cutoftime 	= $this->config->get('module_tmdarivaldatetime_cutoftime');
		

		if(!empty($this->config->get('module_tmdarivaldatetime_datestart'))){
		$datestart 	= $this->config->get('module_tmdarivaldatetime_datestart');
	    }else{
        $datestart 	= 0;
	    }
	    
		$dateend 	= $this->config->get('module_tmdarivaldatetime_dateend');

		$data['today'] 		= date('Y-m-d');
		
		if ((date('H') >= $cutoftime) && $cutoftime!=0) {
			$i=$datestart+1;
			$data['newdate']=date('Y-m-d', strtotime(date('y-m-d') . ' +'.$i.' day'));
			$data['newdatecount']=$i;
		} else {
			$data['newdate']=date('Y-m-d', strtotime(date('y-m-d') . ' +'.$datestart.' day'));
			$data['newdatecount']=$datestart;
		}
		
		$data['enddate'] = date('Y-m-d', strtotime(date('y-m-d') . ' +'.$dateend.' day'));
		$data['newdate'] = $this->checkdateholiday($data['newdate']);

		$tmddelilables = $this->config->get('module_tmdarivaldatetime_multi');

		if(!empty($this->config->get('module_tmdarivaldatetime_week'))) {
			$data['tmddeldatetime_week'] = implode(',',$this->config->get('module_tmdarivaldatetime_week'));
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

		$data['tmdsettingsstatus'] =$this->config->get('module_tmdarivaldatetimeg_ex_status');
		if(!empty($data['tmdsettingsstatus'])){
			$tmddelsettings = $this->config->get('module_tmdarivaldatetime_setting');
			if(!empty($tmddelsettings[$this->config->get('config_language_id')]['text_heading'])){
				$data['entry_delexp_heading'] = $tmddelsettings[$this->config->get('config_language_id')]['text_heading'];
			} else {
				$data['entry_delexp_heading'] = $this->language->get('entry_delexp_heading');
			}

			$tmddelexpressvalue = $this->config->get('module_tmdarivaldatetime_express_value');
			if(!empty($tmddelexpressvalue)){
				foreach ($tmddelexpressvalue as $expresult) {
					if(!empty($expresult[$this->config->get('config_language_id')]['express_text'])){
						$express_text = $expresult[$this->config->get('config_language_id')]['express_text'];
					} else {
						$express_text = '';
					}
					$express_price    = $this->currency->format($expresult['express_price'], $this->session->data['currency']);
					$data['tmddelexpressvalues'][] = array(
						'express_text'    => $express_text,
						'express_price'   => $express_price,
						'express_charges' => $expresult['express_price']
					);
				}
			}

			$tmddeltextsetting = $this->config->get('module_tmdarivaldatetime_text_setting');

			if(!empty($tmddeltextsetting)){
				foreach ($tmddeltextsetting as $textsetting) {
					if (!empty($textsetting[$this->config->get('config_language_id')]['tc_text'])) {
					$text = html_entity_decode($textsetting[$this->config->get('config_language_id')]['tc_text']);
					}else{
						$text = '';
					}
                   
					if(!empty($text)){
						$tc_text = $text;
					} else {
						$tc_text = '';
					}
					$data['tmddeltextsettings'][] = array(
						'tc_text'       => $tc_text,
						'sort_order'    => $textsetting['tc_sort_order']
					);
				}
			}
		}

		return $this->load->view('extension/arivaldatetime', $data);
	}
	
	public function loadtime() {
		$this->load->model('extension/arivaldatetime');
		$tmdtimezone = $this->config->get('module_tmdarivaldatetime_timezone');
		date_default_timezone_set($tmdtimezone);

		$json['deliverytimes'] = array();
		if(isset($this->request->get['day'])){
			$day = $this->request->get['day'];
		} else {
			$day = '';
		}

		$deliverytimes = $this->config->get('module_tmdarivaldatetime_delivery_time');
		if(!empty($deliverytimes)){
		foreach ($deliverytimes as $result) {
			if(isset($this->request->get['day'])){
				$postdate    = $this->request->get['day'];
				$day         = $this->request->get['day'];
				$day_of_week = $day;
				$day_of_week = date('D', strtotime($day));
			
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
								
								$json['deliverytimes'][] = array(
									'open_time'  => $open_sort_time,
									'close_time' => $close_sort_time,
									'text'       => $text,
									'extra_val'  => $extracharges,
									'charges'    => $extracharges1,
								);
					}   }else{
								
							if(empty($text)){
								$text=$open_sort_time.'-'.$close_sort_time;
							}
							$json['deliverytimes'][] = array(
								'open_time'  => $open_sort_time,
								'close_time' => $close_sort_time,
								'text'       => $text,
								'extra_val'  => $extracharges,
								'charges'    => $extracharges1,
							);
						}
						
				    }
			    } 
		    }
	    }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function checkdateholiday($date){
		$datedeactiv   =  array();
		$datedeactives = $this->config->get('module_tmdarivaldatetime_datedeactive');
		if(!empty($datedeactives)){
			foreach ($datedeactives as $deactive) {
				$datedeactiv[] = $deactive['de_date'];
			}
		}
		
		if(in_array($date,$datedeactiv)){
			$newdate=date('Y-m-d', strtotime($date . ' +1 day'));
			$date=$this->checkdateholiday($newdate);
			
		}

		$weekholiday = array();
		$module_tmdarivaldatetime_week = $this->config->get('module_tmdarivaldatetime_week');
		if(!empty($module_tmdarivaldatetime_week)){
			foreach ($module_tmdarivaldatetime_week as $tmdarivaldatetime_week) {
				$weekholiday[] = $tmdarivaldatetime_week;
			}
		}
		 
		$day = date('N', strtotime($date));
		if($day==7){
		    $day=0;
		}

		if(in_array($day,$weekholiday)){
			$newdate=date('Y-m-d', strtotime($date . ' +1 day'));
			$date=$this->checkdateholiday($newdate);	
		 }
	
		return $date;
	}

	public function addprice() {
		$json = array();
		$this->load->model('extension/arivaldatetime');

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
			$resultdata  = explode('-', $deliveryvalue);
			if(!empty($resultdata[0])) {
	          $open_time = str_replace(' ', '', $resultdata[0]);
	        } 

	        if(!empty($resultdata[1])) {
	          $close_time = str_replace(' ', '', $resultdata[1]);
	        }
	    }

	    $extracharges  = array();
		$deliverytimes = $this->config->get('module_tmdarivaldatetime_delivery_time');
		if(!empty($deliverytimes)){
		foreach ($deliverytimes as $result) {
			if(!empty($result['ex_charges'])) {
				$extracharges[$result['open_time'].'-'.$result['close_time']] = $result['ex_charges'];
			}
		}
		}

		$ex_charges = '0';
		if(!empty($extracharges[$open_time.'-'.$close_time])) {
			$ex_charges = $extracharges[$open_time.'-'.$close_time];
		}
		
	    $this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		$pprice       = '';
		$specialprice = '';
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
				//$json['ex_price']   = $this->currency->format($ex_charges+$express_delivery, $this->session->data['currency']);
			
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}