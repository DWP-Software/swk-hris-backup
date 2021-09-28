<?php

namespace api\models;

class Employee extends \common\models\entity\Employee
{
    
    public $addressShortText;
    public $domicileShortText;

    public function __construct()
    {  
        parent::__construct();
        $this->setAddressShortText();
    }
    
    public function setAddressShortText()
    {
        $this->addressShortText = parent::getAddressText();
    }

}
