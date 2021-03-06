<?php

abstract class Pay_Gateway_Abstract extends WC_Payment_Gateway
{

    public static function getId()
    {
        throw new Exception('Please implement the getId method');
    }

    public static function getName()
    {
        throw new Exception('Please implement the getName method');
    }

    public static function getOptionId()
    {
        throw new Exception('Please implement the getOptionId method');
    }

    public static function getApiToken()
    {
        return get_option('paynl_apitoken');
    }

    public static function getServiceId()
    {
        return get_option('paynl_serviceid');
    }

    public function getIcon()
    {
        return 'https://admin.pay.nl/images/payment_profiles/' . $this->getOptionId() . '.gif';
    }

    public function __construct()
    {

        $this->id = $this->getId();
        $this->icon = $this->getIcon();
        $optionSubs = Pay_Helper_Data::getOptionSubs($this->getOptionId());
        $this->has_fields = true;
        $this->method_title = 'Pay.nl - ' . $this->getName();
        $this->method_description = sprintf(__('Activate this module to accept %s transactions', 'woocommerce-payment-paynl'), $this->getName());

        $this->supports = array('products', 'refunds');

        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function payment_fields()
    {
        //velden bankselectie ideal
        $optionSubs = Pay_Helper_Data::getOptionSubs($this->getOptionId());
        $description = $this->get_option('description');
        echo $description;

        $selectionType = get_option('paynl_bankselection');

        if ($this->getOptionId() == 10 && !empty($optionSubs) && $selectionType != 'none')
        {
            if ($selectionType == 'select')
            {
                ?>
                <p>
                    <select name="option_sub_id">
                    <?php
                    foreach ($optionSubs as $optionSub)
                    {
                        echo '<option value="' . $optionSub['option_sub_id'] . '">' . $optionSub['name'] . '</option>';
                    }
                    ?>                   
                    </select>
                </p>
            <?php } elseif ($selectionType == 'radio')
            {
                ?>
                <ul style="border:none;width:400px;list-style: none;">
                <?php
                foreach ($optionSubs as $optionSub)
                {
                    echo '<li style="float: left; width: 200px;"><label><input type="radio" name="option_sub_id" value="' . $optionSub['option_sub_id'] . '" />&nbsp;<img src="' . $optionSub['image'] . '" alt="' . $optionSub['name'] . '" title="' . $optionSub['name'] . '" /></label></li>';
                }
                ?>
                </ul>
                <div class="clear"></div>
                <?php
            }
        }
    }

    public function process_payment($order_id)
    {
        /** @var $wpdb wpdb The database */
        global $wpdb;
        global $woocommerce;
        $order = new WC_Order($order_id);

        //start transaction
        $api = new Pay_Api_Start();
        $api->setApiToken($this->getApiToken());
        $api->setServiceId($this->getServiceId());

        $amount = round($order->get_total() * 100);

        $api->setAmount($amount);
        $api->setDescription($order->get_order_number());


        $returnUrl = add_query_arg('wc-api', 'Wc_Pay_Gateway_Return', home_url('/'));
        $exchangeUrl = add_query_arg('wc-api', 'Wc_Pay_Gateway_Exchange', home_url('/'));
        $api->setFinishUrl($returnUrl);
        $api->setExchangeUrl($exchangeUrl);
        $api->setOrderId($order->get_order_number());
        $api->setCurrency($order->get_order_currency());
        $api->setPaymentOptionId($this->getOptionId());

        if (isset($_POST['option_sub_id']))
        {
            $api->setPaymentOptionSubId($_POST['option_sub_id']);
        }

        if (get_option('paynl_send_order_data') == true)
        {   
            // order gegevens ophalen
            $shippingAddress = $order->shipping_address_1 . ' ' . $order->shipping_address_2;
            $arrShippingAddress = explode(' ', trim($shippingAddress));
            $shippingHousenumber = array_pop($arrShippingAddress);
            $shippingHousenumber = substr($shippingHousenumber, 0, 10);
            $shippingStreet = implode(' ', $arrShippingAddress);

            $billingAddress = $order->billing_address_1 . ' ' . $order->billing_address_2;
            $arrBillingAddress = explode(' ', trim($billingAddress));
            $billingHousenumber = array_pop($arrBillingAddress);
            $billingHousenumber = substr($billingHousenumber, 0, 10);
            $billingStreet = implode(' ', $arrBillingAddress);

            $arrEnduser = array(
                'initials' => substr($order->shipping_first_name, 0, 1),
                'lastName' => $order->shipping_last_name,
                'phoneNumber' => $order->billing_phone,
                'emailAddress' => $order->billing_email,
                'address' => array(
                    'streetName' => $shippingStreet,
                    'streetNumber' => $shippingHousenumber,
                    'zipCode' => $order->shipping_postcode,
                    'city' => $order->shipping_city,
                    'countryCode' => $order->shipping_country
                ),
                'invoiceAddress' => array(
                    'initials' => substr($order->billing_first_name, 0, 1),
                    'lastName' => $order->billing_last_name,
                    'streetName' => $billingStreet,
                    'streetNumber' => $billingHousenumber,
                    'zipCode' => $order->billing_postcode,
                    'city' => $order->billing_city,
                    'countryCode' => $order->billing_country
                ),
            );

            $arrEnduser['language'] = get_option('paynl_language');
            //klantdata opsturen
            $api->setEnduser($arrEnduser);

            // de producten toevoegen
            $items = $order->get_items();

            $totalFromLines = 0;

            foreach ($items as $item)
            {
                $pricePerPiece = round((($item['line_subtotal'] + $item['line_subtotal_tax']) / $item['qty']) * 100);
                $totalFromLines += $pricePerPiece * $item['qty'];

                $api->addProduct($item['product_id'], $item['name'], $pricePerPiece, $item['qty'], 0);
            }

            //verzendkosten en korting meenemen
            $discount = $order->get_total_discount(false);
            $shipping = $order->get_total_shipping() + $order->get_shipping_tax();

            //Kortingen verrekenen
            if ($discount != 0)
            {
                $totalDiscount = round($discount * -100);
                $api->addProduct('DISCOUNT', 'Korting', $totalDiscount, 1, 0);
                $totalFromLines += $totalDiscount;
            }
            //verzendkosten verrekenen
            if ($shipping != 0)
            {
                $totalShipping = round($shipping * 100);
                $api->addProduct('SHIPPING', 'Verzendkosten', $totalShipping, 1, 'H');
                $totalFromLines += $totalShipping;
            }

			//Extra kosten meesturen
			$fees = $order->get_fees();
            if(!empty($fees)){
                foreach($fees as $fee){
                    $feeAmount = round($fee['line_total']*100);
                    $api->addProduct($fee['type'], $fee['name'], $feeAmount, 1, 'H');
                    $totalFromLines += $feeAmount;
                }
            }
			
            // Nu heb ik alles meegestuurd wat ik weet, er kan door afrondingsverschillen of door andere plugins een verschil ontstaan.
            // Daarom stuur ik het verschil tussen de rijtotalen en het totaal mee als correctieregel
            $correction = $amount - $totalFromLines;
            if ($correction != 0)
            {
                //een correctieregel is nodig
                $api->addProduct('CORRECTION', 'Correctieregel', $correction, 1, 0);
            }
        }

        $result = $api->doRequest();

        $order->add_order_note(sprintf(__('Pay.nl: Transaction started: %s', 'woocommerce-payment-paynl'), $result['transaction']['transactionId']));

        Pay_Helper_Transaction::newTransaction($result['transaction']['transactionId'], $this->getOptionId(), $amount, $order->id, json_encode($api->getPostData()));

        // Return thankyou redirect
        return array(
            'result' => 'success',
            'redirect' => $result['transaction']['paymentURL']
        );
    }


    public function init_settings()
    {
        $optionId = $this->getOptionId();
        if (Pay_Helper_Data::isOptionAvailable($optionId))
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => sprintf(__('Enable Pay.nl %s', 'woocommerce-payment-paynl'), $this->getName()),
                    'default' => 'no',
                ),
                'title' => array(
                    'title' => __('Title', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                    'default' => $this->getName(),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Customer Message', 'woocommerce'),
                    'type' => 'textarea',
                    'default' => sprintf(__('Pay with %s', 'woocommerce-payment-paynl'), $this->getName()),
                ),
                'instructions' => array(
                    'title' => __('Instructions', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Instructions that will be added to the thank you page.', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
            );
        } else
        {
            $this->form_fields = array(
                'message' => array(
                    'title' => __('Disabled', 'woocommerce'),
                    'type' => 'hidden',
                    'description' => __('This payment method is not available, please enable this in the pay.nl admin.', 'woocommerce-payment-paynl'),
                    'label' => sprintf(__('Enable Pay.nl %s', 'woocommerce-payment-paynl'), $this->getName()),
           
                )
            );
        }

        add_action('woocommerce_thankyou_' . $this->getId(), array($this, 'thankyou_page'));


        parent::init_settings();
    }

    /**
     * Process a refund if supported
     * @param  int $order_id
     * @param  float $amount
     * @param  string $reason
     * @return  bool|wp_error True or false based on success, or a WP_Error object
     */
    public function process_refund($order_id, $amount = null, $reason = '')
    {
        $order = wc_get_order($order_id);

        $transactionId = Pay_Helper_Transaction::getPaidTransactionIdForOrderId($order_id);

        if (!$order || !$transactionId)
        {
            return false;
        }

        try
        {
            $refundApi = new Pay_Api_Refund();

            $refundApi->setApiToken($this->getApiToken());
            $refundApi->setServiceId($this->getServiceId());

            $refundApi->setTransactionId($transactionId);
            $refundApi->setAmount((int) round($amount * 100));
            $refundApi->setDescription($reason);

            $result = $refundApi->doRequest();

            $order->add_order_note(sprintf(__('Refunded %s - Refund ID: %s', 'woocommerce'), $amount, $result['refundId']));
            return true;
        } catch (Exception $e)
        {
            return new WP_Error(1, $e->getMessage());
        }

        return false;
    }

    /**
     * Output for the order received page.
     */
    public function thankyou_page()
    {
        if ($this->get_option('instructions'))
            echo wpautop(wptexturize($this->get_option('instructions')));
    }

}
