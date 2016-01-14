<?php

App::uses('AppController', 'Controller');

/**
 * Crontab tính toán GAN
 * Class CrontabHardNumbersController
 * @author: phutx
 */
class CrontabHardNumbersController extends AppController
{
    const MIN_SPAN = 3;
    const MAX_SPAN = 20; // ngày gan cực đại
    const TYPE = 'GAN';
    public $uses = array('Region', 'DateLuck', 'NumberLuck', 'DateResult');

    /**
     * main function
     */
    public function index()
    {
        $this->layout = false;
        $this->autoRender = false;
        $this->logAnyFile(sprintf('CRON START: %s', date('Y-m-d H:i:s')), __CLASS__.'_'.__FUNCTION__);
        // delete all old data
        $this->_deleteOldData();
        // get all child region
        $list_region = $this->Region->find('list', array(
            'fields' => array('id', 'code'),
            'conditions' => array('parent' => array('$ne' => null)),
        ));
        // loop region
        foreach($list_region AS $region) {
            // find MAX_SPAN results lastest
            $datas = $this->DateResult->find('all', array(
                'fields' => array('lotos', 'date'),
                'conditions' => array(
                    'region_code' => $region
                ),
                'limit' => self::MAX_SPAN,
                'order' => array('date' => 'DESC')
            ));
            $this->_execute($datas, $region);
        }
        $this->logAnyFile(sprintf('CRON END: %s', date('Y-m-d H:i:s')), __CLASS__.'_'.__FUNCTION__);
    }

    /**
     * delete old data if has old data from db
     */
    protected function _deleteOldData() {
        // delete all record to day has type = GAN in table date_lucks
        $all_date_luck = $this->DateLuck->find('all', array('conditions' => array(
            'date'=> (int) date('Ymd'),
            'type' => self::TYPE,
        )));
        if(is_array($all_date_luck) && count($all_date_luck)) {
            foreach($all_date_luck AS $item_date) {
                $this->DateLuck->delete(new MongoId($item_date['DateLuck']['id']));
            }
        }

        // delete all record to day has type = GAN in table number_lucks
        $all_number_luck = $this->NumberLuck->find('all', array('conditions' => array(
            'date'=> (int) date('Ymd'),
            'type' => self::TYPE,
        )));
        if(is_array($all_number_luck) && count($all_number_luck)) {
            foreach($all_number_luck AS $item_number) {
                $this->NumberLuck->delete(new MongoId($item_number['NumberLuck']['id']));
            }
        }
    }

    /**
     * @param $datas
     * @param $region
     */
    protected function _execute($datas, $region) {
        $arr_number = array_fill(0, 100, 0);
        $arr_number_no_increase = array();
        foreach($datas AS $key => $data) {
            foreach($arr_number AS $k => $number) {
                if(in_array(str_pad($k, 2, 0, STR_PAD_LEFT), $data['DateResult']['lotos'])) {
                    $arr_number_no_increase[$k] = true;
                    if(count($arr_number_no_increase) == count($arr_number)) {
                        break;
                    }
                } else {
                    if(!array_key_exists($k, $arr_number_no_increase)) {
                        $arr_number[$k] += 1;
                    }
                }
            }
        }
        if(count($arr_number)) {
            $this->DateLuck->create();
            $save = array(
                'date' => (int) date('Ymd'),
                'region_code' => $region,
                'type' => self::TYPE,
                'numbers' => array()
            );
            for($i = max($arr_number); $i >= self::MIN_SPAN; $i--) {
                if(array_keys($arr_number, $i)) {
                    $save['numbers'][$i] = array_keys($arr_number, $i);
                }
            }
            $this->DateLuck->save($save);
        }
    }
}