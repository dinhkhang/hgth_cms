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
    const MIN_SPAN = 3;
    const MAX_SPAN = 6;
    const TYPE = 'GAN';
    public $uses = array('Region', 'DateLuck', 'DateResult');

    public function main()
    {
        // get all child region
        $list_region = $this->Region->find('list', array(
            'fields' => array('id', 'code'),
            'conditions' => array('parent' => array('$ne' => null)),
        ));
        // loop region
        foreach($list_region AS $region) {
            // find 6 result lastest
            $datas = $this->DateResult->find('all', array(
                'fields' => array('lotos'),
                'conditions' => array(
                    'region_code' => $region
                ),
                'limit' => self::MAX_SPAN,
                'order' => array('modified' => 'DESC')
            ));
            $this->execute($datas, $region);
        }
        //
    }

    protected function execute($datas, $region) {
        $arr_number = array_fill(0, 100, 0);
        foreach($datas AS $key => $data) {
            foreach($arr_number AS $k => $number) {
                if($key + 1 <= self::MIN_SPAN && in_array($number, $data['DateResult']['lotos'])) {
                    unset($arr_number[$k]);
                } elseif(in_array($number, $data['DateResult']['lotos'])) {
                    // do nothing
                } else {
                    $arr_number[$k] += 1;
                }
            }
        }
        $this->DateLuck->create();
        $this->DateLuck->save(array(
            'date' => (int) date('Ymd'),
            'region_code' => $region,
            'type' => self::TYPE,
            'numbers' => array(
                array('6' => array_keys($arr_number, 6)),
                array('5' => array_keys($arr_number, 5)),
                array('4' => array_keys($arr_number, 4)),
                array('3' => array_keys($arr_number, 3))
            )
        ));
    }
}