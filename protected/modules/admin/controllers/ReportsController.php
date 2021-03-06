<?php

class ReportsController extends AdminController {

    public $layout = '//layouts/column1';

    /**
     * Index action.
     */
    public function actionIndex() {
        $this->render('index', array(
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

    /**
     * Revenue action.
     */
    public function actionRevenue() {
        $agentId = isset(Yii::app()->user->agent_id) ? Yii::app()->user->agent_id : '';
        $mAgent = Agents::model()->findByPk($agentId);
        $mAgent->created_by = '';
        $mAgent->doctor_id = '';
        if(empty($mAgent)){
            $mAgent = new Agents();
            $mAgent->unsetAttributes();
        }
        
        $dateFormat = DomainConst::DATE_FORMAT_4;
        $from = '';
        $to = '';
        // Get data from url
        $this->validateRevenueUrl($from, $to);
        if (empty($from)) {
            $from = CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_4);
        }
        if (empty($to)) {
            $to = CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_4);
        }
        // Get agent info
        $agentId = '';
        $arrAgentId = array();
        if (isset($_GET['agentId'])) {
            $agentId = $_GET['agentId'];
        }
        if (isset($_POST['agentId'])) {
            $agentId = $_POST['agentId'];
        }
        if (empty($agentId)) {
            $arrAgentId = CommonProcess::getCurrentAgentIdArray();
        } else {
            $arrAgentId[] = $agentId;
        }
        $mAgent->agent_id = $arrAgentId;
        $receipts = Agents::getRevenueMultiAgent($from, $to, array(Receipts::STATUS_RECEIPTIONIST), false, $arrAgentId);
        $allReceipts = Agents::getRevenueMultiAgent($from, $to, array(Receipts::STATUS_RECEIPTIONIST), true, $arrAgentId);
        $newReceipts = Agents::getRevenueMultiAgent($from, $to, array(Receipts::STATUS_DOCTOR), false, $arrAgentId);

        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT)) {
            $this->redirect(array('revenue',
                'from' => CommonProcess::convertDateTime($_POST['from_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat),
                'to' => CommonProcess::convertDateTime($_POST['to_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_MONTH)) {
            $from = CommonProcess::getFirstDateOfCurrentMonth($dateFormat);
            $this->redirect(array('revenue',
                'from' => $from,
                'to' => CommonProcess::getLastDateOfMonth($from),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_LAST_MONTH)) {
            $from = CommonProcess::getPreviousMonth($dateFormat);
            $this->redirect(array('revenue',
                'from' => CommonProcess::getFirstDateOfMonth($from),
                'to' => CommonProcess::getLastDateOfMonth($from),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_TODATE)) {
            $from = CommonProcess::getCurrentDateTime($dateFormat);
            $this->redirect(array('revenue',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_DATE_YESTERDAY)) {
            $from = CommonProcess::getPreviousDateTime($dateFormat);
            $this->redirect(array('revenue',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_DATE_BEFORE_YESTERDAY)) {
            $from = CommonProcess::getDateBeforeYesterdayDateTime($dateFormat);
            $this->redirect(array('revenue',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_EXCEL)) {
            $from = CommonProcess::convertDateTime($_POST['from_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat);
            $to = CommonProcess::convertDateTime($_POST['to_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat);
//            $this->exportExcel($from, $to, $agentId);
            ExcelHandler::summaryReportMoney($mAgent, $from, $to,true);
        }
        $this->render('revenue', array(
            'receipts' => $receipts,
            'allReceipts' => $allReceipts,
            'newReceipts' => $newReceipts,
            'from' => $from,
            'to' => $to,
            'agentId' => $agentId,
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

    /**
     * Validate revenue url
     * @param String $from From
     * @param String $to To
     */
    public function validateRevenueUrl(&$from, &$to) {
        $from = isset($_GET['from']) ? $_GET['from'] : '';
        $to = isset($_GET['to']) ? $_GET['to'] : '';
    }
    
    /**
     * Handle export excel
     * @param String $from      From
     * @param String $to        To
     * @param String $agentId Id of agent
     */
    public function exportExcel($from, $to, $agentId) {
        if (!empty($agentId)) {
            $mAgent = Agents::model()->findByPk($agentId);
            if ($mAgent) {
                ExcelHandler::summaryReportMoney($mAgent, $from, $to);
            }
        }
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */

    public function actionReportMoney() {
        $dateFormat = DomainConst::DATE_FORMAT_4;
        $agentId = isset(Yii::app()->user->agent_id) ? Yii::app()->user->agent_id : '';
        $mAgent = Agents::model()->findByPk($agentId);
        if(empty($mAgent)){
            $mAgent = new Agents();
        }
        $from = '';
        $to = '';
        // Get data from url
        $this->validateRevenueUrl($from, $to);
        if (empty($from)) {
            $from = CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_4);
        }
        if (empty($to)) {
            $to = CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_4);
        }
        
        // Get agent info
        $agentId = '';
        $arrAgentId = array();
        if (isset($_GET['agentId'])) {
            $agentId = $_GET['agentId'];
        }
        if (isset($_POST['agentId'])) {
            $agentId = $_POST['agentId'];
        }
        if (empty($agentId)) {
            $arrAgentId = CommonProcess::getCurrentAgentIdArray();
        } else {
            $arrAgentId[] = $agentId;
        }
        $mAgent->agent_id = $arrAgentId;

        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT)) {
            $this->redirect(array('ReportMoney',
                'from' => CommonProcess::convertDateTime($_POST['from_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat),
                'to' => CommonProcess::convertDateTime($_POST['to_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_MONTH)) {
            $from = CommonProcess::getFirstDateOfCurrentMonth($dateFormat);
            $this->redirect(array('ReportMoney',
                'from' => $from,
                'to' => CommonProcess::getLastDateOfMonth($from),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_LAST_MONTH)) {
            $from = CommonProcess::getPreviousMonth($dateFormat);
            $this->redirect(array('ReportMoney',
                'from' => CommonProcess::getFirstDateOfMonth($from),
                'to' => CommonProcess::getLastDateOfMonth($from),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_TODATE)) {
            $from = CommonProcess::getCurrentDateTime($dateFormat);
            $this->redirect(array('ReportMoney',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_DATE_YESTERDAY)) {
            $from = CommonProcess::getPreviousDateTime($dateFormat);
            $this->redirect(array('ReportMoney',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_DATE_BEFORE_YESTERDAY)) {
            $from = CommonProcess::getDateBeforeYesterdayDateTime($dateFormat);
            $this->redirect(array('ReportMoney',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_EXCEL)) {
            $from = CommonProcess::convertDateTime($_POST['from_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat);
            $to = CommonProcess::convertDateTime($_POST['to_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat);
            ExcelHandler::summaryReportMoney($mAgent, $from, $to,true);
        }
        
        $this->render('report_money', array(
            'from' => $from,
            'to' => $to,
            'aData' => $mAgent->getReportMoney($from, $to,true),
            'agentId' => $agentId,
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

    /**
     * report Customer
     */
    public function actionCustomer() {
        $dateFormat = DomainConst::DATE_FORMAT_4;
        $agentId = isset(Yii::app()->user->agent_id) ? Yii::app()->user->agent_id : '';
        $mAgent = Agents::model()->findByPk($agentId);
        $mAgent->created_by = '';
        $mAgent->doctor_id = '';
        if(empty($mAgent)){
            $mAgent = new Agents();
            $mAgent->unsetAttributes();
        }
        
        $from = '';
        $to = '';
        $old = null;
        $new = null;
        // Get data from url
        $this->validateRevenueUrl($from, $to);
        if (empty($from)) {
            $from = CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_4);
        }
        if (empty($to)) {
            $to = CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_4);
        }
        
        // Get agent info
        $agentId = '';
        $arrAgentId = array();
        if (isset($_GET['agentId'])) {
            $agentId = $_GET['agentId'];
        }
        if (isset($_POST['agentId'])) {
            $agentId = $_POST['agentId'];
        }
        if (empty($agentId)) {
            $arrAgentId = CommonProcess::getCurrentAgentIdArray();
        } else {
            $arrAgentId[] = $agentId;
        }
        $mAgent->agent_id = $arrAgentId;
        // Start access db

        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT)) {
            $this->redirect(array('customer',
                'from' => CommonProcess::convertDateTime($_POST['from_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat),
                'to' => CommonProcess::convertDateTime($_POST['to_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_MONTH)) {
            $from = CommonProcess::getFirstDateOfCurrentMonth($dateFormat);
            $this->redirect(array('customer',
                'from' => $from,
                'to' => CommonProcess::getLastDateOfMonth($from),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_LAST_MONTH)) {
            $from = CommonProcess::getPreviousMonth($dateFormat);
            $this->redirect(array('customer',
                'from' => CommonProcess::getFirstDateOfMonth($from),
                'to' => CommonProcess::getLastDateOfMonth($from),
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_TODATE)) {
            $from = CommonProcess::getCurrentDateTime($dateFormat);
            $this->redirect(array('customer',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_DATE_YESTERDAY)) {
            $from = CommonProcess::getPreviousDateTime($dateFormat);
            $this->redirect(array('customer',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_DATE_BEFORE_YESTERDAY)) {
            $from = CommonProcess::getDateBeforeYesterdayDateTime($dateFormat);
            $this->redirect(array('customer',
                'from' => $from,
                'to' => $from,
                'agentId' => $agentId,
            ));
        }
        if (filter_input(INPUT_POST, DomainConst::KEY_SUBMIT_EXCEL)) {
            $from = CommonProcess::convertDateTime($_POST['from_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat);
            $to = CommonProcess::convertDateTime($_POST['to_date'], DomainConst::DATE_FORMAT_BACK_END, $dateFormat);
            ExcelHandler::summaryReportMoney($mAgent, $from, $to,true);
        }
        if (!empty($_GET['Agents'])) {
            $mAgent->attributes = $_GET['Agents'];
        }
        if ($mAgent) {
            $data = $mAgent->getCustomers($from, $to,true);
            $old = !empty($data['OLD']) ? $data['OLD'] : null;
            $new = !empty($data['NEW']) ? $data['NEW'] : null;
        }
        $this->render('customer', array(
            'old' => $old,
            'new' => $new,
            'model' => $mAgent,
            'from' => $from,
            'to' => $to,
            'agentId' => $agentId,
            DomainConst::KEY_ACTIONS => $this->listActionsCanAccess,
        ));
    }

}
