<?php
/* @var $this ActionsRolesController */
/* @var $model ActionsRoles */

//$this->breadcrumbs = $this->createBreadCrumbs('index');
//
//$menus = array(
//	array('label'=>$this->getPageTitleByAction('index'), 'url'=>array('index')),
//	array('label'=>$this->getPageTitleByAction('create'), 'url'=>array('create')),
//);
//$this->menu = AdminController::createOperationMenu($menus, $actions);
$this->createMenu('index', $model);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#actions-roles-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<!--<h1>Manage Actions Roles</h1>-->
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
	'id'=>'actions-roles-grid',
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
//		'id',
		//'role_id',
		array(
                    'name'=>'role_id',
                    'htmlOptions' => array('style' => 'text-align:center;'),
                    'value'=> '!empty($data->role_id) ? $data->role->role_name : ""',
                    'filter'=>Roles::loadItems(),
                ),
		//'controller_id',
		array(
                    'name'=>'controller_id',
                    'htmlOptions' => array('style' => 'text-align:center;'),
                    'value'=> '!empty($data->controller_id) ? $data->controller->name : ""',
                    'filter'=>Controllers::loadItems(),
                ),
		'actions',
		//'can_access',		
                array(
                    'name'=>'can_access',
                    'type' => 'Access',
                    'htmlOptions' => array('style' => 'text-align:center;'),
                    'filter'=> CommonProcess::getDefaultAccessStatus(true),
		),
                array(
                    'header' => DomainConst::CONTENT00239,
                    'class'=>'CButtonColumn',
                    'template'=> $this->createActionButtons()
                ),
	),
)); ?>
