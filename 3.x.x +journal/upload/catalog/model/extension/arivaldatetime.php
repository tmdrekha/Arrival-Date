<?php
class ModelExtensionArivaldatetime extends Model {

	public function getProductlinkStatus($product_id) {
		$status=false;
		$productids = $this->config->get('module_tmdarivaldatetime_product');
		if(!empty($productids)){
			$products = $this->config->get('module_tmdarivaldatetime_product');
		} else {
			$products = array();
		}
		if(in_array($product_id, $products)) {
			$status=true;
		}

		$tmdcategory = $this->config->get('module_tmdarivaldatetime_category');
		if(!empty($tmdcategory)){
			$tmdcategory = $this->config->get('module_tmdarivaldatetime_category');
		} else {
			$tmdcategory = array();
		}
		$query = $this->db->query("SELECT category_id from ". DB_PREFIX ."product_to_category where product_id= '".$product_id."'");
		if(!empty($query->rows)) {
			foreach($query->rows as $category) {
				if(in_array($category['category_id'], $tmdcategory)) {
					$status=true;
				}
			}
		}

		$tmdmanufacturer = $this->config->get('module_tmdarivaldatetime_manufacturer');
		if(!empty($tmdmanufacturer)){
			$tmdmanufacturer = $this->config->get('module_tmdarivaldatetime_manufacturer');
		} else {
			$tmdmanufacturer = array();
		}
		$mquery = $this->db->query("SELECT * from ". DB_PREFIX ."product where product_id= '".$product_id."'");
		if(!empty($mquery->row)) {
			if(in_array($mquery->row['manufacturer_id'], $tmdmanufacturer)) {
				$status=true;
			}
		}
		return $status;
	}
}