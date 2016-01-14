<?php
echo $this->element('js/datepicker');
echo $this->Html->script('plugins/alphanum/jquery.alphanum');
?>

<script>
    $(document).ready(function () {
        // only allow input numberic
        $('input.number-prize').numeric({
            allowPlus           : false, // Allow the + sign
            allowMinus          : false,  // Allow the - sign
            allowThouSep        : false,  // Allow the thousands separator, default is the comma eg 12,000
            allowDecSep         : false,  // Allow the decimal separator, default is the fullstop eg 3.141
            allowLeadingSpaces  : false
        });

        // redirect by region
        $('#show-region').text($('#region option:selected').text().toUpperCase());

        var region = $('#region');
        var date = $('#date');
        $('#region, #date').change(function () {
            if(region.val() && date.val()) {
                $('#table-form').hide();
                window.location = "<?php echo $root; ?>" + '/' + region.val() + '/' + date.val();
            }
        });

        // save data
        $('.number-prize').change(function () { //use clicks message send button
            var main_data = $(this)
            var max_length = main_data.attr('maxlength');
            if(max_length != main_data.val().length) {
                alert(main_data.val() + ': <?php echo __('leng_not_valid'); ?>: ' + max_length);
                main_data.closest('div').addClass('has-error');
               return;
            }
            if(confirm('<?php echo __('sure'); ?>: ' + main_data.val())) {
                var data = $(this).closest('form').serializeArray();
                $.ajax({
                    type: "POST",
                    data: data,
                    dataType: "JSON",
                    url: "<?php echo $save; ?>" + '/' + region.val() + '/' + date.val(),
                    success: function (data) {
                        main_data.closest('div').removeClass('has-error');
                        main_data.closest('div').addClass(data.class);
                        main_data.closest('div').find('.mongo-id').val(data.id);
                    }
                });
            }
        });

        // datepicker
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

    });
</script>
<style>
    .number-prize {
        text-align: center;
    }
    form {
        margin: 4px auto;
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
                        echo $this->Form->input($model_name . '.region_code', array(
                            'id' => 'region',
                            'class' => 'form-control',
                            'div' => false,
                            'label' => false,
                            'required' => true,
                            'options' => $listRegion,
                            'empty' => '-------',
                        ));
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>


                <div class="form-group">
                    <label
                        class="col-sm-2 control-label"><?php echo __('event_date') ?><?php echo $this->element('required') ?></label>
                    <div class="col-sm-10">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <?php
                            echo $this->Form->input($model_name . '.date', array(
                                'id' => 'date',
                                'class' => 'form-control datepicker',
                                'maxlength' => 10,
                                'div' => false,
                                'label' => false,
                                'required' => true,
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>


                <?php if (isset($showType)) : ?>
                    <table class="table table-bordered table-hover" id="table-form">
                        <tr class="text-center">
                            <td colspan="2">
                                <p>
                                <h1>KẾT QUẢ XỔ SỐ</h1></p>
                                <p>
                                <h2 id="show-region">TỈNH/THÀNH PHỐ</h2></p>
                                <p>
                                <h3 id="show-date"><?php echo $this->Form->value($model_name . '.date'); ?></h3></p>
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