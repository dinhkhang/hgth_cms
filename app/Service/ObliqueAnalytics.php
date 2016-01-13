<?php
App::import('Model', 'DateResult');

/**
 * Created by PhpStorm.
 * User: huongnx
 * Date: 12/01/2016
 * Time: 13:50
 *
 * @property NumberResult NumberResult
 * @property mixed DateResult
 */
class ObliqueAnalytics {
    /**
     * Biên độ thống kê
     *
     * @var
     */
    private $amplitudes;

    /**
     * Tần số xuất hiện trong khoảng biên độ
     *
     * @var
     */
    private $frequency;

    /**
     * Số cặp lo xiên cần trả về
     *
     * @var
     */
    private $quality;

    /**
     * Tỉnh thành phố thống kê
     *
     * @var
     */
    private $region;

    /**
     * Loại xiên (2 hoặc 3)
     *
     * @var
     */
    private $type;

    /**
     * Kết quả trả ra là 1 mảng các cặp lo xiên hay về
     *
     * @var
     */
    private $result;

    /**
     * Khởi tạo các property
     *
     * Xien constructor.
     */
    public function __construct()
    {
        $this->amplitudes = 5;
        $this->frequency = 2;
        $this->quality = 10;
        $this->type = 2;
        $this->region = 'thu-do';
        $this->result = array();
        $this->DateResult = new DateResult();
    }

    /**
     * Set biên độ
     *
     * @param $amplitudes
     * @return $this
     */
    public function setAmplitudes($amplitudes)
    {
        $this->amplitudes = $amplitudes;

        return $this;
    }

    /**
     * Trả ra biên độ
     *
     * @return mixed
     */
    public function getAmplitudes()
    {
        return $this->amplitudes;
    }

    /**
     * @param $frequency
     * @return $this
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * @return int
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param $quality
     * @return $this
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Hàm thống kê lô xiên hay về
     */
    public function analytics()
    {
        $lottos = $this->DateResult->find('all', array(
            'conditions' => array(
                'region_code' => $this->region
            ),
            'limit' => $this->amplitudes,
            'order' => array(
                'date' => 'DESC'
            ),
            'fields' => array('lotos', 'date')
        ));

        list($lottoWithDate, $lottos) = $this->convertDateResult($lottos);

        $pairNumber = $this->getPairNumber($lottos);

        foreach ($lottoWithDate as $date => $value) {
            foreach ($pairNumber as $key => $pair) {
                if (in_array($pair[0], $value) && in_array($pair[1], $value)) {
                    $this->result[$key][] = $date;
                }
            }
        }

        uasort($this->result, function($a, $b) {
            $a = count($a);
            $b = count($b);
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? 1 : -1;
        });

        $index = 0;
        $temp = array();
        foreach ($this->result as $key => $value) {
            if (count($value) >= $this->frequency) {
                $temp[$key] = $value;
                $index ++;
            }
            if ($index >= $this->quality) break;
        }

        $this->result = $temp;
    }

    /**
     * @param $dateResults
     * @return array
     */
    protected function convertDateResult($dateResults)
    {
        $lotoWithDate = array();
        $lotos = array();
        foreach ($dateResults as $dateResult) {
            $dateResult = $dateResult['DateResult'];
            $lotto = $dateResult['lotos'];

            if (!isset($lotoWithDate[$dateResult['date']])) {
                $lotoWithDate[$dateResult['date']] = $lotto;
            }

            foreach ($lotto as $value) {
                if (!in_array($value, $lotos)) {
                    $lotos[] = $value;
                }
            }
        }

        return array(
            $lotoWithDate,
            $lotos
        );
    }

    /**
     * @param $arrNumber
     * @return array
     */
    protected function getPairNumber($arrNumber)
    {
        $temp = array();
        switch ($this->type) {
            case 2: // Xien 2
                foreach ($arrNumber as $key => $number) {
                    foreach ($arrNumber as $value) {
                        $keyPair = $number . '_' . $value;
                        if ($number > $value) {
                            $keyPair = $value . '_' . $number;
                        }
                        if ($arrNumber[$key] == $value || isset($temp[$keyPair])) continue;
                        $temp[$keyPair] = array($number, $value);
                    }
                }
                break;
            case 3: // Xien 3
                // waiting....
                break;
        }

        return $temp;
    }

}