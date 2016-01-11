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
                <?= $this->element('lang_field') ?>
                <?php
                $country_code_err = $this->Form->error($model_name . '.country_code');
                $country_code_err_class = !empty($country_code_err) ? 'has-error' : '';
                ?>
                <div class="form-group <?php echo $country_code_err_class ?>">
                    <label class="col-sm-2 control-label"><?php echo __('region_country_code') ?> <?php echo $this->element('required') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.country_code', array(
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                            'required' => true,
                            'empty' => '---',
                            'options' => $listCountry,
                            'type' => 'select',
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <?php
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
                <?php
                $address_err = $this->Form->error($model_name . '.address');
                $address_err_class = !empty($address_err) ? 'has-error' : '';
                ?>
                <div class="form-group <?php echo $address_err_class ?>">
                    <label class="col-sm-2 control-label"><?php echo __('region_address') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.address', array(
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <?php
                echo $this->Form->hidden($model_name . '.loc.type', array(
                    'value' => Configure::read('sysconfig.App.GeoJSON_type'),
                ));
                ?>
                <?php
                $longitude_err = $this->Form->error($model_name . '.loc.coordinates.0');
                $longitude_err_class = !empty($longitude_err) ? 'has-error' : '';
                ?>
                <div class="form-group <?php echo $longitude_err_class ?>">
                    <label class="col-sm-2 control-label"><?php echo __('region_longitude') ?> <?php echo $this->element('required') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.loc.coordinates.0', array(
                            'class' => 'form-control longitude',
                            'div' => false,
                            'label' => false,
                            'required' => true,
                            'default' => 0,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <?php
                $latitude_err = $this->Form->error($model_name . '.loc.coordinates.1');
                $latitude_err_class = !empty($latitude_err) ? 'has-error' : '';
                ?>
                <div class="form-group <?php echo $latitude_err_class ?>">
                    <label class="col-sm-2 control-label"><?php echo __('region_latitude') ?> <?php echo $this->element('required') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.loc.coordinates.1', array(
                            'class' => 'form-control latitude',
                            'div' => false,
                            'label' => false,
                            'required' => true,
                            'default' => 0,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('region_categories') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.categories', array(
                            'class' => 'form-control chosen-select',
                            'div' => false,
                            'label' => false,
                            'multiple' => true,
                            'options' => $categories,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <?php
                $area_code_err = $this->Form->error($model_name . '.area_code');
                $area_code_err_class = !empty($area_code_err) ? 'has-error' : '';
                ?>
                <div class="form-group <?php echo $area_code_err_class ?>">
                    <label class="col-sm-2 control-label"><?php echo __('region_area_code') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.area_code', array(
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <?php
                $zip_code_err = $this->Form->error($model_name . '.zip_code');
                $zip_code_err_class = !empty($zip_code_err) ? 'has-error' : '';
                ?>
                <div class="form-group <?php echo $zip_code_err_class ?>">
                    <label class="col-sm-2 control-label"><?php echo __('region_zip_code') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.zip_code', array(
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('region_order') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.order', array(
                            'type' => 'text',
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <?php
                $user = CakeSession::read('Auth.User');
                ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('region_description') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->textarea($model_name . '.description', array(
                            'class' => 'form-control editor',
                            'div' => false,
                            'label' => false,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('region_support') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->textarea($model_name . '.support', array(
                            'class' => 'form-control editor',
                            'div' => false,
                            'label' => false,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('region_guide') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->textarea($model_name . '.guide', array(
                            'class' => 'form-control editor',
                            'div' => false,
                            'label' => false,
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('hotel_tags') ?></label>
                    <div class="col-sm-10"> 
                        <select name="<?php echo 'data[' . $model_name . '][tags][]'; ?>" class="js-example-tags" data-tags="true" tabindex="-1" aria-hidden="true" multiple="true">
                            <?php
                            if (isset($this->request->data[$model_name]['tags'])) {
                                foreach ($this->request->data[$model_name]['tags'] AS $icon) {
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
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('Icon file') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->element('JqueryFileUpload/basic_plus_ui', array(
                            'name' => $model_name . '.files.icon',
                            'options' => array(
                                'id' => 'icon',
                            ),
                            'upload_options' => array(
                                'maxNumberOfFiles' => 1,
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <!--<label class="col-sm-2 control-label"><?php // echo __('Banner file')                       ?></label>-->
                    <label class="col-sm-2 control-label"><?php echo __('Logo file') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->element('JqueryFileUpload/basic_plus_ui', array(
                            'name' => $model_name . '.files.banner',
                            'options' => array(
                                'id' => 'banner',
                            ),
                            'upload_options' => array(
                                'maxNumberOfFiles' => 1,
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <!--<label class="col-sm-2 control-label"><?php // echo __('Logo file')                       ?></label>-->
                    <label class="col-sm-2 control-label"><?php echo __('Banner file') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->element('JqueryFileUpload/basic_plus_ui', array(
                            'name' => $model_name . '.files.logo',
                            'options' => array(
                                'id' => 'logo',
                            ),
                            'upload_options' => array(
                                'maxNumberOfFiles' => 1,
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('Thumbnails file') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->element('JqueryFileUpload/basic_plus_ui', array(
                            'name' => $model_name . '.files.thumbnails',
                            'options' => array(
                                'id' => 'thumbnails',
                                'multiple' => true,
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('Map file') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->element('JqueryFileUpload/basic_plus_ui_view', array(
                            'name' => $model_name . '.files.map',
                            'options' => array(
                                'id' => 'map',
                            ),
                            'upload_options' => array(
//                                                        'maxNumberOfFiles' => 1,
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo __('Video file') ?></label>

                    <div class="col-sm-10">
                        <?php
                        echo $this->element('JqueryFileUpload/basic_plus_ui', array(
                            'name' => $model_name . '.files.video',
                            'options' => array(
                                'id' => 'video',
                            ),
                            'upload_options' => array(
                                'maxFileSize' => Configure::read('sysconfig.App.max_video_file_size_upload'),
                                'maxNumberOfFiles' => 1,
                                'acceptFileTypes' => Configure::read('sysconfig.App.video_upload_types'),
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div>
                    <?php
                    echo $this->element('Streamings/req_save_package');
                    ?>
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