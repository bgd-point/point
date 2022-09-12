<?php

namespace App\Model\Master;

use App\Model\MasterModel;
use App\Traits\Model\Master\BranchJoin;
use App\Traits\Model\Master\BranchRelation;

/**
 * @property int $id
 */
class BranchUser extends MasterModel
{
    use BranchJoin, BranchRelation;

    protected $connection = 'tenant';

    protected $table = 'branch_user';


}