<?php
/* @var $model HrWorkPlans */
/* @var $form CActiveForm */
/* @var $canSearch boolean */
?>

<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
//        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'role_id'); ?>
            <?php echo $form->dropdownlist($model, 'role_id', Roles::getRoleArrayForSalary()); ?>
        </div>
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'department_id'); ?>
            <?php echo $form->dropdownlist($model, 'department_id', Departments::loadItems(true)); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'month'); ?>
            <?php
            $month = $model->month;
            if (empty($month)) {
                $month = CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_13);
            }
            $this->widget('DatePickerWidget', array(
                'model'         => $model,
                'field'         => 'month',
                'value'         => $month,
                'isReadOnly'    => false,
                'format'        => DomainConst::DATE_FORMAT_14,
            ));
            ?>
        </div>
        <div class="col-md-6">
            <?php echo $form->labelEx($model, 'agent_id'); ?>
            <?php echo $form->dropdownlist($model, 'agent_id', Agents::loadItems(true)); ?>
        </div>
    </div>

    <div class="row buttons" style="<?php echo !$canSearch ? 'display: none;' : '' ?>">
        <?php echo CHtml::submitButton('Search', array(
            'name'  => 'search',
            'style' => 'margin: 10px 10px 10px 154px; background: teal',
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->