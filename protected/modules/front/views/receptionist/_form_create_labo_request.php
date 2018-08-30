<?php
/* @var $this LaboRequestsController */
/* @var $model LaboRequests */
/* @var $form CActiveForm */
$mLaboServices = new LaboServices();
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'labo-requests-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo DomainConst::CONTENT00081; ?>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'service_id'); ?>
            <?php echo $form->dropDownList($model, 'service_id', $mLaboServices->loadItems(), array('class' => '', 'empty' => 'Select')); ?>
            <?php echo $form->error($model, 'service_id'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'date_request'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'date_request',
                'model' => $model,
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => DomainConst::DATE_FORMAT_2,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showOn' => 'button',
                    'buttonImage' => Yii::app()->theme->baseUrl . '/img/icon_calendar_r.gif',
                    'buttonImageOnly' => true,
                ),
                'htmlOptions' => array(
                    'class' => 'w-16',
                ),
            ));
            ?>
            <?php echo $form->error($model, 'date_request'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'date_receive'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'date_receive',
                'model' => $model,
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => DomainConst::DATE_FORMAT_2,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showOn' => 'button',
                    'buttonImage' => Yii::app()->theme->baseUrl . '/img/icon_calendar_r.gif',
                    'buttonImageOnly' => true,
                ),
                'htmlOptions' => array(
                    'class' => 'w-16',
                ),
            ));
            ?>
            <?php echo $form->error($model, 'date_receive'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'date_test'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'date_test',
                'model' => $model,
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => DomainConst::DATE_FORMAT_2,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showOn' => 'button',
                    'buttonImage' => Yii::app()->theme->baseUrl . '/img/icon_calendar_r.gif',
                    'buttonImageOnly' => true,
                ),
                'htmlOptions' => array(
                    'class' => 'w-16',
                ),
            ));
            ?>
            <?php echo $form->error($model, 'date_test'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo $form->labelEx($model, 'teeths'); ?>
            <?php
            $aData = array(
                'model' => $model
            );
            $this->widget('ext.SelectToothExt.SelectToothExt', array('data' => $aData));
            ?>
            <?php echo $form->error($model, 'teeths'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'price'); ?>
            <?php echo $form->textField($model, 'price'); ?>
            <?php echo $form->error($model, 'price'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 100)); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
    </div>

    <div class="row" style="display: none;">
        <?php echo $form->labelEx($model, 'tooth_color'); ?>
        <?php echo $form->dropDownList($model, 'tooth_color', $model->getItemToothColor(), array('class' => '', 'empty' => 'Select')); ?>
        <?php echo $form->error($model, 'tooth_color'); ?>
    </div>

    <div class="row">
        
    </div>

    <div class="row buttons">
        <?php
        echo CHtml::submitButton(DomainConst::CONTENT00377, array(
                    'name' => DomainConst::KEY_SUBMIT_SAVE,
                ));
        ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/autoNumeric/autoNumeric.js'); ?>
<script>
    $(document).ready(function () {
        $('#LaboRequests_price').autoNumeric('init', {lZero: "deny", aPad: false});
        $('#LaboRequests_service_id').on('change', function () {
            $id_service = $(this).val();
            $.ajax({
                'url': '<?php echo Yii::app()->createAbsoluteUrl('admin/laboServices/getPriceOfService') ?>/id/' + $id_service,
                'success': function (price) {
                    $('#LaboRequests_price').val(price);
                },
            });
        });
    });
</script>