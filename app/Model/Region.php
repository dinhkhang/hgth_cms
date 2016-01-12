<?php

App::uses('AppModel', 'Model');

class Region extends AppModel
{

    public $useTable = 'regions';
    public $customSchema = array(
        'id'            => '',
        'name'          => '',
        'code'          => '',
        'alias'         => '',
        'parent'        => null,
        'status'        => '',
        'user'          => '',
        'created'       => '',
        'modified'      => '',
    );

    public function findListName()
    {
        $conditions = [];
        if (isset($_GET['lang_code']) && $_GET['lang_code']) {
            $conditions['lang_code'] = $_GET['lang_code'];
        }

        return $this->find('list', ['conditions' => $conditions, 'fields' => ['name']]);
    }

    public function findNameById($id)
    {
        $result = $this->find('first', ['fields' => ['name'], 'conditions' => ['id' => $id]]);

        return isset($result['Region']['name']) ? $result['Region']['name'] : '';
    }

    public function searchByName($region)
    {
        $return = $optionsRegion = [];
        $optionsRegion['conditions']['name']['$regex'] = new MongoRegex("/" . mb_strtolower($region) . "/i");
        $result = $this->find('list', $optionsRegion);
        foreach ($result AS $key => $item) {
            $return[] = new MongoId($key);
            unset($item);
        }

        return $return;
    }

    public function beforeValidate($options = array())
    {
        parent::beforeValidate($options);

        if (
            isset($this->data[$this->alias]['loc']['coordinates']) &&
            isset($this->data[$this->alias]['loc']['coordinates'][0]) &&
            isset($this->data[$this->alias]['loc']['coordinates'][1])
        ) {

            $long = floatval(trim($this->data[$this->alias]['loc']['coordinates'][0]));
            $lat = floatval(trim($this->data[$this->alias]['loc']['coordinates'][1]));
            $is_valid = 1;

            if ($long > 180 || $long < -180) {

                $this->validationErrors['loc']['coordinates'][0] = __('location_common_invalid_long');
                $is_valid = 0;
            }

            if ($lat > 90 || $lat < -90) {

                $this->validationErrors['loc']['coordinates'][1] = __('location_common_invalid_lat');
                $is_valid = 0;
            }

            if (!$is_valid) {

                return false;
            }
        }
    }

    public function beforeSave($options = array())
    {
        parent::beforeSave($options);

        if (isset($this->data[$this->alias]['name'])) {

            $this->data[$this->alias]['name'] = trim($this->data[$this->alias]['name']);
        }

        if (
            !empty($this->data[$this->alias]['topics']) &&
            is_array($this->data[$this->alias]['topics'])
        ) {

            foreach ($this->data[$this->alias]['topics'] as $k => $v) {

                if (!($v instanceof MongoId)) {

                    $this->data[$this->alias]['topics'][$k] = new MongoId($v);
                }
            }
        }
    }

    public function afterFind($results, $primary = false)
    {
        parent::afterFind($results, $primary);

        if (!empty($results)) {

            foreach ($results as $k => $v) {

                if (empty($v[$this->alias]['categories']) || !is_array($v[$this->alias]['categories'])) {

                    continue;
                }

                foreach ($v[$this->alias]['categories'] as $kk => $vv) {

                    if (is_object($vv)) {

                        $results[$k][$this->alias]['categories'][$kk] = (string)$vv;
                    }
                }
            }
        }

        return $results;
    }
    public function beforeDelete($cascade = true)
    {
        parent::beforeDelete($cascade);

        $region = $this->find('first', array(
            'conditions' => array(
                'id' => $this->id,
            ),
        ));
    }

}
