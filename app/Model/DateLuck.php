<?php

App::uses('AppModel', 'Model');
App::uses('NumberLuck', 'Model');

/**
 * Class DateLuck
 */

class DateLuck extends AppModel
{

    public $useTable = 'date_lucks';

    public function afterSave($created, $options = array())
    {
        parent::afterSave($created, $options);

    }

    public $customSchema = array(
        'id'            =>  '' ,
        'date'          =>  0,
        'region_code'   =>  '',
        'type'          =>  '',
        'numbers'        =>  '',
        'user'          =>  '',
        'created'       =>  '',
        'modified'      =>  '',
    );

}
