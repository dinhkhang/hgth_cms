<?php

/**
 * Class NumberResultsController
 */
class NumberResultsController extends AppController
{
    const FORM_MIEN_BAC = 0;
    const FORM_TRUNG_NAM = 1;
    public $specialRegion;
    public $uses = array('NumberResult', 'Region');
    private $datas;
    private $list_region;

    /**
     * @param string $region
     * @param string $date
     */
    public function add($region = null, $date = null)
    {
        $this->set([
            'breadcrumb' => [
                array(
                    'url'   => Router::url(array('action' => __FUNCTION__)),
                    'label' => __('number_result_add_title'),
                )
            ],
            'page_title' => __('number_result_title')
        ]);
        $this->setInit();
        $this->set(compact('region', 'date'));

        $this->request->data = array($this->modelClass => array(
            'date' => $date ?: date('d-m-Y'),
            'region_code' => $region
        ));
        if ($region && array_key_exists($region, $this->list_region) && $date && strtotime($date)) {
            $this->datas = $this->{$this->modelClass}->find('all', array(
                'conditions' => array(
                    'date' => (int)date('Ymd', strtotime($date)),
                    'region_code' => $region,
                )
            ));
            $this->set('data', $this->datas);

            if ($region == $this->specialRegion) {
                $this->set('showType', $this->__buildForm($region, self::FORM_MIEN_BAC));
            } else {
                $this->set('showType', $this->__buildForm($region, self::FORM_TRUNG_NAM));
            }
        }
    }

    /**
     * Init
     */
    protected function setInit()
    {
        $this->set('model_name', $this->modelClass);
        $this->list_region = $this->Region->find('list', array(
            'fields' => array('code', 'name'),
            'conditions' => array('parent' => array('$ne' => null)),
        ));
        $this->set('listRegion', $this->list_region);

        $this->set('root', Router::url(array('controller' => $this->name, 'action' => 'add'), true));
        $this->set('save', Router::url(array('controller' => $this->name, 'action' => 'saveData'), true));

        $this->specialRegion = Configure::read('sysconfig.special_region');
    }

    /**
     * @param string $region
     * @param string $date
     */
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

    /**
     * Get lotos from numbers
     */
    protected function _getLoto()
    {
        if($this->request->data($this->modelClass . '.number')) {
            $loto = substr($this->request->data($this->modelClass . '.number'), -2);
            $this->request->data[$this->modelClass]['loto'] = $loto;
            $this->request->data[$this->modelClass]['first_loto'] = (int)substr($loto, 0, 1);
            $this->request->data[$this->modelClass]['last_loto'] = (int)substr($loto, -1, 1);
        }
    }

    /**
     * get value for view form
     * @param $type
     * @return array
     */
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

    /**
     * build view form
     * @param $region
     * @param $form
     * @return array
     */
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