<?php

App::uses('AppController', 'Controller');

class RegionsController extends AppController
{

    public $uses = array(
        'Region',
    );
    public $components = array(
        'FileCommon',
        'StreamingCommon',
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        // nếu không có quyền truy cập, thì buộc user phải đăng xuất
//        if (!$this->isAllow()) {
//            return $this->redirect($this->Auth->loginRedirect);
//        }
        $this->setInit();
    }

    public function index()
    {
        $options = [
            'order' => array('modified' => 'DESC')
        ];
        $this->getParent();
        $this->setSearchConds($options);
        $this->Paginator->settings = $options;
        $list_data = $this->Paginator->paginate($this->modelClass);

        $this->set([
            'breadcrumb' => [
                array(
                    'url' => Router::url(array('action' => 'index')),
                    'label' => __('region_title'),
                )
            ],
            'page_title' => __('region_title'),
            'list_data' => $list_data
        ]);
    }

    public function add()
    {
        $this->saveNewData();

        $this->set([
            'breadcrumb' => [
                array(
                    'url' => Router::url(array('action' => 'index')),
                    'label' => __('region_title'),
                ),
                array(
                    'url' => Router::url(array('action' => __FUNCTION__)),
                    'label' => __('add_action_title'),
                )
            ],
            'page_title' => __('region_title')
        ]);
    }

    public function edit($id = null)
    {
        if (!$this->{$this->modelClass}->exists($id)) {
            throw new NotFoundException(__('invalid_data'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $save_data = $this->request->data[$this->modelClass];
            $save_data['id']=new MongoId($id);
            if ($this->{$this->modelClass}->save($save_data)) {
                $this->Session->setFlash(__('save_successful_message'), 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {

                $this->Session->setFlash(__('save_error_message'), 'default', array(), 'bad');
            }
        }
        $this->getParent();
        $this->setRequestData($id);

        $this->set([
            'breadcrumb' => [
                array(
                    'url' => Router::url(array('action' => 'index')),
                    'label' => __('region_title'),
                ),
                array(
                    'url' => Router::url(array('action' => __FUNCTION__, $id)),
                    'label' => __('edit_action_title'),
                )
            ],
            'page_title' => __('region_title')
        ]);

        $this->render('add');
    }

    /**
     * @param null $id
     */
    public function cloneRecord($id = null)
    {
        if (!$this->{$this->modelClass}->exists($id)) {
            throw new NotFoundException(__('invalid_data'));
        }

        $this->saveNewData();

        $this->setRequestData($id, true);

        $this->set([
            'breadcrumb' => [
                array(
                    'url' => Router::url(array('action' => 'index')),
                    'label' => __('region_title'),
                ),
                array(
                    'url' => Router::url(array('action' => __FUNCTION__, $id)),
                    'label' => __('clone_action_title'),
                )
            ],
            'page_title' => __('region_title')
        ]);

        $this->render('add');
    }

    /**
     * @param $options
     */
    protected function setSearchConds(&$options)
    {
        if (isset($this->request->query['name']) && strlen(trim($this->request->query['name'])) > 0) {
            $name = trim($this->request->query['name']);
            $this->request->query['name'] = $name;
            $options['conditions']['name']['$regex'] = new MongoRegex("/" . mb_strtolower($name) . "/i");
        }
        if (isset($this->request->query['status']) && strlen($this->request->query['status']) > 0) {
            $status = (int)$this->request->query['status'];
            $options['conditions']['status']['$eq'] = $status;
        }

        if (isset($this->request->query['parent']) && strlen($this->request->query['parent']) > 0) {
            $parent = new MongoId(trim($this->request->query['parent']));
            $this->request->query['parent'] = $parent;
            $options['conditions']['parent'] = $parent;
        }
    }

    protected function setInit()
    {
        $this->set('model_name', $this->modelClass);
        $this->set('status', Configure::read('sysconfig.App.status'));
//        $this->set('listCountry', $this->Country->getListCountryCode());
        $this->set('objectTypeId', $this->object_type_id);
//        $this->set('categories', $this->Category->getListName($this->object_type_id));
//        $this->set('topics', $this->Topic->getListTopicActived());
    }

    private function saveNewData()
    {
        $this->getParent();
        if ($this->request->is('post') || $this->request->is('put')) {
            $save_data = $this->request->data[$this->modelClass];
            if(!empty($save_data['parent'])){
                $save_data['parent'] = new MongoId($this->request->data[$this->modelClass]['parent']);
            }
            if ($this->{$this->modelClass}->save($save_data)) {
                $this->Session->setFlash(__('save_successful_message'), 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {

                $this->Session->setFlash(__('save_error_message'), 'default', array(), 'bad');
            }
        }
    }

    private function getParent()
    {
        $request_data = $this->{$this->modelClass}->find('all', array(
            'conditions' => array(
                'code' => array('$in' => array('mienbac', 'miennam', 'mientrung')),
            ),
        ));
        if (!empty($request_data)) {
            $this->set('parent', $request_data);
        }
    }

    /**
     * @param $id
     * @param bool $clone
     */
    private function setRequestData($id, $clone = false)
    {
        $request_data = $this->{$this->modelClass}->find('first', array(
            'conditions' => array(
                'id' => new MongoId($id),
            ),
        ));

        $this->FileCommon->autoSetFiles($request_data[$this->modelClass]);

        // thực hiện đọc ra thông tin streaming
        $this->StreamingCommon->autoSet($request_data, $id);

        $this->request->data = $request_data;

        if ($clone && isset($this->request->data[$this->modelClass]['id'])) {
            $this->request->data[$this->modelClass]['ref_id'] = $this->request->data[$this->modelClass]['id'];
            unset($this->request->data[$this->modelClass]['id']);
        }
    }

}
