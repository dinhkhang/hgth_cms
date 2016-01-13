<?php

App::uses('AppModel', 'Model');
App::uses('NumberLuck', 'Model');

/**
 * Class DateLuck
 */
class DateLuck extends AppModel
{
    public $uses = 'date_lucks';

    public function afterSave($created, $options = array())
    {
        parent::afterSave($created, $options);

    }


}