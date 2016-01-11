<?php

App::uses('AppModel', 'Model');

class UserGroupPermission extends AppModel {

    public $useTable = 'user_group_permissions';
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'not_empty_validate',
            ),
        ),
        'module' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'not_empty_validate',
            ),
        ),
        'code' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'not_empty_validate',
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'is_unique_validate',
            ),
        ),
    );
    public $customSchema = array(
        'id' => '',
        'name' => '',
        'code' => '',
        'module' => '',
        'description' => '',
        'order' => '',
        'user' => '',
        'created' => '',
        'modified' => '',
    );

    public function getInfoFromCode($code) {


        return $this->find('first', array(
                    'conditions' => array(
                        'code' => $code,
                    ),
        ));
    }

}
