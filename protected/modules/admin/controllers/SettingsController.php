<?php

class SettingsController extends AdminController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * Settings array
     * @var Array 
     */
    public $aSettings = array(
        // Group General setting
        Settings::KEY_GENERAL_SETTINGS => array(
            DomainConst::KEY_ALIAS => DomainConst::CONTENT00160,
            DomainConst::KEY_CHILDREN => array(
                Settings::KEY_TITLE,
                Settings::KEY_DOMAIN,
                Settings::KEY_DOMAIN_SALE_WEBSITE,
                Settings::KEY_LIST_PAGE_SIZE,
                Settings::KEY_PASSWORD_LEN_MIN,
                Settings::KEY_PASSWORD_LEN_MAX,
                Settings::KEY_NUM_QRCODE_DOWNLOAD_MAX,
                Settings::KEY_PRINT_RECEIPT_FONT_SIZE_RATE,
                Settings::KEY_SMS_PROVIDER,
                Settings::KEY_TOOTH_COLOR,
                Settings::KEY_NEWS_DATE_OF_HOT_NEWS,
                Settings::KEY_HR_WORK_ON_SATURDAY,
                Settings::KEY_HR_WORKSHIFT_OFF_DAY_PER_MONTH,
                Settings::KEY_SOURCE_INFO_WEBSITE,
                Settings::KEY_SOURCE_INFO_APP,
                Settings::KEY_OTP_LIMIT_TIME,
            /** Test */
//                    Settings::KEY_APP_MOBILE_VERSION_IOS,
//                    Settings::KEY_ADMIN_EMAIL,
//                    Settings::KEY_EMAIL_MAIN_SUBJECT,
//                    Settings::KEY_EMAIL_TRANSPORT_TYPE,
//                    Settings::KEY_EMAIL_TRANSPORT_HOST,
//                    Settings::KEY_EMAIL_TRANSPORT_USERNAME,
//                    Settings::KEY_EMAIL_TRANSPORT_PASSWORD,
//                    Settings::KEY_EMAIL_TRANSPORT_PORT,
//                    Settings::KEY_EMAIL_TRANSPORT_ENCRYPTION,
            ),
        ),
        // App setting
        Settings::KEY_APP_SETTINGS => array(
            DomainConst::KEY_ALIAS => DomainConst::CONTENT00163,
            DomainConst::KEY_CHILDREN => array(
                Settings::KEY_APP_MOBILE_VERSION_IOS,
                Settings::KEY_APP_API_LIST_PAGE_SIZE,
            ),
        ),
        // Mail setting
        Settings::KEY_MAIL_SETTINGS => array(
            DomainConst::KEY_ALIAS => DomainConst::CONTENT00161,
            DomainConst::KEY_CHILDREN => array(
                Settings::KEY_ADMIN_EMAIL,
                Settings::KEY_EMAIL_MAIN_SUBJECT,
                Settings::KEY_EMAIL_TRANSPORT_TYPE,
                Settings::KEY_EMAIL_TRANSPORT_HOST,
                Settings::KEY_EMAIL_TRANSPORT_USERNAME,
                Settings::KEY_EMAIL_TRANSPORT_PASSWORD,
                Settings::KEY_EMAIL_TRANSPORT_PORT,
                Settings::KEY_EMAIL_TRANSPORT_ENCRYPTION,
                Settings::KEY_EMAIL_SENDGRID_API_KEY,
                Settings::KEY_EMAIL_PROVIDER,
            ),
        ),
        // SMS setting
        Settings::KEY_SMS_SETTINGS => array(
            DomainConst::KEY_ALIAS => DomainConst::CONTENT00261,
            DomainConst::KEY_CHILDREN => array(
                Settings::KEY_SMS_FUNC_ON_OFF,
                Settings::KEY_SMS_SERVER_URL,
                Settings::KEY_VIVAS_SMS_SERVER_URL,
                Settings::KEY_VIVAS_URL_LOGIN,
                Settings::KEY_VIVAS_URL_SEND_SMS,
                Settings::KEY_VIVAS_URL_SEND_SMS_EXT,
                Settings::KEY_VIVAS_URL_VERIFY,
                Settings::KEY_VIVAS_URL_LOGOUT,
                Settings::KEY_VIVAS_USERNAME,
                Settings::KEY_VIVAS_PASSWORD,
                Settings::KEY_VIVAS_SHARE_KEY,
            ),
        ),
        // SMS type send
        Settings::KEY_SMS_SETTING_SENDS => array(
            DomainConst::KEY_ALIAS => 'Tuỳ chọn gửi sms',
            DomainConst::KEY_CHILDREN => array(
                Settings::KEY_SMS_SEND_CREATE_SCHEDULE,
                Settings::KEY_SMS_SEND_UPDATE_SCHEDULE,
                Settings::KEY_SMS_SEND_CREATE_SCHEDULE_DETAIL,
                Settings::KEY_SMS_SEND_CREATE_RECEIPT,
                Settings::KEY_SMS_SEND_ALARM_SCHEDULE,
            ),
        ),
        // SMS type send
        Settings::KEY_API_SETTINGS => array(
            DomainConst::KEY_ALIAS => 'Cài đặt API',
            DomainConst::KEY_CHILDREN => array(
                Settings::KEY_WORDPRESS_API_TOKEN,
            ),
        ),
        // Logger
        Settings::KEY_LOGGER_SETTINGS => array(
            DomainConst::KEY_ALIAS => 'Logger',
            DomainConst::KEY_CHILDREN => array(
                Settings::KEY_LOG_API_REQUEST,
                Settings::KEY_LOG_GENERAL,
                Settings::KEY_LOG_USER_ACTIVITY,
                Settings::KEY_LOG_ACTIVE_RECORD,
            ),
        ),
        // Jr
        Settings::KEY_HR_SETTINGS => array(
            DomainConst::KEY_ALIAS => DomainConst::CONTENT00559,
            DomainConst::KEY_CHILDREN => array(
                Settings::KEY_SALARY_TYPE_TIMESHEET,
                Settings::KEY_SALARY_TYPE_EFFICIENCY,
                Settings::KEY_HOLIDAY_COMPENSATORY,
            ),
        ),
            // TODO: Add more group here
    );

    /**
     * array type
     */
    public $aTypeView = [
        'CheckBox' => [
            Settings::KEY_SMS_SEND_CREATE_SCHEDULE,
            Settings::KEY_SMS_SEND_UPDATE_SCHEDULE,
            Settings::KEY_SMS_SEND_CREATE_SCHEDULE_DETAIL,
            Settings::KEY_SMS_SEND_CREATE_RECEIPT,
            Settings::KEY_SMS_FUNC_ON_OFF,
            //TODO: Add more checkbook here
            Settings::KEY_SMS_SEND_ALARM_SCHEDULE,
            Settings::KEY_HR_WORK_ON_SATURDAY,
            Settings::KEY_LOG_API_REQUEST,
            Settings::KEY_LOG_GENERAL,
            Settings::KEY_LOG_USER_ACTIVITY,
            Settings::KEY_LOG_ACTIVE_RECORD,
        ],
        //TODO: Add more type input here
    ];

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($key = '') {
        $model = new Settings;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $model->key = $key;

        if (isset($_POST['Settings'])) {
            $model->attributes = $_POST['Settings'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Settings'])) {
            $model->attributes = $_POST['Settings'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Settings('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Settings'])) {
            $model->attributes = $_GET['Settings'];
        }

        // Start handle save
        if (filter_input(INPUT_POST, 'submit')) {
            foreach (Settings::loadItems() as $key => $value) {
                if (isset($_POST[$value->id])) {
                    Settings::updateSetting($value->id, $_POST[$value->id]);
                }
            }
            Yii::app()->user->setFlash(DomainConst::KEY_SUCCESS_UPDATE, DomainConst::CONTENT00035);
            $this->refresh();
        }

        $this->render('index', array(
            'model' => $model,
            'aSettings' => $this->aSettings,
            'aTypeView' => $this->aTypeView,
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Settings('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Settings'])) {
            $model->attributes = $_GET['Settings'];
        }

        $this->render('admin', array(
            'model' => $model,
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Settings the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Settings::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Settings $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'settings-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
