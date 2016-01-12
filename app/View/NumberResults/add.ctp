<?php
echo $this->element('js/datepicker');
?>

<script>
    $(document).ready(function () {
        var region = $('#region option:selected');

        $('#show-region').text(region.text().toUpperCase());
        $('#region').change(function () {
            console.log("<?php echo $root; ?>" + '/' + $('#region').val());
            window.location = "<?php echo $root; ?>" + '/' + $('#region').val();
        });

        $('.number-prize').change(function () { //use clicks message send button
            var main_data = $(this)
            var max_length = main_data.attr('maxlength');
            if(max_length != main_data.val().length) {
                alert(main_data.val() + ': chưa đủ độ dài. Vui lòng nhập số có ' + max_length + ' chữ số!');
                main_data.closest('div').addClass('has-error');
               return;
            }
            if(confirm('Bạn có chắc: ' + main_data.val())) {
                var data = $(this).closest('form').serializeArray();
                $.ajax({
                    type: "POST",
                    data: data,
                    dataType: "JSON",
                    url: "<?php echo $save; ?>" + '/' + region.val(),
                    success: function (data) {
                        main_data.closest('div').addClass(data.class);
                        // update id
                        main_data.closest('div').find('.mongo-id').val(data.id);
                    }
                });
            }
        });
    });
</script>
<style>
    .number-prize {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php
                echo $this->Form->create($model_name, array(
                    'class' => 'form-horizontal',
                    'id' => 'form-lottery'
                ));
                ?>
                <div class="form-group">
                    <label
                        class="col-sm-2 control-label"><?php echo __('region_name') ?><?php echo $this->element('required') ?></label>
                    <div class="col-sm-10">
                        <?php
                        echo $this->Form->input($model_name . '.region', array(
                            'id' => 'region',
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                            'required' => true,
                            'options' => $listRegion,
                            'empty' => '-------',
                            'default' => $this->request->data[$model_name . '.region'],
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>


                <?php if (isset($showType)) : ?>
                    <table class="table table-bordered table-hover">
                        <tr class="text-center">
                            <td colspan="2">
                                <p>
                                <h1>KẾT QUẢ XỔ SỐ</h1></p>
                                <p>
                                <h2 id="show-region">TỈNH/THÀNH PHỐ</h2></p>
                                <p>
                                <h3 id="show-date"><?php echo date('d-m-Y'); ?></h3></p>
                            </td>
                        </tr>
                        <?php foreach ($showType AS $key => $prize) : ?>
                            <tr>
                                <td class="col-md-2"><strong><?php echo $prize['title']; ?></strong></td>
                                <td class="col-md-10">
                                    <div class="row">
                                        <?php for ($i = 0; $i < $prize['data']['number_result']; $i++) : ?>
                                            <div class="col-md-<?php echo $prize['data']['class'][$i]; ?>">
                                                <?php
                                                echo $this->Form->create($model_name, array(
                                                    'class' => 'form-horizontal',
                                                    'id' => 'form-lottery'
                                                ));
                                                echo $this->Form->hidden($model_name . '.date', array(
                                                    'value' => date('Ymd')
                                                ));
                                                echo $this->Form->hidden($model_name . '.region_code', array(
                                                    'value' => $region
                                                ));
                                                echo $this->Form->hidden($model_name . '.type', array(
                                                    'value' => $key
                                                ));
                                                echo $this->Form->hidden($model_name . '.id', array(
                                                    'value' => isset($prize['data']['value'][$i]['id']) ? $prize['data']['value'][$i]['id'] : '',
                                                    'class' => 'mongo-id'
                                                ));
                                                echo $this->Form->input($model_name . '.number', array(
                                                    'id' => 'numbers',
                                                    'class' => 'form-control number-prize',
                                                    'div' => false,
                                                    'label' => false,
                                                    'required' => true,
                                                    'maxlength' => $prize['data']['max_length'],
                                                    'value' => isset($prize['data']['value'][$i]['number']) ? $prize['data']['value'][$i]['number'] : '',
                                                ));
                                                echo $this->Form->end();
                                                ?>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </table>
                <?php endif; ?>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>