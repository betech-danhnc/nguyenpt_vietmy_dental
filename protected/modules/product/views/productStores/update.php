<?php
/* @var $this ProductStoresController */
/* @var $model ProductStores */

$this->createMenu('update', $model);
?>

<h1><?php echo $this->pageTitle . ' ' . $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>