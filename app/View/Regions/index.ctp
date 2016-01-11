<?php
echo $this->element('js/chosen');
echo $this->element('page-heading-with-add-action');
$user = CakeSession::read('Auth.User');
$permissions = $user['permissions'];
?>
<div class="ibox-content m-b-sm border-bottom">
    <?php
    echo $this->Form->create('Search', array(
        'url' => array(
            'action' => $this->action,
            'controller' => Inflector::pluralize($model_name),
        ),
        'type' => 'get',
    ))
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('country_code', array(
                    'div' => false,
                    'class' => 'form-control',
                    'label' => __('country'),
                    'empty' => '-------',
                    'options' => $listCountry,
                    'default' => $this->request->query('country_code'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('name', array(
                    'div' => false,
                    'class' => 'form-control',
                    'label' => __('region_name'),
                    'default' => $this->request->query('name'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('categories', array(
                    'div' => false,
                    'class' => 'form-control',
                    'label' => __('region_categories'),
                    'options' => $categories,
                    'empty' => '-------',
                    'default' => $this->request->query('categories'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('area_code', array(
                    'div' => false,
                    'class' => 'form-control',
                    'label' => __('region_area_code'),
                    'empty' => '-------',
                    'default' => $this->request->query('area_code'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('zip_code', array(
                    'div' => false,
                    'class' => 'form-control',
                    'label' => __('region_zip_code'),
                    'empty' => '-------',
                    'default' => $this->request->query('zip_code'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('status', array(
                    'div' => false,
                    'class' => 'form-control',
                    'label' => __('region_status'),
                    'options' => $status,
                    'empty' => '-------',
                    'default' => $this->request->query('status'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('lang_code', array(
                    'div' => false,
                    'class' => 'form-control',
                    'label' => __('lang_code'),
                    'options' => Configure::read('S.Lang'),
                    'empty' => '-------',
                    'default' => $this->request->query('lang_code'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div>
                <label style="visibility: hidden"><?php echo __('search_btn') ?></label>
            </div>
            <?php echo $this->element('buttonSearchClear'); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <?php if (!empty($list_data)): ?>
                            <th style="width:4%;"><?php echo __('no') ?></th>
                            <th style="width:25%;">
                                <?php
                                echo $this->Paginator->sort('name', __('region_name'));
                                ?>
                            </th>
                            <th style="width:35%;">
                                <?php
                                echo __('region_categories');
                                ?>
                            </th>
                            <th style="width:20%;">
                                <?php
                                echo (__('region_status'));
                                ?>
                            </th>
                            <th style="width:20%">
                                <?php
                                echo (__('lang_code'));
                                ?>
                            </th>
                            <th style="width:15%;"><?php echo __('operation') ?></th>
                        <?php else: ?>
                            <th><?php echo __('no') ?></th>
                            <th><?php echo __('region_name') ?></th>
                            <th><?php echo __('region_categories') ?></th>
                            <th><?php echo __('region_status') ?></th>
                            <th><?php echo __('lang_code') ?></th>
                            <th><?php echo __('operation') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_data)): ?>
                        <?php
                        $stt = $this->Paginator->counter('{:start}');
                        ?>
                        <?php foreach ($list_data as $item): ?>
                            <tr class="form-edit">
                                <td>
                                    <?php
                                    $id = $item[$model_name]['id'];
                                    echo $this->Form->hidden('id', array(
                                        'value' => $id,
                                    ));
                                    echo $stt;
                                    ?>
                                </td>
                                <td>
                                    <strong>
                                        <?php echo $item[$model_name]['name'] ?>
                                    </strong>
                                    <br/>
                                    <small>
                                        <?php echo __('country_code') ?>: <?php
                                        echo isset($item[$model_name]['country_code']) ? $item[$model_name]['country_code'] : ''
                                        ?>
                                    </small>
                                    <br/>
                                    <small>
                                        <?php echo __('region_area_code') ?>: <?php
                                        echo isset($item[$model_name]['area_code']) ? $item[$model_name]['area_code'] : ''
                                        ?>
                                    </small>
                                    <br/>
                                    <small>
                                        <?php echo __('region_zip_code') ?>: <?php
                                        echo isset($item[$model_name]['zip_code']) ? $item[$model_name]['zip_code'] : ''
                                        ?>
                                    </small>
                                </td>
                                <td>
                                    <?php
                                    echo $this->Form->input('categories', array(
                                        'div' => false,
                                        'class' => 'form-control chosen-select',
                                        'label' => false,
                                        'options' => $categories,
                                        'default' => !empty($item[$model_name]['categories']) ?
                                                $item[$model_name]['categories'] : array(),
                                        'multiple' => true,
                                    ));
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (in_array('Regions_edit_status_field', $permissions)) {
                                        echo $this->Form->input('status', array(
                                            'div' => false,
                                            'class' => 'form-control',
                                            'label' => false,
                                            'options' => $status,
                                            'default' => $item[$model_name]['status'],
                                        ));
                                    } else {

                                        echo $status[$item[$model_name]['status']];
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?= Configure::read('S.Lang')[$item[$model_name]['lang_code']];?>
                                </td>
                                <td>
                                    <?php
                                    echo $this->element('Button/edit', array(
                                        'action' => 'cloneRecord',
                                        'id' => $id,
                                    ));
                                    ?>
                                    <?php
                                    echo $this->element('Button/submit_form_edit', array(
                                        'id' => $id,
                                        'permissions' => $permissions,
                                    ));
                                    ?>
                                    <?php
                                    echo $this->element('Button/edit', array(
                                        'id' => $id,
                                        'permissions' => $permissions,
                                    ));
                                    ?>
                                    <?php
                                    echo $this->element('Button/delete', array(
                                        'id' => $id,
                                        'permissions' => $permissions,
                                    ));
                                    ?>
                                    <?php
                                    if (empty($controller)) {

                                        $controller = 'RegionActivities';
                                    }
                                    if (empty($action)) {

                                        $action = 'index';
                                    }
                                    $action_path = Router::url(array(
                                                'action' => $action,
                                                'controller' => $controller,
                                                '?' => array('objectId' => $id, 'objectTypeId' => $objectTypeId)
                                    ));
                                    ?>
                                    <?php if (in_array($controller . '/' . $action, $permissions)): ?>
                                        <a href="<?php echo $action_path ?>" class="btn btn-primary" title="<?php echo __('region_activities_index_btn') ?>">
                                            <i class="fa fa-paper-plane-o"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $stt++; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center"><?php echo __('no_result') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $this->element('pagination'); ?>
    </div>
</div>

