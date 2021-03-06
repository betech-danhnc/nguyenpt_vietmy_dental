<?php
/* @var $this TreatmentTypesController */
/* @var $model TreatmentTypes */

$this->createMenu('index', $model);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#treatment-types-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo $this->pageTitle; ?></h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'treatment-types-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
                array(
                    'header' => DomainConst::CONTENT00034,
                    'type' => 'raw',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                    'headerHtmlOptions' => array('width' => '30px','style' => 'text-align:center;'),
                    'htmlOptions' => array('style' => 'text-align:center;')
                ),
		'name',
		'description',
                array(
                    'name' => 'price',
                    'htmlOptions' => array('style' => 'text-align:right;'),
                    'value' => 'CommonProcess::formatCurrency($data->price)',
                ),
                array(
                    'name' => 'material_price',
                    'htmlOptions' => array('style' => 'text-align:right;'),
                    'value' => 'CommonProcess::formatCurrency($data->material_price)',
                ),
                array(
                    'name' => 'labo_price',
                    'htmlOptions' => array('style' => 'text-align:right;'),
                    'value' => 'CommonProcess::formatCurrency($data->labo_price)',
                ),
		array(
                    'name'=>'group_id',
                    'htmlOptions' => array('style' => 'text-align:center;'),
                    'value'=> 'isset($data->rGroup) ? $data->rGroup->name : ""',
                    'filter'=> TreatmentGroup::loadItems(),
                ),
                array(
                    'name'=>'status',
                    //++ BUG0059-IMT (NguyenPT 20180809) Add new status of TreatmentTypes
//                    'type' => 'Status',
                    'value'     => 'TreatmentTypes::getStatus()[$data->status]',
                    'htmlOptions' => array('style' => 'text-align:center;'),
                    'visible' => CommonProcess::isUserAdmin(),
//                    'filter'=> CommonProcess::getDefaultStatus(true),
                    'filter'=> TreatmentTypes::getStatus(),
                    //-- BUG0059-IMT (NguyenPT 20180809) Add new status of TreatmentTypes
		),
                array(
                    'header' => DomainConst::CONTENT00239,
                    'class'=>'CButtonColumn',
                    'template'=> $this->createActionButtons()
                ),
	),
)); ?>