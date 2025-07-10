<?php
namespace Opencart\Admin\Controller\Extension\Tmdarrivaldate\Total;
class Tmdarivalprice extends \Opencart\System\Engine\Controller {

	public function index() {
		$this->load->language('extension/tmdarrivaldate/total/tmdarivalprice');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		$data['breadcrumbs']   = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdarrivaldate/total/tmdarivalprice', 'user_token=' . $this->session->data['user_token'])
		];

		$data['action'] = $this->url->link('extension/tmdarrivaldate/total/tmdarivalprice|save', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total');

		$data['total_tmdarivalprice_status']     = $this->config->get('total_tmdarivalprice_status');
		$data['total_tmdarivalprice_title']      = $this->config->get('total_tmdarivalprice_title');
		$data['total_tmdarivalprice_sort_order'] = $this->config->get('total_tmdarivalprice_sort_order');

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdarrivaldate/total/tmdarivalprice', $data));
	}

	public function save(): void {
		$this->load->language('extension/tmdarrivaldate/total/tmdarivalprice');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdarrivaldate/total/tmdarivalprice')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('total_tmdarivalprice', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}