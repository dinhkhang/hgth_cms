<?php
echo $this->element('page-heading-with-add-action');
$user = CakeSession::read('Auth.User');
$permissions = $user['permissions'];
?>
<div class="ibox-content m-b-sm border-bottom">
    <?php
    echo $this->Form->create('Search', array(
        'url'  => array(
            'action'     => $this->action,
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
                    'div'     => false,
                    'class'   => 'form-control',
                    'label'   => __('location_name'),
                    'default' => $this->request->query('name'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('country_code', array(
                    'div'     => false,
                    'class'   => 'form-control',
                    'label'   => __('location_country_code'),
                    'empty'   => '-------',
                    'default' => $this->request->query('country_code'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('region', array(
                    'div'     => false,
                    'class'   => 'form-control',
                    'label'   => __('location_region'),
                    'empty'   => '-------',
                    'default' => $this->request->query('region'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('status', array(
                    'div'     => false,
                    'class'   => 'form-control',
                    'label'   => __('location_status'),
                    'options' => $status,
                    'empty'   => '-------',
                    'default' => $this->request->query('status'),
                ));
                ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php
                echo $this->Form->input('lang_code', array(
                    'div'     => false,
                    'class'   => 'form-control',
                    'label'   => __('lang_code'),
                    'options' => Configure::read('S.Lang'),
                    'empty'   => '-------',
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
                        <th><?php echo __('no') ?></th>
                        <th>
                            <?php
                            echo $this->Paginator->sort('name', __('location_name'));
                            ?>
                        </th>
                        <th>
                            <?php
                            echo $this->Paginator->sort('address', __('location_address'));
                            ?>
                        </th>
                        <th>
                            <?php
                            echo $this->Paginator->sort('country_code', __('location_country_code'));
                            ?>
                        </th>
                        <th>
                            <?php
                            echo $this->Paginator->sort('region', __('location_region'));
                            ?>
                        </th>
                        <th>
                            <?php
                            echo(__('location_status'));
                            ?>
                        </th>
                        <th>
                            <?php
                            echo(__('lang_code'));
                            ?>
                        </th>
                        <th><?php echo __('operation') ?></th>
                    <?php else: ?>
                        <th><?php echo __('no') ?></th>
                        <th><?php echo __('location_name') ?></th>
                        <th><?php echo __('location_address') ?></th>
                        <th><?php echo __('location_country_code') ?></th>
                        <th><?php echo __('location_region') ?></th>
                        <th><?php echo __('location_status') ?></th>
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
                        <tr>
                            <td>
                                <?php
                                $id = $item[$model_name]['id'];
                                echo $this->Form->create($model_name, array('url' => Router::url('edit/' . $id, true)
                                ));
                                echo $this->Form->hidden($model_name . '.id', array(
                                    'value' => $id,
                                ));
                                echo $stt;
                                ?>
                            </td>
                            <td><?php echo $item[$model_name]['name'] ?></td>
                            <td><?php echo $item[$model_name]['address'] ?></td>
                            <td><?php echo $item[$model_name]['country_code'] ?></td>
                            <td>
                                <?php
                                    if ($item[$model_name]['region']) {
                                        $region = isset($item[$model_name]['region']->{'$id'}) ? $item[$model_name]['region']->{'$id'} : $item[$model_name]['region'];
                                    }
                                    echo $listRegion[$region];
                                ?>
                            </td>
                            <td>
                                <?php
                                if (in_array('Locations_edit_status_field', $permissions)) {
                                    echo $this->Form->input('status', array(
                                        'div'     => false,
                                        'class'   => 'form-control',
                                        'label'   => false,
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
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-save"></i>
                                </button>
                                <a href="<?php echo Router::url(array('action' => 'cloneRecord', $id)) ?>"
                                   class="btn btn-primary" title="<?= __('cloneRecord') ?>">
                                    <i class="fa fa-files-o"></i>
                                </a>
                                <a href="<?php echo Router::url(array('action' => 'edit', $id)) ?>"
                                   class="btn btn-primary" title="<?php echo __('edit_btn') ?>">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="<?php echo Router::url(array('action' => 'reqDelete', $id)) ?>"
                                   class="btn btn-danger remove" title="<?php echo __('delete_btn') ?>">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <?php
                                echo $this->Form->end();
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

