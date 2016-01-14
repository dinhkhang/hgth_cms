<?php

App::uses('AppController', 'Controller');

/**
 * Crontab tính toán thống kê cho các cặp số từ 00 - 99
 * Class CrontabLotoAnalyticsController
 * @author: phutx
 */
class CrontabLotoAnalyticsController extends AppController
{
    const MAX_SPAN = 30; // Phan tich cap so [Cap so]  [Ten tinh/thanh pho] trong vong 30 ngay.
    public $uses = array('Region', 'DateResult', 'LotoAnalytic');

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
        $all_loto = $this->LotoAnalytic->find('all', array('conditions' => array(
            'date' => (int) date('Ymd')
        )));
        if(is_array($all_loto) && count($all_loto)) {
            foreach($all_loto AS $item_loto) {
                $this->LotoAnalytic->delete(new MongoId($item_loto['LotoAnalytic']['id']));
            }
        }
    }

    /**
     * @param $datas
     * @param $region
     */
    protected function _execute($datas, $region) {
        // tính biên độ lớn nhất toàn bộ
        $arr_number = array_fill(0, 100, 0); // mảng lưu từ 00 -> 99
        $arr_luck = $arr_number; // mảng lưu từ 00 -> 99
        $arr_luck_by_date = $arr_number; // mảng lưu từ 00 -> 99
        $arr_fix = $arr_number; // màng lưu ngày

        // tính toán số lượt về theo đầu đít
        $arr_number2 = $arr_luck_first = $arr_luck_last = array_fill(0, 10, 0); // mảng lưu từ 00 -> 10
        foreach($datas AS $key => $one) { // loop main data
            foreach($arr_number2 AS $k => $count) { // loop mảng 00 -> 99
                $arr_luck_first[$k] += $this->__getTotalLuckFirstCount($one['DateResult']['lotos'], $k);
                $arr_luck_last[$k] += $this->__getTotalLuckLastCount($one['DateResult']['lotos'], $k);
            }
        }

        foreach($datas AS $key => $one) { // loop main data
            foreach($arr_number AS $k => $gan) { // loop mảng 00 -> 99
                $loto = str_pad($k, 2, 0, STR_PAD_LEFT);
                if(in_array($loto, $one['DateResult']['lotos'])) {
                    $arr_luck[$k] += $this->__countNumberFromLoto($one['DateResult']['lotos'], $loto);
                    $arr_luck_by_date[$k] += 1;
                    $arr_fix[$k] .= '|';
                } else {
                    $arr_fix[$k] .= '-';
                }
            }
        }

        $arr_gan_span = array_fill(0, 100, 0);
        foreach($arr_fix AS $number => $two) {
            $loto = str_pad($number, 2, 0, STR_PAD_LEFT);
            $arr_list_date = explode('|', $two);
            foreach($arr_list_date AS $range_date) {
                $number_date = strlen($range_date);
                if($number_date > $arr_gan_span[$number]) {
                    $arr_gan_span[$number] = $number_date;
                }
            }
            $this->LotoAnalytic->create();
            $save = array(
                'date' => (int) date('Ymd'),
                'region_code' => $region,
                'loto' => $loto,
                'luck_count' => $arr_luck[$number],
                'luck_count_by_date' => $arr_luck_by_date[$number],
                'luck_first_count' => $arr_luck_first[substr($loto, 0, 1)],
                'luck_last_count' => $arr_luck_last[substr($loto, -1, 1)],
                'gan_span' => $arr_gan_span[$number]
            );
            $this->LotoAnalytic->save($save);
        }
    }

    private function __getTotalLuckFirstCount($data, $number) {
        $counter = 0;
        foreach($data AS $item) {
            if(substr($item, 0, 1) == $number) {
                $counter++;
            }
        }
        return $counter;
    }

    private function __getTotalLuckLastCount($data, $number) {
        $counter = 0;
        foreach($data AS $item) {
            if(substr($item, -1, 1) == $number) {
                $counter++;
            }
        }
        return $counter;
    }

    private function __countNumberFromLoto($data, $number) {
        $counter = 0;
        foreach($data AS $item) {
            if($item == $number) {
                $counter++;
            }
        }
        return $counter;
    }

}