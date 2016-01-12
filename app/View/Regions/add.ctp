<?php
echo $this->element('js/chosen');
// sử dụng công cụ soạn thảo
echo $this->element('js/tinymce');
// sử dụng upload file
echo $this->element('JqueryFileUpload/basic_plus_ui_assets');
echo $this->element('js/validate');

// streaming
echo $this->element('Streamings/req_save.js');

$user = CakeSession::read('Auth.User');
$permissions = $user['permissions'];
?>
<script>
    $(function () {

        $('form').validate();
        $(".longitude").rules("add", {
            number: true,
            range: [-180, 180]
        });
        $(".latitude").rules("add", {
            number: true,
            range: [-90, 90]
        });
    });
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?php
                echo $this->Form->create($model_name, array(
                    'class' => 'form-horizontal',
                ));
                ?>
                <?php
                $country_code_err = $this->Form->error($model_name . '.country_code');
                $country_code_err_class = !empty($country_code_err) ? 'has-error' : '';
                $name_err = $this->Form->error($model_name . '.name');
                $name_err_class = !empty($name_err) ? 'has-error' : '';
                ?>
                <div class="form-group <?php echo $name_err_class ?>">
                    <label class="col-sm-2 control-label"><?php echo __('region_name') ?> <?php echo $this->element('required') ?></label>
                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.name', array(
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                            'required' => true,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group <?php echo $name_err_class ?>">
                    <label class="col-sm-2 control-label"><?php echo 'Code' ?> <?php echo $this->element('required') ?></label>
                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.code', array(
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                            'required' => true,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('region_categories') ?></label>
                    <div class="col-sm-10">
                        <?php
                        if(!empty($parent)){
                            foreach($parent as $item){
                                $parent_id[$item['Region']['id']] =$item['Region']['name'];
                            }
                        }
                        echo $this->Form->input($model_name . '.parent', array(
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                            'empty' => '-------',
                            'options' => !empty($parent_id)?$parent_id:"",/*danhmuc*/
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <?php
                $area_code_err = $this->Form->error($model_name . '.area_code');
                $area_code_err_class = !empty($area_code_err) ? 'has-error' : '';
                ?>
                <?php
                $user = CakeSession::read('Auth.User');
                ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo 'Alias' ?></label>
                    <div class="col-sm-10">
                        <select name="<?php echo 'data[' . $model_name . '][alias][]'; ?>" class="js-example-tags" data-tags="true" tabindex="-1" aria-hidden="true" multiple="true">
                            <?php
                            if (isset($this->request->data[$model_name]['alias'])) {
                                foreach ($this->request->data[$model_name]['alias'] AS $icon) {
                                    ?>
                                    <option value="<?php echo $icon; ?>" selected><?php echo $icon; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <?php
                // ẩn edit status đối với user có type là CONTENT_EDITOR
                if (in_array('Regions_edit_status_field', $permissions)):
                    ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo __('region_status') ?></label>

                        <div class="col-sm-10">
                            <?php
                            echo $this->Form->input($model_name . '.status', array(
                                'class' => 'form-control',
                                'div' => false,
                                'label' => false,
                                'default' => 1,
                                'options' => $status,
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <?php
                endif;
                ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <a href="<?php echo Router::url(array('action' => 'index')) ?>" class="btn btn-white"><i class="fa fa-ban"></i> <span><?php echo __('cancel_btn') ?></span> </a>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <span><?php echo __('save_btn') ?></span> </button>
                    </div>
                </div>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>