<?php

App::uses('AppController', 'Controller');

class RegionsController extends AppController
{

    public $uses = array(
        'Region',
        'Country',
        'ObjectType',
        'Topic',
        'Category'
    );
    public $components = array(
        'FileCommon',
        'StreamingCommon',
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        // nếu không có quyền truy cập, thì buộc user phải đăng xuất
        if (!$this->isAllow()) {
            return $this->redirect($this->Auth->loginRedirect);
        }
        $this->setInit();
    }

    public function index()
    {
        $options = [
            'order' => array('modified' => 'DESC')
        ];

        $this->setSearchConds($options);
        $this->Paginator->settings = $options;
        $list_data = $this->Paginator->paginate($this->modelClass);

        $this->set([
            'breadcrumb' => [
                array(
                    'url'   => Router::url(array('action' => 'index')),
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
                    'url'   => Router::url(array('action' => 'index')),
                    'label' => __('region_title'),
                ),
                array(
                    'url'   => Router::url(array('action' => __FUNCTION__)),
                    'label' => __('add_action_title'),
                )
            ],
            'page_title' =>  __('region_title')
        ]);
    }

    public function edit($id = null)
    {
        if (!$this->{$this->modelClass}->exists($id)) {
            throw new NotFoundException(__('invalid_data'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $save_data = $this->request->data[$this->modelClass];
            $this->FileCommon->autoProcess($save_data);
            $this->FileCommon->autoGetMap($save_data, 'L');

            // xử lý streaming
            $this->StreamingCommon->autoProcess($this->request->data, $id);

            if ($this->{$this->modelClass}->save($save_data)) {
                $this->addTagByFullName($save_data, $this->modelClass);
                $this->Session->setFlash(__('save_successful_message'), 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {

                $this->Session->setFlash(__('save_error_message'), 'default', array(), 'bad');
            }
        }

        $this->setRequestData($id);

        $this->set([
            'breadcrumb' => [
                array(
                    'url'   => Router::url(array('action' => 'index')),
                    'label' => __('region_title'),
                ),
                array(
                    'url'   => Router::url(array('action' => __FUNCTION__, $id)),
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
                    'url'   => Router::url(array('action' => 'index')),
                    'label' => __('region_title'),
                ),
                array(
                    'url'   => Router::url(array('action' => __FUNCTION__, $id)),
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

        if (isset($this->request->query['country_code']) && strlen(trim($this->request->query['country_code'])) > 0) {
            $code = trim($this->request->query['country_code']);
            $this->request->query['country_code'] = $code;
            $options['conditions']['country_code']['$regex'] = new MongoRegex("/" . mb_strtolower($code) . "/i");
        }

        if (isset($this->request->query['area_code']) && strlen(trim($this->request->query['area_code'])) > 0) {
            $area_code = trim($this->request->query['area_code']);
            $this->request->query['area_code'] = $area_code;
            $options['conditions']['area_code']['$regex'] = new MongoRegex("/" . mb_strtolower($area_code) . "/i");
        }

        if (isset($this->request->query['zip_code']) && strlen(trim($this->request->query['zip_code'])) > 0) {
            $zip_code = trim($this->request->query['zip_code']);
            $this->request->query['zip_code'] = $zip_code;
            $options['conditions']['zip_code']['$regex'] = new MongoRegex("/" . mb_strtolower($zip_code) . "/i");
        }

        if (isset($this->request->query['status']) && strlen($this->request->query['status']) > 0) {
            $status = (int)$this->request->query['status'];
            $options['conditions']['status']['$eq'] = $status;
        }

        if (isset($this->request->query['lang_code']) && strlen($this->request->query['lang_code']) > 0) {
            $langCode = $this->request->query['lang_code'];
            $options['conditions']['lang_code']['$eq'] = $langCode;
        }

        if (isset($this->request->query['categories']) && strlen($this->request->query['categories']) > 0) {
            $categories = new MongoId(trim($this->request->query['categories']));
            $this->request->query['categories'] = $categories;
            $options['conditions']['categories'] = $categories;
        }
    }

    protected function setInit()
    {
        $this->set('model_name', $this->modelClass);
        $this->set('status', Configure::read('sysconfig.App.status'));
        $this->set('listCountry', $this->Country->getListCountryCode());
        $this->set('objectTypeId', $this->object_type_id);
        $this->set('categories', $this->Category->getListName($this->object_type_id));
        $this->set('topics', $this->Topic->getListTopicActived());
    }

    private function saveNewData()
    {
        if ($this->request->is('post') || $this->request->is('put')) {

            $save_data = $this->request->data[$this->modelClass];
            $this->FileCommon->autoProcess($save_data);
            $this->FileCommon->autoGetMap($save_data, 'L');

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
