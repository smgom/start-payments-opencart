<?php

class ControllerPaymentStartPayments extends Controller {

    public function index() {
        $this->language->load('payment/start_payments');
        $data['button_confirm'] = $this->language->get('button_confirm');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/start_payments.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/start_payments.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/start_payments.tpl', $data);
        }
    }

    public function send() {
        require_once './vendor/autoload.php';
        if ($this->config->get('start_payments_transaction')) {
            $capture = FALSE;
        } else {
            $capture = TRUE;
        }

        if ($this->config->get('start_payments_test')) {
            $start_payments_secret_api = $this->config->get('start_payments_entry_test_secret_key');
        } else {
            $start_payments_secret_api = $this->config->get('start_payments_entry_live_secret_key');
        }
        $token = $_POST['payment_token'];
        $email = $_POST['payment_email'];
        $this->load->model('checkout/order');
        $order_id = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $order_description = "Charge for order";
        $amount = $order_info['total'];
        $amount_in_cents = $amount * 100;
        $charge_args = array(
            'description' => $order_description . ': ' . $order_id, // only 255 chars
            'card' => $token,
            'currency' => $order_info['currency_code'], // only USD and AED are supported
            'email' => $email,
            'ip' => $_SERVER["REMOTE_ADDR"],
            'amount' => $amount_in_cents,
            'capture' => $capture
        );
        Start::setApiKey($start_payments_secret_api);
        $json = array();
        try {
            $charge = Start_Charge::create($charge_args);
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('start_payments_order_status_id'), 'Charge added: ' . $order_id, false);
            $json['success'] = $this->url->link('checkout/success');
        } catch (Start_Error_Banking $e) {
            if($e->getErrorCode() == "card_declined"){
                 $json['error'] = "Card declined. Please use another card";
            }else{
                 $json['error'] = $e->getMessage();
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
