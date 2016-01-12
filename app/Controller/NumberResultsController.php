<?php

class NumberResultsController extends AppController
{
    const FORM_MIEN_BAC = 0;
    const FORM_TRUNG_NAM = 1;
    public $SPECIAL_REGION;
    public $uses = array('NumberResult', 'Region');
    private $datas;

    public function add($region = null)
    {
        $this->setInit();
        $this->set(compact('region'));

        // set default value
        if ($region) {
            $this->request->data[$this->modelClass . '.region'] = $region;
        } else {
            $this->request->data[$this->modelClass . '.region'] = $this->SPECIAL_REGION;
        }

        if ($region) {
            $this->datas = $this->{$this->modelClass}->find('all', array(
                'conditions' => array(
                    'date' => (int) date('Ymd'),
                    'region_code' => $region,
                )
            ));
            $this->set('data', $this->datas);

            if ($this->request->data[$this->modelClass . '.region'] == $this->SPECIAL_REGION) {
                $this->set('showType', $this->__buildForm($region, self::FORM_MIEN_BAC));
            } else {
                $this->set('showType', $this->__buildForm($region, self::FORM_TRUNG_NAM));
            }
        }
        $this->set('data2', $this->data);
    }

    protected function setInit()
    {
        $this->set('model_name', $this->modelClass);
        $this->set('listRegion', $this->Region->find('list', array('fields' => array('code', 'name'))));

        $this->set('root', Router::url(array('controller' => $this->name, 'action' => 'add'), true));
        $this->set('get', Router::url(array('controller' => $this->name, 'action' => 'getData'), true));
        $this->set('save', Router::url(array('controller' => $this->name, 'action' => 'saveData'), true));

        $this->SPECIAL_REGION = Configure::read('sysconfig.special_region');
    }

    public function getData($region = null)
    {
        // lấy dữ liệu
        if ($this->request->is('ajax') && $this->request->is('get')) {
            $data = $this->DateResult->find('first', array('conditions' => array(
                'region_code' => $region,
                'date' => (int)date('Ymd')
            )));

            $format_data = array();
            foreach ($data['DateResult']['numbers'] AS $key => $prize) {
                if (is_array($prize)) {
                    foreach ($prize AS $k => $one) {
                        $format_data[$key . $k] = $one;
                    }
                } else {
                    $format_data[$key . '0'] = $prize;
                }
            }
            $result = array(
                'data' => $format_data,
                'conditions' => array(
                    'region_code' => $region,
                    'date' => date('Ymd')
                )
            );
            echo json_encode($result);
        }
        $this->autoLayout = $this->autoRender = false;
    }

    public function saveData($region = null, $date = null)
    {
        if ($this->request->is('post')) {
            $this->_getLoto();
            if ($this->{$this->modelClass}->save($this->request->data)) {
                echo json_encode(array(
                    'status' => 1,
                    'class' => 'has-success',
                    'id' => $this->{$this->modelClass}->getLastInsertId()
                ));
            } else {
                echo json_encode(array(
                    'status' => 1,
                    'class' => 'has-error',
                    'id' => ''
                ));
            }
        }
        $this->autoLayout = $this->autoRender = false;
    }

    protected function _getLoto()
    {
        $loto = substr($this->request->data($this->modelClass . '.number'), -2);
        $this->request->data[$this->modelClass]['loto'] = $loto;
        $this->request->data[$this->modelClass]['first_loto'] = (int)substr($loto, 0, 1);
        $this->request->data[$this->modelClass]['last_loto'] = (int)substr($loto, -1, 1);
    }

    private function __fetchValue($type)
    {
        $result = array();
        foreach ($this->datas AS $key => $one) {
            if ($one[$this->modelClass]['type'] == $type) {
                $result[] = array(
                    'id' => $one[$this->modelClass]['id'],
                    'number' => $one[$this->modelClass]['number'],
                );
            }
        }
        return $result;
    }

    private function __buildForm($region, $form)
    {
        switch ($form) {
            case self::FORM_MIEN_BAC:
                return array(
                    array(
                        'title' => 'Đặc Biệt',
                        'data' => array(
                            'number_result' => 1,
                            'max_length' => 5,
                            'class' => array(12),
                            'value' => $this->__fetchValue(0),
                        )
                    ),
                    array(
                        'title' => 'Giải Nhất',
                        'data' => array(
                            'number_result' => 1,
                            'max_length' => 5,
                            'class' => array(12),
                            'value' => $this->__fetchValue(1),
                        )
                    ),
                    array(
                        'title' => 'Giải Nhì',
                        'data' => array(
                            'number_result' => 2,
                            'max_length' => 5,
                            'class' => array(6, 6),
                            'value' => $this->__fetchValue(2),
                        )
                    ),
                    array(
                        'title' => 'Giải Ba',
                        'data' => array(
                            'number_result' => 6,
                            'max_length' => 5,
                            'class' => array(4, 4, 4, 4, 4, 4),
                            'value' => $this->__fetchValue(3),
                        )
                    ),
                    array(
                        'title' => 'Giải Tư',
                        'data' => array(
                            'number_result' => 4,
                            'max_length' => 4,
                            'class' => array(3, 3, 3, 3),
                            'value' => $this->__fetchValue(4),
                        )
                    ),
                    array(
                        'title' => 'Giải Năm',
                        'data' => array(
                            'number_result' => 6,
                            'max_length' => 4,
                            'class' => array(4, 4, 4, 4, 4, 4),
                            'value' => $this->__fetchValue(5),
                        )
                    ),
                    array(
                        'title' => 'Giải Sáu',
                        'data' => array(
                            'number_result' => 3,
                            'max_length' => 3,
                            'class' => array(4, 4, 4),
                            'value' => $this->__fetchValue(6),
                        )
                    ),
                    array(
                        'title' => 'Giải Bảy',
                        'data' => array(
                            'number_result' => 4,
                            'max_length' => 2,
                            'class' => array(3, 3, 3, 3),
                            'value' => $this->__fetchValue(7),
                        )
                    ),
                );
            case self::FORM_TRUNG_NAM:
                return array(
                    array(
                        'title' => 'Đặc Biệt',
                        'data' => array(
                            'number_result' => 1,
                            'max_length' => 6,
                            'class' => array(12),
                            'value' => $this->__fetchValue(0),
                        )
                    ),
                    array(
                        'title' => 'Giải Nhất',
                        'data' => array(
                            'number_result' => 1,
                            'max_length' => 5,
                            'class' => array(12),
                            'value' => $this->__fetchValue(1),
                        )
                    ),
                    array(
                        'title' => 'Giải Nhì',
                        'data' => array(
                            'number_result' => 1,
                            'max_length' => 5,
                            'class' => array(12),
                            'value' => $this->__fetchValue(2),
                        )
                    ),
                    array(
                        'title' => 'Giải Ba',
                        'data' => array(
                            'number_result' => 2,
                            'max_length' => 5,
                            'class' => array(6, 6),
                            'value' => $this->__fetchValue(3),
                        )
                    ),
                    array(
                        'title' => 'Giải Tư',
                        'data' => array(
                            'number_result' => 7,
                            'max_length' => 5,
                            'class' => array(3, 3, 3, 3, 4, 4, 4),
                            'value' => $this->__fetchValue(4),
                        )
                    ),
                    array(
                        'title' => 'Giải Năm',
                        'data' => array(
                            'number_result' => 1,
                            'max_length' => 4,
                            'class' => array(12),
                            'value' => $this->__fetchValue(5),
                        )
                    ),
                    array(
                        'title' => 'Giải Sáu',
                        'data' => array(
                            'number_result' => 3,
                            'max_length' => 4,
                            'class' => array(4, 4, 4),
                            'value' => $this->__fetchValue(6),
                        )
                    ),
                    array(
                        'title' => 'Giải Bảy',
                        'data' => array(
                            'number_result' => 1,
                            'max_length' => 3,
                            'class' => array(12),
                            'value' => $this->__fetchValue(7),
                        )
                    ),
                    array(
                        'title' => 'Giải Tám',
                        'data' => array(
                            'number_result' => 1,
                            'max_length' => 2,
                            'class' => array(12),
                            'value' => $this->__fetchValue(8),
                        )
                    ),
                );
        }
    }

}