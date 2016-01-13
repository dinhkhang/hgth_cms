<?php

class XienNumberLuck extends AppModel {

    public $useTable = 'xien_number_lucks';

    public $customSchema = array(
        'id' => '',
        'date' => '',
        'region_code' => '',
        'type' => '',
        'span' => '',
        'number' => '',
        'lucky_dates' => '',
        'user' => '',
        'created' => '',
        'modified' => '',
    );

}