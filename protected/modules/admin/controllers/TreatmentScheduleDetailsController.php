<?php

class TreatmentScheduleDetailsController extends AdminController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
//	public function filters()
//	{
//		return array(
//			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
//		);
//	}
//
//	/**
//	 * Specifies the access control rules.
//	 * This method is used by the 'accessControl' filter.
//	 * @return array access control rules
//	 */
//	public function accessRules()
//	{
//		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'users'=>array('@'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//		);
//	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
                        DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TreatmentScheduleDetails;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TreatmentScheduleDetails']))
		{
			$model->attributes=$_POST['TreatmentScheduleDetails'];
			if($model->save()) {
                            if (filter_input(INPUT_POST, 'submit')) {
                                $index = 0;
                                foreach (CommonProcess::getListTeeth() as $teeth) {
                                    if (isset($_POST['teeth'][$index])
                                            && ($_POST['teeth'][$index] == DomainConst::CHECKBOX_STATUS_CHECKED)) {
                                        OneMany::insertOne($model->id, $index, OneMany::TYPE_TREATMENT_DETAIL_TEETH);
                                    }
                                    $index++;
                                }
                            }
                            $this->redirect(array('view','id'=>$model->id));
                        }
		}

		$this->render('create',array(
			'model'=>$model,
                        DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TreatmentScheduleDetails']))
		{
			$model->attributes=$_POST['TreatmentScheduleDetails'];
			if($model->save()) {
                                // Remove old record
                                OneMany::deleteAllOldRecords($model->id, OneMany::TYPE_TREATMENT_DETAIL_TEETH);
                                $index = 0;
                                foreach (CommonProcess::getListTeeth() as $teeth) {
                                    if (isset($_POST['teeth'][$index])
                                            && ($_POST['teeth'][$index] == DomainConst::CHECKBOX_STATUS_CHECKED)) {
                                        OneMany::insertOne($model->id, $index, OneMany::TYPE_TREATMENT_DETAIL_TEETH);
                                    }
                                    $index++;
                                }
                            $this->redirect(array('view','id'=>$model->id));
                        }
		}

		$this->render('update',array(
			'model'=>$model,
                        DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
		));
	}
        
        /**
         * Handle update image XRay
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
         */
        public function actionUpdateImageXRay($id) {
		$model=$this->loadModel($id);
//                $mImageXRayFile = new Files();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['TreatmentScheduleDetails']))
		{
//			$model->attributes=$_POST['TreatmentScheduleDetails'];
//                        $mImageXRayFile->attributes = $_POST['Files'];
                        Files::deleteFileInUpdateNotIn($model);
                        Files::saveRecordFile($model, Files::TYPE_2_TREATMENT_SCHEDULE_DETAIL_XRAY);
			$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('updateImageXRay',array(
			'model'=>$model,
                        DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
		));
        }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
//		$dataProvider=new CActiveDataProvider('TreatmentScheduleDetails');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));
		$model=new TreatmentScheduleDetails('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TreatmentScheduleDetails']))
			$model->attributes=$_GET['TreatmentScheduleDetails'];

		$this->render('index',array(
			'model'=>$model,
                        DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TreatmentScheduleDetails('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TreatmentScheduleDetails']))
			$model->attributes=$_GET['TreatmentScheduleDetails'];

		$this->render('admin',array(
			'model'=>$model,
                        DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TreatmentScheduleDetails the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TreatmentScheduleDetails::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TreatmentScheduleDetails $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='treatment-schedule-details-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
