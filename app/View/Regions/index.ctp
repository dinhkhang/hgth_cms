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
                if(!empty($parent)){
                    foreach($parent as $item){
                        $parent_id[$item['Region']['id']] =$item['Region']['name'];
                    }
                }
                echo $this->Form->input('parent', array(
                    'div' => false,
                    'class' => 'form-control',
                    'label' => __('regions'),
                    'options' => !empty($parent_id)?$parent_id:"",
                    'empty' => '-------',
                    'default' => $this->request->query('parent'),
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
                        <th style="width:5%;"><?php echo __('no') ?></th>
                        <th style="width:30%;">
                            <?php
                            echo $this->Paginator->sort('name', __('region_name'));
                            ?>
                        </th>
                        <th style="width:20%;">
                            <?php
                            echo 'Alias';
                            ?>
                        </th>
                        <th style="width:25%;">
                            <?php
                            echo(__('region_status'));
                            ?>
                        </th>
                        <th style="width:20%;"><?php echo __('operation') ?></th>
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
                            </td>
                            <td>
                                <strong>
                                    <?php
                                    if(!empty($item[$model_name]['alias'])){
                                        foreach($item[$model_name]['alias'] as $alias){
                                            echo $alias."\t";
                                        }
                                    }
                                    ?>
                                </strong>
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

