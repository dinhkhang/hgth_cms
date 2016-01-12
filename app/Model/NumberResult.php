<?php
App::uses('AppModel', 'Model');
App::uses('DateResult', 'Model');

class NumberResult extends AppModel
{
    public $useTable = 'number_results';
    private $validateMienBac = array(
        0 => 5, 1 => 5, 2 => 5, 3 => 5, 4 => 4, 5 => 4, 6 => 3, 7 => 2
    );
    private $validateTrungNam = array(
        0 => 6, 1 => 5, 2 => 5, 3 => 5, 4 => 5, 5 => 4, 6 => 4, 7 => 3, 8 => 2
    );

    public $validate = array(
        'number' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'required' => true,
                'message' => 'Please supply the number of prize.'
            ),
            'between' => array(
                'rule' => array('lengthBetween', 2, 6),
                'required' => true,
                'message' => 'Between 2 to 6 characters'
            ),
           'checkByPrize' => array(
                "rule" => array("checkByPrize"),
               'required' => true,
                "message" => "Value is not valid."
           ),
        ),
        'loto' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'required' => true,
                'message' => 'Please supply the number of prize.'
            ),
            'between' => array(
                'rule' => array('lengthBetween', 2, 2),
                'required' => true,
                'message' => 'Between 2 to 2 characters'
            )
        ),
        'first_loto' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'required' => true,
                'message' => 'Please supply the number of prize.'
            ),
            'between' => array(
                'rule' => array('lengthBetween', 1, 1),
                'required' => true,
                'message' => 'Between 1 to 1 characters'
            )
        ),
        'last_loto' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'required' => true,
                'message' => 'Please supply the number of prize.'
            ),
            'between' => array(
                'rule' => array('lengthBetween', 1, 1),
                'required' => true,
                'message' => 'Between 1 to 1 characters'
            )
        ),
    );

    public function beforeSave($options = array())
    {
        parent::beforeSave($options);
        if (isset($this->data[$this->alias]['date'])) {
            $this->data[$this->alias]['date'] = (int)$this->data[$this->alias]['date'];
        }
        if (isset($this->data[$this->alias]['type'])) {
            $this->data[$this->alias]['type'] = (int)$this->data[$this->alias]['type'];
        }
    }

    public function checkByPrize() {
        $type = isset($this->data[$this->alias]['type']) ? $this->data[$this->alias]['type'] : '';
        $region_code = isset($this->data[$this->alias]['region_code']) ? $this->data[$this->alias]['region_code'] : '';
        $data_compate = ($region_code == Configure::read('sysconfig.special_region'))
                      ? $this->validateMienBac
                      : $this->validateTrungNam;
        return strlen($this->data[$this->alias]['number']) == $data_compate[$type];
    }

    public function afterSave($created, $options = array())
    {
        parent::afterSave($created, $options);

        // check record to day in table date_results
        $date = date('Ymd');
        $region_code = $this->data[$this->alias]['region_code'];
        $type = $this->data[$this->alias]['type'];
        $date_result = new DateResult();
        $data = $date_result->find('first', array('conditions' => array(
            'date' => (int)$date,
            'region_code' => $region_code
        )));

        // get all record of prize
        $data_prize = $this->find('all', array('conditions' => array(
            'date' => (int)$date,
            'region_code' => $region_code,
        )));
        $array_realtime_number = $array_loto = array();
        foreach ($data_prize AS $prize) {
            $array_realtime_number[(int) $prize[$this->alias]['type']][] = $prize[$this->alias]['number'];
            $array_loto[] = $prize[$this->alias]['loto'];
        }
        sort($array_loto);

        if ($data) {
            $date_result->id = new MongoId($data['DateResult']['id']);
            $data['DateResult']['realtime_numbers'] = $array_realtime_number;
            $data['DateResult']['lotos'] = $array_loto;
            if(count($data_prize) == $this->__getTotalPrize($region_code)) {
                $data['DateResult']['numbers'] = $this->__formatNumber($array_realtime_number);
            }
        } else {
            $date_result->create();
            $data = array(
                'date' => (int)$date,
                'region_code' => $region_code,
                'realtime_numbers' => $array_realtime_number,
                'lotos' => $array_loto,
            );
        }
        $date_result->save($data);

    }

    private function __formatNumber($array_realtime_number) {
        $array_numbers = array();
        ksort($array_realtime_number);
       return array_values($array_realtime_number);
    }

    private function __getTotalPrize($region_code = 0) {
        $config_total_prize = Configure::read('sysconfig.total_result_prize');
        if(array_key_exists($region_code, $config_total_prize)) {
            return $config_total_prize[$region_code];
        } else {
            return $config_total_prize['other'];
        }
    }


}
