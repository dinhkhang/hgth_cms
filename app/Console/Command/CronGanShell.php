<?php
/**
 * // dự đoán tính theo number
 * number_lucks:[{
 * _id: < ObjectId_lucky_number_id > ,
 * date: 20160105, // kiểu int có dạng Ymd
 * region_code: 'mienbac', // vùng miền
 * type: 'CAU', // kiểu số may mắn, CAU, CAP, LBT, GAN
 * span: 1, // kiểu int, biên độ tính theo ngày
 * number: '01',
 * first_loc:{ // tọa độ chữ số đầu tiên, đối với GAN thì bằng null
 * number_result: < ObjectId_number_result_id >, // id của số kết quả giải thưởng liên kết
 * type: 0, // được lấy ra từ type của < ObjectId_number_result_id >
 * number: '0123', // được lấy ra từ number của < ObjectId_number_result_id >
 * index: 0, // vị trí chữ số đầu tiên này nằm trong number_result, tính từ trái sang phải, bắt đầu từ 0
 * },
 * last_loc:{ // tọa độ chữ số cuối, đối với GAN thì bằng null
 * number_result: < ObjectId_number_result_id >, // id của số kết quả giải thưởng liên kết
 * type: 0, // được lấy ra từ type của < ObjectId_number_result_id >
 * number: '0123', // được lấy ra từ number của < ObjectId_number_result_id >
 * index: 0, // vị trí chữ số cuối này nằm trong number_result, tính từ trái sang phải, bắt đầu từ 0
 * },
 * user: < ObjectId_user_id > , // user tạo
 * created: "2015-01-01 00:00:00", // thời điểm tạo
 * modified: "2015-01-01 00:00:00", // thời điểm chỉnh sửa
 * }]
 *
 * // dự đoán tính theo date
 * date_lucks:[{
 * _id: < ObjectId_date_luck_id > ,
 * date: 20160105, // kiểu int có dạng Ymd
 * region_code: 'mienbac', // vùng miền
 * type: 'CAU', // kiểu số may mắn, CAU, CAP, LBT, XIEN, GAN
 * numbers:{ // lưu sắp xếp theo biên độ, biên độ cao nhất ở đầu
 * 6:["01","02"], // biên độ 6 ngày
 * 5:["01","03"], // biên độ 5 ngày
 * ..............
 * },
 * user: < ObjectId_user_id > , // user tạo
 * created: "2015-01-01 00:00:00", // thời điểm tạo
 * modified: "2015-01-01 00:00:00", // thời điểm chỉnh sửa
 * }]
 */
App::uses('AppShell', 'Shell');

class CronGanShell extends AppShell
{

    public $uses = array('Region', 'DateLuck', 'DateResult');

    public function main()
    {
        $min_date = $this->request->query('min_date') ?: '01-01-2014';
        $max_date = $this->request->query('max_date') ?: '11-01-2016';
        $date_range = $this->request->query('date_range') ?: '10';
        $region_code = $this->request->query('region_code') ?: 'thu-do';

        $data = $this->DateResult->find('all', array(
            'order' => array('date' => 'DESC'),
            'conditions' => array(
                'region_code' => $region_code,
                'date' => array(
                    '$gte' => (int) date('Ymd', strtotime($min_date)),
                    '$lte' => (int) date('Ymd', strtotime($max_date))
                ),
            )
        ));

        // tính biên độ gần nhất
        $array_number = array_fill(0, 100, 0);
        $array_fix = array();
        foreach($data AS $key => $one) {
            foreach($array_number AS $k => $gan) {
                if(in_array(str_pad($k, 2, 0, STR_PAD_LEFT), $one['DateResult']['lotos'])) {
                    if($key < $date_range) {
                        unset($array_number[$k]);
                    } else {
                        $array_fix[$k] = $one['DateResult']['date'];
                    }
                } else {
                    if(!array_key_exists($k, $array_fix)) {
                        $array_number[$k] += 1;
                    }
                }
            }
            if(count($array_number) == count($array_fix)) {
                break;
            }
        }

        // tính biên độ lớn nhất toàn bộ
        $array_number2 = array_fill(0, 100, 0);
        $array_fix2 = array_fill(0, 100, '');
        foreach($data AS $key => $one) {
            foreach($array_number2 AS $k => $gan) {
                if(in_array(str_pad($k, 2, 0, STR_PAD_LEFT), $one['DateResult']['lotos'])) {
                    $array_fix2[$k] .= '|';
                } else {
                    $array_fix2[$k] .= $one['DateResult']['date'];
                }
            }
        }

        $array_fix3 = array_fill(0, 100, 0);
        $array_fix4 = array_fill(0, 100, 0);
        foreach($array_fix2 AS $key2 => $two) {
            $array_list_date = explode('|', $two);
            foreach($array_list_date AS $range_date) {
                $number_date = strlen($range_date) / 8;
                if($number_date > $array_fix3[$key2]) {
                    $array_fix3[$key2] = $number_date;
                    $array_fix4[$key2] = $range_date;
                }
            }
        }

        // get region
        // loop region
        //
    }
}