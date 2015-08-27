<?php

class Pay_Api_Refund extends Pay_Api {

    protected $_version = 'v5';
    protected $_controller = 'transaction';
    protected $_action = 'refund';
    
    protected $_amount = '';
    protected $_description = '';
  
    
    
    public function setTransactionId($transactionId){      
        $this->_postData['transactionId'] = $transactionId;
    }
    
    public function setAmount($amount){      
        $this->_amount = $amount;
    }
    public function setDescription($description){      
        $this->_description = $description;
    }
    
    protected function _getPostData() {
        $data = parent::_getPostData();
        if ($this->_apiToken == '') {
            throw new Exception('apiToken not set');            
        } else {
            $data['token'] = $this->_apiToken;
        }
        if(!isset($this->_postData['transactionId'])){
            throw new Exception('transactionId is not set');
        }
        if($this->_amount != ''){
            $data['amount'] = $this->_amount;
        }
        if($this->_description != ''){
            $data['description'] = $this->_description;
        }
        return $data;
    }
}
