<?php

namespace App\Model\Master;

use App\Model\MasterModel;
use App\Traits\Model\Master\CustomerGroupJoin;
use App\Traits\Model\Master\CustomerGroupRelation;

class CustomerCustomerGroup extends MasterModel
{


    protected $connection = 'tenant';

    public static $alias = 'customer_customer_group';

    protected $table = 'customer_customer_group';
}
