<?php

App::uses('AppModel', 'Model');

class ObjectType extends AppModel {

    public $useTable = 'object_types';

    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        if (
                isset($this->data[$this->alias]['location_relations']) &&
                is_array($this->data[$this->alias]['location_relations']) &&
                !empty($this->data[$this->alias]['location_relations'])
        ) {

            foreach ($this->data[$this->alias]['location_relations'] as $k => $rel) {

                if (is_string($rel)) {

                    $get_rel = $this->find('first', array(
                        'conditions' => array(
                            'id' => new MongoId($rel),
                        ),
                    ));
                    $this->data[$this->alias]['location_relations'][$k] = array(
                        '_id' => new MongoId($rel),
                        'code' => $get_rel[$this->alias]['code'],
                    );
                }
            }
        }
    }

    public function afterFind($results, $primary = false) {
        parent::afterFind($results, $primary);

        if (!empty($results)) {

            foreach ($results as $k => $v) {

                if (
                        isset($v[$this->alias]['location_relations']) &&
                        is_array($v[$this->alias]['location_relations']) &&
                        !empty($v[$this->alias]['location_relations'])
                ) {

                    foreach ($v[$this->alias]['location_relations'] as $kk => $vv) {

                        if (!empty($vv['_id'])) {

                            $results[$k][$this->alias]['location_relations'][$kk] = (string) $vv['_id'];
                        }
                    }
                }

                if (
                        isset($v[$this->alias]['parent_id']) &&
                        !empty($v[$this->alias]['parent_id']) &&
                        $v[$this->alias]['parent_id'] instanceof MongoId
                ) {

                    $v[$this->alias]['parent_id'] = (string) $v[$this->alias]['parent_id'];
                }
            }
        }

        return $results;
    }

    public function getObjectTypeId($code) {

        $object_type = $this->find('first', array(
            'conditions' => array(
                'code' => $code,
            ),
        ));

        return !empty($object_type) ? $object_type[$this->alias]['id'] : null;
    }

    public function getObjectTypeCode($id) {

        $object_type = $this->find('first', array(
            'conditions' => array(
                'id' => new MongoId($id),
            ),
        ));

        return !empty($object_type) ? $object_type[$this->alias]['code'] : null;
    }

    public function getList()
    {
        $conditions['conditions'] = [
            'status' => Configure::read('sysconfig.App.constants.STATUS_APPROVED')
        ];

        if (isset($_GET['lang_code'])) {
            $conditions['conditions']['lang_code'] = $_GET['lang_code'];
        }

        return $this->find('list', [
            $conditions,
            'fields' => ['id', 'name']
        ]);
    }

    public function reqObjectByObjectType($request)
    {
        $object = [];
        $objectType = $this->findById($request['object_type_id']);
        if ($objectType) {
            $objectModel = Inflector::classify($objectType['ObjectType']['code']);
            App::import('Model', $objectModel);

            if(class_exists($objectModel)) {
                $this->objectModel = new $objectModel;
                $conditions = [
                    'status' => Configure::read('sysconfig.App.constants.STATUS_APPROVED')
                ];
                if (isset($request['lang_code'])) {
                    $conditions['lang_code'] = $request['lang_code'];
                }
                $object = $this->objectModel->find('list', [
                    'conditions' => $conditions,
                    'fields' => ['id', 'name']
                ]);
            }
        }
        return $object;

    }

}
