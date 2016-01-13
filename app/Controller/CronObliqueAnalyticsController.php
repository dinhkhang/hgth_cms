<?php
App::import('Service', 'ObliqueAnalytics');

/**
 * Created by PhpStorm.
 * User: huongnx
 * Date: 12/01/2016
 * Time: 13:50
 * @property mixed Region
 * @property mixed XienNumberLuck
 */

class CronObliqueAnalyticsController extends AppController {

    public $uses = array('Region', 'XienNumberLuck');

    public function beforeFilter()
    {
        parent::beforeFilter(); // TODO: Change the autogenerated stub

        $this->Auth->allow();
    }


    public function run()
    {
        $this->layout = false;
        $this->autoRender = false;
        $this->logAnyFile(sprintf('CRON START: %s', date('Y-m-d H:i:s')), __CLASS__.'_'.__FUNCTION__);
        $amplitudes = Configure::read('sysconfig.amplitudesOblique');
        $regions = $this->Region->find('list', array(
            'conditions' => array(
                'parent' => array(
                    '$ne' => null
                )
            ),
            'fields' => 'code'
        ));

        foreach ($amplitudes as $amplitude) {
            foreach ($regions as $region) {
                $oblique = new ObliqueAnalytics();
                $oblique->setAmplitudes($amplitude)
                        ->setRegion($region)
                        ->analytics();

                $results = $oblique->getResult();

                foreach ($results as $pair => $dayOfPair) {
                    $pair = explode('_', $pair);
                    arsort($dayOfPair);

                    $xienNumberData = array(
                        'date' => (int)date('Ymd'),
                        'region_code' => $region,
                        'type' => $oblique->getType(),
                        'span' => $oblique->getAmplitudes(),
                        'number' => $pair,
                        'lucky_dates' => $dayOfPair
                    );

                    // check exists
                    $checkExists = $this->XienNumberLuck->find('first', array(
                        'conditions' => $xienNumberData
                    ));

                    if ($checkExists) {
                        $this->XienNumberLuck->id = $checkExists['XienNumberLuck']['id'];
                    } else {
                        $this->XienNumberLuck->create();
                    }

                    $this->XienNumberLuck->save($xienNumberData);
                }
            }
        }

        $this->logAnyFile(sprintf('CRON END: %s', date('Y-m-d H:i:s')), __CLASS__.'_'.__FUNCTION__);
    }
}