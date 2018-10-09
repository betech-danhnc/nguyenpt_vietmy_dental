<?php

/**
 * This is the model class for table "hr_holidays".
 *
 * The followings are the available columns in table 'hr_holidays':
 * @property string $id                 Id of record
 * @property string $name               Name of holiday
 * @property string $date               Date value
 * @property integer $type_id           Id of type
 * @property string $compensatory_date  Date compensatory
 * @property string $description        Description
 * @property integer $status            Status
 * @property string $created_date       Created date
 * @property string $created_by         Created by
 *
 * The followings are the available model relations:
 * @property Users                      $rCreatedBy                     User created this record
 * @property HrHolidayTypes             $rType                          Type of holiday
 */
class HrHolidays extends BaseActiveRecord {
    //-----------------------------------------------------
    // Constants
    //-----------------------------------------------------
    /** Inactive */
    const STATUS_INACTIVE               = 0;
    /** Active */
    const STATUS_ACTIVE                 = 1;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return HrHolidays the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hr_holidays';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, date, type_id, description', 'required'),
            array('type_id, status', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('created_by', 'length', 'max' => 10),
            array('compensatory_date, created_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, date, type_id, compensatory_date, description, status, created_date, created_by', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'rCreatedBy' => array(self::BELONGS_TO, 'Users', 'created_by'),
            'rType' => array(
                self::BELONGS_TO, 'HrHolidayTypes', 'type_id',
                'on'    => 'status !=' . HrHolidayTypes::STATUS_INACTIVE,
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id'                => 'ID',
            'name'              => DomainConst::CONTENT00482,
            'date'              => DomainConst::CONTENT00483,
            'type_id'           => DomainConst::CONTENT00480,
            'compensatory_date' => DomainConst::CONTENT00484,
            'description'       => DomainConst::CONTENT00062,
            'status'            => DomainConst::CONTENT00026,
            'created_date'      => DomainConst::CONTENT00010,
            'created_by'        => DomainConst::CONTENT00054,
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('type_id', $this->type_id);
        $criteria->compare('compensatory_date', $this->compensatory_date, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_date', $this->created_date, true);
        $criteria->compare('created_by', $this->created_by, true);
        $criteria->order = 'id desc';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Settings::getListPageSize(),
            ),
        ));
    }

    //-----------------------------------------------------
    // Parent methods
    //-----------------------------------------------------
    /**
     * Override before save
     * @return parent
     */
    protected function beforeSave() {
        $date = $this->date;
        $this->date = CommonProcess::convertDateTime($date,
            DomainConst::DATE_FORMAT_BACK_END,
            DomainConst::DATE_FORMAT_4);
        $date = $this->compensatory_date;
        $this->compensatory_date = CommonProcess::convertDateTime($date,
            DomainConst::DATE_FORMAT_BACK_END,
            DomainConst::DATE_FORMAT_4);
        if ($this->isNewRecord) {
            $this->created_by = Yii::app()->user->id;
            
            // Handle created date
            $this->created_date = CommonProcess::getCurrentDateTime();
        }
        
        return parent::beforeSave();
    }
    
    //-----------------------------------------------------
    // Utility methods
    //-----------------------------------------------------
    /**
     * Get created user
     * @return string
     */
    public function getCreatedBy() {
        if (isset($this->rCreatedBy)) {
            return $this->rCreatedBy->getFullName();
        }
        return '';
    }
    
    /**
     * Return status string
     * @return string Status value as string
     */
    public function getStatus() {
        if (isset(self::getArrayStatus()[$this->status])) {
            return self::getArrayStatus()[$this->status];
        }
        return '';
    }
    
    /**
     * Get plan this date belong to
     * @return Model HrHolidayPlans model if found, NULL otherwise
     */
    public function getHolidayPlanBelongTo() {
        $idPlan = CommonProcess::convertDateTime($this->date, DomainConst::DATE_FORMAT_4, 'Y');
        $mPlan = HrHolidayPlans::model()->findByPk($idPlan);
        if ($mPlan) {
            return $mPlan;
        }
        
        return NULL;
    }
    
    /**
     * Get type
     * @return String Type name
     */
    public function getType() {
        return isset($this->rType) ? $this->rType->name : '';
    }
    
    /**
     * Get factor value
     * @return Double Factor value
     */
    public function getFactorValue() {
        $retVal = 1;
        if (isset($this->rType)) {
            $retVal = $this->rType->factor;
        }
        return $retVal;
    }
    
    //-----------------------------------------------------
    // Static methods
    //-----------------------------------------------------
    /**
     * Get status array
     * @return Array Array status of debt
     */
    public static function getArrayStatus() {
        return array(
            self::STATUS_INACTIVE       => DomainConst::CONTENT00408,
            self::STATUS_ACTIVE         => DomainConst::CONTENT00407,
        );
    }
    
    /**
     * Get number of holiday in range
     * @param String $date_from Date from
     * @param String $date_to Date to
     * @return Int Number of holiday in range
     */
    public static function getNumberOfDayHolidays($date_from, $date_to) {
        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition("t.date", $date_from, $date_to);
        return self::model()->count($criteria);
    }
    
    /**
     * Check if a day is holiday
     * @param String $date Value of date to check
     * @return True if date does exist in db, false otherwise
     */
    public static function isHoliday($date) {
        $criteria = new CDbCriteria();
        $criteria->compare('t.date', $date);
        return self::model()->count($criteria);
    }
    
    /**
     * Find holiday model by date
     * @param String $date Value of date to find
     * @return Model HrHolidays model if found, NULL otherwise
     */
    public static function getHolidayByDate($date) {
        $result = NULL;
        if (isset($date)) {
            $criteria = new CDbCriteria;
            $criteria->addCondition('t.date = "' . $date . '"');
            $criteria->limit = 1;
            $aModel = self::model()->findAll($criteria);
            $result = isset($aModel[0]) ? $aModel[0] : null;
        }
        return $result;
    }
    
    /**
     * Get all holidays in year
     * @param Int $year Value of year
     * @return Array List holidays
     */
    public static function getHolidaysInYear($year) {
        $criteria = new CDbCriteria();
        $criteria->addCondition('YEAR(t.date) = ' . $year);
        $models = self::model()->findAll($criteria);
        $aRetVal = [];
        foreach ($models as $model) {
            // Check if plan was approved
            $plan = HrHolidayPlans::model()->findByPk($year);
            if ($plan && $plan->isApproved()) {
                $aRetVal[$model->date] = $model->date;
            }
        }
        
        return $aRetVal;
    }
}