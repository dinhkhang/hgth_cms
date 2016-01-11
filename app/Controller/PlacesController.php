<?php

/**
 * @property mixed LocationCommon
 * @property mixed User
 * @property mixed FileCommon
 * @property mixed StreamingCommon
 */
class PlacesController extends AppController
{

    public $uses = array(
        'Place',
        'Country',
        'Region',
        'Location',
        'Category',
        'Collection',
        'ObjectType',
        'ObjectIcon',
        'Topic',
        'User',
        'Tag'
    );
    public $components = array(
        'FileCommon',
        'LocationCommon',
        'StreamingCommon',
    );
    public $object_type_id = null;

    public function index()
    {
        $this->checkAuth();
        $options = [
            'order' => array('modified' => 'DESC')
        ];

        $this->setSearchConds($options);
        $this->Paginator->settings = $options;

        $list_data = $this->Paginator->paginate($this->modelClass, [], ['order', 'modified', 'user', 'name', 'rating.score', 'rating.count']);
        $this->LocationCommon->setInfo($list_data);
        $this->setUserInfoInList($list_data);

        $this->set([
            'users' => $this->User->getListName(),
            'breadcrumb' => [
                array(
                    'url'   => Router::url(array('action' => 'index')),
                    'label' => __('place_title'),
                )
            ],
            'list_data' => $list_data,
            'page_title' => __('place_title')
        ]);
    }

    public function add()
    {
        $this->checkAuth();
        $this->saveNewData();
        $this->LocationCommon->autoInit();

        $this->set([
            'breadcrumb' => [
                array(
                    'url'   => Router::url(array('action' => 'index')),
                    'label' => __('place_title'),
                ),
                array(
                    'url'   => Router::url(array('action' => __FUNCTION__)),
                    'label' => __('add_action_title'),
                )
            ],
            'page_title' => __('place_title')
        ]);
    }

    public function edit($id = null)
    {
        $this->checkAuth();
        if (!$this->{$this->modelClass}->exists($id)) {
            throw new NotFoundException(__('invalid_data'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $save_data = $this->request->data[$this->modelClass];
            $old_data = $this->{$this->modelClass}->find('first', array(
                'conditions' => array(
                    'id' => new MongoId($id),
                ),
            ));

            // upload file
            $this->FileCommon->autoProcess($save_data);
            $this->LocationCommon->autoProcess($save_data, array(
                'old_data' => $old_data[$this->modelClass],
            ));
            $this->FileCommon->autoGetMap($save_data, 'P');

            // xử lý streaming
            $this->StreamingCommon->autoProcess($this->request->data, $id);

            if ($this->{$this->modelClass}->save($save_data)) {
                $this->addTagByFullName($save_data, $this->modelClass);
                $this->Session->setFlash(__('save_successful_message'), 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {

                $this->Session->setFlash(__('save_error_message'), 'default', array(), 'bad');
            }
        } else {
            $this->setRequestData($id);
            $this->set([
                'breadcrumb' => [
                    array(
                        'url'   => Router::url(array('action' => 'index')),
                        'label' => __('place_title'),
                    ),
                    array(
                        'url'   => Router::url(array('action' => __FUNCTION__, $id)),
                        'label' => __('edit_action_title'),
                    )
                ],
                'page_title' => __('place_title')
            ]);
        }
        $this->render('add');
    }

    public function cloneRecord($id = null)
    {
        $this->checkAuth();
        $this->saveNewData();

        $this->setRequestData($id, true);
        $this->set([
            'breadcrumb' => [
                array(
                    'url'   => Router::url(array('action' => 'index')),
                    'label' => __('place_title'),
                ),
                array(
                    'url'   => Router::url(array('action' => __FUNCTION__, $id)),
                    'label' => __('clone_action_title'),
                )
            ],
            'page_title' => __('place_title')
        ]);
        $this->render('add');
    }

    protected function setSearchConds(&$options)
    {
        $this->commonSearchCondition($options);
    }

    protected function setInit()
    {
        $this->set('controller_name', $this->name);
        $this->set('model_name', $this->modelClass);
        $this->set('status', Configure::read('sysconfig.App.status'));
        $this->set('categories', $this->Category->getListName($this->object_type_id));
        $this->set('collections', $this->Collection->getListName($this->object_type_id));
        $this->set('objectIcon', $this->ObjectIcon->getList($this->object_type_id));
        $this->set('topics', $this->Topic->getListTopic($this->object_type_id));
    }

    // edit field search
    public function beforeSearch(&$customSearchField)
    {
        parent::beforeSearch($customSearchField);
    }

    // edit option search
    public function afterSearch(&$options)
    {
        parent::afterSearch($options);
    }

    private function saveNewData()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $save_data = $this->request->data[$this->modelClass];
            // upload file
            $this->FileCommon->autoProcess($save_data);
            $this->LocationCommon->autoProcess($save_data);
            $this->FileCommon->autoGetMap($save_data, 'P');

            if ($this->{$this->modelClass}->save($save_data)) {
                // xử lý streaming
                $this->StreamingCommon->autoProcess($this->request->data, $this->{$this->modelClass}->getLastInsertID());

                $this->Session->setFlash(__('save_successful_message'), 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('save_error_message'), 'default', array(), 'bad');
            }
        }
    }

    /**
     * @param $id
     * @param bool $clone
     */
    private function setRequestData($id, $clone = false)
    {
        $this->LocationCommon->autoInit();

        $data = $this->{$this->modelClass}->find('first', array(
            'conditions' => array(
                'id' => new MongoId($id),
            ),
        ));

        $this->FileCommon->autoSetFiles($data[$this->modelClass]);
        $this->LocationCommon->autoSet($data[$this->modelClass]);

        // thực hiện đọc ra thông tin streaming
        $this->StreamingCommon->autoSet($data, $id);

        $this->request->data = $data;

        if ($clone && isset($this->request->data[$this->modelClass]['id'])) {
            $this->request->data[$this->modelClass]['ref_id'] = $this->request->data[$this->modelClass]['id'];
            unset($this->request->data[$this->modelClass]['id']);
        }
    }

    private function checkAuth()
    {
        if (!$this->isAllow()) {

            return $this->redirect($this->Auth->loginRedirect);
        }
        $this->setInit();
    }

}
