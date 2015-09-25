<?php

class ControllerPaymentStartPayments extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('payment/start_payments');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('start_payments', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $data['error'] = @$this->error;
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_authorization_capture'] = $this->language->get('text_authorization_capture');
        $data['text_authorization_only'] = $this->language->get('text_authorization_only');

        $data['entry_live_open_key'] = $this->language->get('entry_live_open_key');
        $data['entry_live_secret_key'] = $this->language->get('entry_live_secret_key');
        $data['entry_test_open_key'] = $this->language->get('entry_test_open_key');
        $data['entry_test_secret_key'] = $this->language->get('entry_test_secret_key');

        $data['entry_test'] = $this->language->get('entry_test');
        $data['entry_transaction'] = $this->language->get('entry_transaction');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_test'] = $this->language->get('help_test');
        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/start_payments', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('payment/start_payments', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['start_payments_entry_live_open_key'])) {
            $data['start_payments_entry_live_open_key'] = $this->request->post['start_payments_entry_live_open_key'];
        } else {
            $data['start_payments_entry_live_open_key'] = $this->config->get('start_payments_entry_live_open_key');
        }

        if (isset($this->request->post['start_payments_entry_live_secret_key'])) {
            $data['start_payments_entry_live_secret_key'] = $this->request->post['start_payments_entry_live_secret_key'];
        } else {
            $data['start_payments_entry_live_secret_key'] = $this->config->get('start_payments_entry_live_secret_key');
        }
        if (isset($this->request->post['start_payments_entry_test_open_key'])) {
            $data['start_payments_entry_test_open_key'] = $this->request->post['start_payments_entry_test_open_key'];
        } else {
            $data['start_payments_entry_test_open_key'] = $this->config->get('start_payments_entry_test_open_key');
        }
        
        if (isset($this->request->post['start_payments_entry_test_secret_key'])) {
            $data['start_payments_entry_test_secret_key'] = $this->request->post['start_payments_entry_test_secret_key'];
        } else {
            $data['start_payments_entry_test_secret_key'] = $this->config->get('start_payments_entry_test_secret_key');
        }




        if (isset($this->request->post['start_payments_test'])) {
            $data['start_payments_test'] = $this->request->post['start_payments_test'];
        } else {
            $data['start_payments_test'] = $this->config->get('start_payments_test');
        }

        if (isset($this->request->post['start_payments_method'])) {
            $data['start_payments_transaction'] = $this->request->post['start_payments_transaction'];
        } else {
            $data['start_payments_transaction'] = $this->config->get('start_payments_transaction');
        }

        if (isset($this->request->post['start_payments_total'])) {
            $data['start_payments_total'] = $this->request->post['start_payments_total'];
        } else {
            $data['start_payments_total'] = $this->config->get('start_payments_total');
        }

        if (isset($this->request->post['start_payments_order_status_id'])) {
            $data['start_payments_order_status_id'] = $this->request->post['start_payments_order_status_id'];
        } else {
            $data['start_payments_order_status_id'] = $this->config->get('start_payments_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['start_payments_geo_zone_id'])) {
            $data['start_payments_geo_zone_id'] = $this->request->post['start_payments_geo_zone_id'];
        } else {
            $data['start_payments_geo_zone_id'] = $this->config->get('start_payments_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['start_payments_status'])) {
            $data['start_payments_status'] = $this->request->post['start_payments_status'];
        } else {
            $data['start_payments_status'] = $this->config->get('start_payments_status');
        }

        if (isset($this->request->post['start_payments_sort_order'])) {
            $data['start_payments_sort_order'] = $this->request->post['start_payments_sort_order'];
        } else {
            $data['start_payments_sort_order'] = $this->config->get('start_payments_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/start_payments.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/start_payments')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['start_payments_entry_live_open_key']) {
            $this->error['start_payments_entry_live_open_key'] = $this->language->get('error_start_payments_entry_live_open_key');
        }
        if (!$this->request->post['start_payments_entry_live_secret_key']) {
            $this->error['start_payments_entry_live_secret_key'] = $this->language->get('error_start_payments_entry_live_secret_key');
        }
        if (!$this->request->post['start_payments_entry_test_open_key']) {
            $this->error['start_payments_entry_test_open_key'] = $this->language->get('error_start_payments_entry_test_open_key');
        }
        if (!$this->request->post['start_payments_entry_test_secret_key']) {
            $this->error['start_payments_entry_test_secret_key'] = $this->language->get('error_start_payments_entry_test_secret_key');
        }
 

        return !$this->error;
    }

}
