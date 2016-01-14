<?php

App::uses('AppModel', 'Model');

class NumberLuck extends AppModel
{

    public $useTable = 'number_lucks';

    public $customSchema = array(
        'id'            =>  '' ,
        'date'          =>  0,
        'region_code'   =>  '',
        'type'          =>  '',
        'span'          =>  0,
        'number'        =>  '', 
        'first_loc'     =>  0,
        'last_loc'      =>  0,
        'user'          =>  '',
        'created'       =>  '',
        'modified'      =>  '',
    );

}
