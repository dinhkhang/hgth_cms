<?php

/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
//App::uses('AppHelper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class CommonHelper extends AppHelper {

        /**
         * Format date from MongoDate
         * @param MonggoDate $mongoDatetime
         * @return string
         */
        public function parseDate($mongoDatetime) {

                if ($mongoDatetime instanceof MongoDate) {

                        return date("d-m-Y", $mongoDatetime->sec);
                }

                return $mongoDatetime ? date("d-m-Y", strtotime($mongoDatetime)) : '';
        }

        /**
         * Format time from MongoDate
         * @param MongoDate $mongoDatetime
         * @return string
         */
        public function parseTime($mongoDatetime) {

                if ($mongoDatetime instanceof MongoDate) {

                        return date("H:i:s", $mongoDatetime->sec);
                }

                return $mongoDatetime ? date("H:i:s", strtotime($mongoDatetime)) : '';
        }

        /**
         * Format date from MongoDate
         * @param MonggoDate $mongoDatetime
         * @return string
         */
        public function parseDateTime($mongoDatetime) {

                if ($mongoDatetime instanceof MongoDate) {

                        return date("d-m-Y H:i:s", $mongoDatetime->sec);
                }

                return $mongoDatetime ? date("d-m-Y H:i:s", strtotime($mongoDatetime)) : '';
        }

        /**
         * Format time from MongoId
         * @param MongoId $mongoId
         * @return string
         */
        public function parseId($mongoId) {

                if ($mongoId instanceof MongoId) {

                        return (string) $mongoId;
                }

                return $mongoId;
        }

        /**
         * Get string location_id
         * @param array $data
         */
        public function getLocationId($data) {
                if (isset($data['location']['_id'])) {
                        return $data['location']['_id']->{'$id'};
                }
        }

        function formatSizeUnits($bytes) {

                if ($bytes >= 1073741824) {

                        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                } elseif ($bytes >= 1048576) {

                        $bytes = number_format($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {

                        $bytes = number_format($bytes / 1024, 2) . ' KB';
                } elseif ($bytes > 1) {

                        $bytes = $bytes . ' bytes';
                } elseif ($bytes == 1) {

                        $bytes = $bytes . ' byte';
                } else {

                        $bytes = '0 bytes';
                }

                return $bytes;
        }

        public function format_report_date($date,$separator = '-'){
            if( !empty($date) && is_numeric($date) && $date > 9999999 && $date < 100000000 ){
                return substr($date, 6, 2).$separator.substr($date, 4, 2).$separator.substr($date, 0, 4);
            }
            return $date;
        }

        public function format_report_week($week){
            if(!empty($week) && is_numeric($week) && $week > 99999 && $week < 1000000  ){
                return "Tuần ".substr($week, 4, 2).' - '.substr($week, 0, 4);
            }
            return $week;
        }

        public function format_report_month($month){
            if(!empty($month) && is_numeric($month) && $month > 99999 && $month < 1000000  ){
                return "Tháng ".substr($month, 4, 2).' - '.substr($month, 0, 4);
            }
            return $month;
        }

        public function format_report_quarter($quarter){
            if( !empty($quarter) && is_numeric($quarter) && $quarter > 99999 && $quarter < 1000000 ){
                return "Quý ".substr($quarter, 4, 2).' - '.substr($quarter, 0, 4);
            }
            return $quarter;
        }

        public function format_report_year($year){
            if( !empty($quarter) && is_numeric($year)){
                return "Năm ".$year;
            }
            return $year;
        }

        public function format_number($number)
        {
            return number_format($number,0,',','.');
        }

        public function add_plus_character($phone) {

            $target = $phone;
            $first = substr($phone, 0, 2);
            if ($first == '84') {

                $target = '+'.$phone;
            }

            return $target;
        }

}
