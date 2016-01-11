<?php

class UserGroupPermissionsController extends AppController {

    public $uses = array(
        'UserGroupPermission',
    );
    public $components = array('ControllerList');

    public function index() {

        $this->setInit();

        $breadcrumb = array();
        $breadcrumb[] = array(
            'url' => Router::url(array('action' => 'index')),
            'label' => __('user_group_permission_title'),
        );
        $this->set('breadcrumb', $breadcrumb);
        $this->set('page_title', __('user_group_permission_title'));

        $options = array();
        $options['order'] = array('modified' => 'DESC');

        $this->setSearchConds($options);
        $this->Paginator->settings = $options;

        $list_data = $this->Paginator->paginate($this->modelClass);
        $this->set('list_data', $list_data);
    }

    protected function setSearchConds(&$options) {

        $this->commonSearchCondition($options, array(
            'name' => 'string',
            'code' => 'string',
            'module' => 'string',
        ));
    }

    public function add() {

        $this->setInit();

        $breadcrumb = array();
        $breadcrumb[] = array(
            'url' => Router::url(array('action' => 'index')),
            'label' => __('user_group_permission_title'),
        );
        $breadcrumb[] = array(
            'url' => Router::url(array('action' => __FUNCTION__)),
            'label' => __('add_action_title'),
        );
        $this->set('breadcrumb', $breadcrumb);
        $this->set('page_title', __('user_group_permission_title'));

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->{$this->modelClass}->create();
            if ($this->{$this->modelClass}->save($this->request->data[$this->modelClass])) {

                $this->Session->setFlash(__('save_successful_message'), 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {

                $this->Session->setFlash(__('save_error_message'), 'default', array(), 'bad');
            }
        }
    }

    public function edit($id = null) {

        $this->{$this->modelClass}->id = $id;
        if (!$this->{$this->modelClass}->exists()) {

            throw new NotFoundException(__('invalid_data'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->add();
        } else {

            $this->setInit();

            $breadcrumb = array();
            $breadcrumb[] = array(
                'url' => Router::url(array('action' => 'index')),
                'label' => __('user_group_permission_title'),
            );
            $breadcrumb[] = array(
                'url' => Router::url(array('action' => __FUNCTION__, $id)),
                'label' => __('edit_action_title'),
            );
            $this->set('breadcrumb', $breadcrumb);
            $this->set('page_title', __('user_group_permission_title'));

            $this->request->data = $this->{$this->modelClass}->find('first', array(
                'conditions' => array(
                    'id' => new MongoId($id),
                ),
            ));
        }

        $this->render('add');
    }

    public function autoGenerate() {

        $permissions = $this->ControllerList->getPermissions();
        //var_dump($permissions);die;
        if (empty($permissions)) {

            throw new CakeException(__('Can not auto generate any permissions'));
        }

        $save_data = array();
        foreach ($permissions as $key => $perm) {

            foreach ($perm as $v) {

                $exist = $this->{$this->modelClass}->getInfoFromCode($v);
                if (!empty($exist)) {

                    continue;
                }

                $module = $key;
                $save_data[] = array(
                    'name' => $v,
                    'code' => $v,
                    'module' => $module,
                );
            }
        }

        if (empty($save_data)) {

            $this->Session->setFlash(__('nothing_changes_message'), 'default', array(), 'good');
            $this->redirect(array('action' => 'index'));
        }

        if ($this->{$this->modelClass}->saveAll($save_data)) {

            $this->Session->setFlash(__('save_successful_message'), 'default', array(), 'good');
            $this->redirect(array('action' => 'index'));
        } else {

            $this->Session->setFlash(__('save_error_message'), 'default', array(), 'bad');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function setInit() {

        $this->set('model_name', $this->modelClass);
    }

}
