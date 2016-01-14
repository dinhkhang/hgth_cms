<?php

App::uses('AppModel', 'Model');
App::uses('NumberLuck', 'Model');

/**
 * Class DateLuck
 */

class DateLuck extends AppModel
{

    public $useTable = 'date_lucks';

    /**
     * @param bool $created
     * @param array $options
     * @throws Exception
     */
    public function afterSave($created, $options = array())
    {
        parent::afterSave($created, $options);

        // save result to table number_luck
        $number_luck_model = new NumberLuck();
        foreach($this->data[$this->alias]['numbers'] AS $span => $list_number) {
            if(is_array($list_number) && $list_number) {
                foreach($list_number AS $number) {
                    $number_luck_model->create();
                    $number_luck_model->save(array(
                        'date' => (int) date('Ymd'),
                        'region_code' => $this->data[$this->alias]['region_code'],
                        'type' => $this->data[$this->alias]['type'],
                        'span' => $span,
                        'number' => str_pad($number, 2, 0, STR_PAD_LEFT),
                        'first_loc' => null,
                        'last_loc' => null
                    ));
                }
            }
        }
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
