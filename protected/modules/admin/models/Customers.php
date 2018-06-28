<?php

/**
 * This is the model class for table "customers".
 *
 * The followings are the available columns in table 'customers':
 * @property string $id
 * @property string $name
 * @property integer $gender
 * @property string $date_of_birth
 * @property string $year_of_birth
 * @property string $phone
 * @property string $email
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $ward_id
 * @property string $street_id
 * @property string $house_numbers
 * @property string $address
 * @property integer $type_id
 * @property integer $career_id
 * @property string $user_id
 * @property string $debt
 * @property integer $status
 * @property string $characteristics
 * @property string $created_by
 * @property string $created_date
 */
class Customers extends BaseActiveRecord
{
    public $autocomplete_name_user;
    public $autocomplete_name_street;
    public $agent;
    public $referCode;
    public $autocomplete_name_refercode;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, city_id, district_id', 'required'),
			array('gender, city_id, district_id, ward_id, type_id, career_id, status', 'numerical', 'integerOnly'=>true),
			array('name, house_numbers', 'length', 'max'=>255),
			array('year_of_birth', 'length', 'max'=>4),
			array('phone, email', 'length', 'max'=>200),
			array('street_id, user_id, created_by', 'length', 'max'=>11),
			array('debt', 'length', 'max'=>10),
			array('date_of_birth, address, characteristics', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, gender, date_of_birth, year_of_birth, phone, email, city_id, district_id, ward_id, street_id, house_numbers, address, type_id, career_id, user_id, status, characteristics, created_by, created_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'rCity' => array(self::BELONGS_TO, 'Cities', 'city_id'),
                    'rDistrict' => array(self::BELONGS_TO, 'Districts', 'district_id'),
                    'rWard' => array(self::BELONGS_TO, 'Wards', 'ward_id'),
                    'rStreet' => array(self::BELONGS_TO, 'Streets', 'street_id'),
                    'rType' => array(self::BELONGS_TO, 'CustomerTypes', 'type_id'),
                    'rCareer' => array(self::BELONGS_TO, 'Careers', 'career_id'),
                    'rUser' => array(self::BELONGS_TO, 'Users', 'user_id'),
                    'rCreatedBy' => array(self::BELONGS_TO, 'Users', 'created_by'),
                    'rStreet' => array(self::BELONGS_TO, 'Streets', 'street_id'),
                    'rMedicalRecord' => array(
                        self::HAS_ONE, 'MedicalRecords', 'customer_id',
                        'on' => 'status = 1',
                    ),
                    'rJoinAgent' => array(
                        self::HAS_MANY, 'OneMany', 'many_id',
                        'on'    => 'type = ' . OneMany::TYPE_AGENT_CUSTOMER,
                    ),
                    'rReferCode' => array(
                        self::HAS_ONE, 'ReferCodes', 'object_id',
                        'on'    => 'type = ' . ReferCodes::TYPE_CUSTOMER,
                    ),
                    'rSocialNetwork' => array(
                        self::HAS_MANY, 'SocialNetworks', 'object_id',
                        'on'    => 'type = ' . SocialNetworks::TYPE_CUSTOMER,
                    ),
                    'rWarranty' => array(self::HAS_MANY, 'Warranties', 'customer_id',
                        'on' => 'status!=' . DomainConst::DEFAULT_STATUS_INACTIVE,
                        'order' => 'id ASC',
                        ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => DomainConst::CONTENT00100,
			'gender' => DomainConst::CONTENT00047,
			'date_of_birth' => DomainConst::CONTENT00101,
			'year_of_birth' => DomainConst::CONTENT00368,
			'phone' => DomainConst::CONTENT00048,
			'email' => DomainConst::CONTENT00040,
			'city_id' => DomainConst::CONTENT00102,
			'district_id' => DomainConst::CONTENT00103,
			'ward_id' => DomainConst::CONTENT00104,
			'street_id' => DomainConst::CONTENT00105,
			'house_numbers' => DomainConst::CONTENT00106,
			'address' => DomainConst::CONTENT00045,
			'type_id' => DomainConst::CONTENT00107,
			'career_id' => DomainConst::CONTENT00099,
			'user_id' => DomainConst::CONTENT00008,
			'debt' => DomainConst::CONTENT00300,
			'status' => DomainConst::CONTENT00026,
			'characteristics' => DomainConst::CONTENT00108,
			'created_by' => DomainConst::CONTENT00054,
			'created_date' => DomainConst::CONTENT00010,
                        'agent' => DomainConst::CONTENT00199,
                        'referCode' => DomainConst::CONTENT00271,
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('date_of_birth',$this->date_of_birth,true);
		$criteria->compare('year_of_birth',$this->year_of_birth,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('district_id',$this->district_id);
		$criteria->compare('ward_id',$this->ward_id);
		$criteria->compare('street_id',$this->street_id,true);
		$criteria->compare('house_numbers',$this->house_numbers,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('career_id',$this->career_id);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('debt',$this->debt,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('characteristics',$this->characteristics,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('created_date',$this->created_date,true);
                $criteria->order = 'created_date DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array(
                            'pageSize' => Settings::getListPageSize(),
                        ),
		));
	}
        
    //-----------------------------------------------------
    // Parent override methods
    //-----------------------------------------------------
    /**
     * Override before save method
     * @return Parent result
     */
    public function beforeSave() {
        $userId = isset(Yii::app()->user) ? Yii::app()->user->id : '';
        // Format birthday value
        $date = $this->date_of_birth;
        $this->date_of_birth = CommonProcess::convertDateTimeToMySqlFormat(
                $date, DomainConst::DATE_FORMAT_3);
        if (empty($this->date_of_birth)) {
            $this->date_of_birth = CommonProcess::convertDateTimeToMySqlFormat(
                        $date, DomainConst::DATE_FORMAT_4);
        }
        if (empty($this->date_of_birth)) {
            $this->date_of_birth = $date;
        }
        $this->address = CommonProcess::createAddressString(
                $this->city_id, $this->district_id,
                $this->ward_id, $this->street_id,
                $this->house_numbers);
        if ($this->isNewRecord) {   // Add
            // Handle created by
            if (empty($this->created_by)) {
                $this->created_by = $userId;
            }
            // Handle created date
            $this->created_date = CommonProcess::getCurrentDateTime();
            
        } else {                    // Update
            
        }
        return parent::beforeSave();
    }
    
    /**
     * Override before delete method
     * @return Parent result
     */
    public function beforeDelete() {
        // Handle relation
        if (isset($this->rMedicalRecord)) {
            $this->rMedicalRecord->delete();
        }
        if (isset($this->rReferCode)) {
            $this->rReferCode->delete();
        }
        if (isset($this->rWarranty)) {
            foreach ($this->rWarranty as $warranty) {
                $warranty->delete();
            }
        }
        // Handle Agent relation
        OneMany::deleteAllManyOldRecords($this->id, OneMany::TYPE_AGENT_CUSTOMER);
        // Handle Social network relation
        SocialNetworks::deleteAllOldRecord($this->id, SocialNetworks::TYPE_CUSTOMER);
        Loggers::info("Deleted " . get_class($this) . " with id = $this->id.", __FUNCTION__, __LINE__);
        return parent::beforeDelete();
    }

    //-----------------------------------------------------
    // Utility methods
    //-----------------------------------------------------
    /**
     * Loads the application items for the specified type from the database
     * @param type $emptyOption boolean the item is empty
     * @return type List data
     */
    public static function loadItems($emptyOption = false) {
        $_items = array();
        if ($emptyOption) {
            $_items[""] = "";
        }
        $models = self::model()->findAll(array(
            'order' => 'id ASC',
        ));
        foreach ($models as $model) {
            $_items[$model->id] = $model->name;
        }
        return $_items;
    }

    /**
     * Get autocomplete customer name
     * @return String [username - last_name first_name]
     */
    public function getAutoCompleteCustomerName() {
        $retVal = $this->name;
        $retVal .= ' - ' . $this->phone;
        $retVal .= ' -  SN: ' . $this->date_of_birth;
        return $retVal;
    }
    
    /**
     * Check if customer have schedule date
     * @return Id of treatment schedule (have not detail data), empty otherwise
     */
    public function getSchedule($isActive = true) {
        $retVal = '';
        // Get medical record info and treatment schedule info
        if (isset($this->rMedicalRecord) && isset($this->rMedicalRecord->rTreatmentSchedule)) {
            if (count($this->rMedicalRecord->rTreatmentSchedule) == 0) {
                // Have no any treatment schedule
                $retVal = '';
            } else { // #0
                // Get newest record
                $schedule = $this->rMedicalRecord->rTreatmentSchedule[0];
                if ($isActive
                        && ($schedule->status == TreatmentSchedules::STATUS_COMPLETED)) {
//                // Status of schedule is Completed
//                if ($schedule->status == TreatmentSchedules::STATUS_COMPLETED) {
                    $retVal = '';
                } else {
                    if (isset($schedule->rDetail)) {
                        if (count($schedule->rDetail) == 0) {                        
                            $retVal = '';
                        } else { // #0
                            if ($schedule->rDetail[0]->isSchedule()) {
                                return $schedule->rDetail[0]->id;
                            } else {
                                $retVal = '';
                            }
                        }
                    } else {
                        $retVal = '';
                    }
                }                
            }
        }
        return $retVal;
    }
    
    /**
     * Get value of id
     * @return String
     */
    public function getId() {
        $retVal = CommonProcess::generateID(DomainConst::CUSTOMER_ID_PREFIX, $this->id);
        return $retVal;
    }
    
    /**
     * Get name of agent
     * @return Name of agent
     */
    public function getAgentName() {
        if (isset($this->rJoinAgent) && count($this->rJoinAgent) > 0) {
            if (isset($this->rJoinAgent[0]->rAgent)) {
                return $this->rJoinAgent[0]->rAgent->name;
            }
        }
        return '';
    }
    
    /**
     * Get id of agent
     * @return Id of agent
     */
    public function getAgentId() {
        if (isset($this->rJoinAgent) && count($this->rJoinAgent) > 0) {
            if (isset($this->rJoinAgent[0]->rAgent)) {
                return $this->rJoinAgent[0]->rAgent->id;
            }
        }
        return '';
    }
    
    /**
     * Get birthday with format
     * @return String Birthday with format {01 thg 11, 2018}
     */
    public function getBirthday() {
        $retVal = '';
        if (!DateTimeExt::isYearNull($this->year_of_birth)) {
            $retVal = $this->year_of_birth;
        }
        
        if (empty($retVal) && !DateTimeExt::isDateNull($this->date_of_birth)) {
            $date = CommonProcess::convertDateTime($this->date_of_birth,
                                DomainConst::DATE_FORMAT_4,
                                DomainConst::DATE_FORMAT_5);
            if (empty($date)) {
                $date = CommonProcess::convertDateTime($this->date_of_birth,
                                DomainConst::DATE_FORMAT_1,
                                DomainConst::DATE_FORMAT_5);
            }
            $retVal = $date;
        }
        
        return $retVal;
    }
    
    /**
     * Get birth year
     * @return String Birthday with format {2018}
     */
    public function getBirthYear() {
        $retVal = '';
        if (!DateTimeExt::isDateNull($this->date_of_birth)) {
            $retVal = CommonProcess::convertDateTime($this->date_of_birth,
                            DomainConst::DATE_FORMAT_4,
                            'Y');
        } else if (!DateTimeExt::isYearNull($this->year_of_birth)) {
            $retVal = $this->year_of_birth;
        }
        
        
        return $retVal;
    }
    
    /**
     * Get age of customer
     * $return String Age of customer
     */
    public function getAge() {
        $retVal = '0';
        $age = '';
        if (!DateTimeExt::isDateNull($this->date_of_birth)) {
            $age = DateTime::createFromFormat(
                DomainConst::DATE_FORMAT_4,
                $this->date_of_birth);
        } else if (!DateTimeExt::isYearNull($this->year_of_birth)) {
            $age = DateTime::createFromFormat(
                'Y',
                $this->year_of_birth);
        }
        if ($age) {
            $retVal = $age->diff(new DateTime('now'))->y;
        }
        return $retVal . 't';
    }
    
    /**
     * Get phone value
     * @return String
     */
    public function getPhone() {
        return isset($this->phone) ? $this->phone : '';
    }
    
    /**
     * Get email value
     * @return String
     */
    public function getEmail() {
//        return isset($this->email) ? $this->email : '';
        if (isset($this->rSocialNetwork)) {
            foreach ($this->rSocialNetwork as $value) {
                if ($value->type_network == SocialNetworks::TYPE_NETWORK_EMAIL) {
                    return $value->value;
                }
                $retVal[] = SocialNetworks::TYPE_NETWORKS[$value->type_network] . ": $value->value";
            }
        }
        return '';
    }
    
    /**
     * Get address value
     * @return String
     */
    public function getAddress() {
        return isset($this->address) ? $this->address : '';
    }
    
    /**
     * Get career value
     * @return String
     */
    public function getCareer() {
        return isset($this->rCareer) ? $this->rCareer->name : '';
    }
    
    /**
     * Get characteristics value
     * @return String
     */
    public function getCharacteristics() {
        return isset($this->characteristics) ? $this->characteristics : '';
    }
    
    /**
     * Get record number value
     * @return String
     */
    public function getMedicalRecordNumber() {
        return isset($this->rMedicalRecord) ? $this->rMedicalRecord->record_number : '';
    }
    
    /**
     * Get customer ajax info
     * @return string Ajax information data
     */
    public function getCustomerAjaxInfo() {
        $recordNumber = '';
        if (isset($this->rMedicalRecord)) {
            $recordNumber = $this->rMedicalRecord->record_number;
        }
        $rightContent = '<div class="info-result">';
        $rightContent .=    '<div class="title-2">';
        $rightContent .=        DomainConst::CONTENT00173;
        $rightContent .=    '</div>';
        $rightContent .=    '<div class="item-search">';
        $rightContent .=        '<table>';
        $rightContent .=            '<tr>';
        $rightContent .=                '<td>' . DomainConst::CONTENT00100 . ': ' . '<b>' . $this->name . '<b>' . '</td>';
        $rightContent .=                '<td>' . DomainConst::CONTENT00101 . ': ' . '<b>' . $this->getBirthday() . '<b>' . '</td>';
        $rightContent .=            '</tr>';
        $rightContent .=            '<tr>';
        $rightContent .=                '<td>' . DomainConst::CONTENT00170 . ': ' . '<b>' . $this->getPhone() . '<b>' . '</td>';
        $rightContent .=                '<td>' . DomainConst::CONTENT00047 . ': ' . '<b>' . CommonProcess::getGender()[$this->gender] . '<b>' . '</td>';
        $rightContent .=            '</tr>';
        $rightContent .=            '<tr>';
        $rightContent .=                '<td colspan="2">' . DomainConst::CONTENT00045 . ': ' . '<b>' . $this->getAddress() . '<b>' . '</td>';
        $rightContent .=            '</tr>';
        $rightContent .=            '<tr>';
        $rightContent .=                '<td>' . '<b>' . $this->getAgentName() . '<b>' . '</td>';
        $rightContent .=                '<td>' . DomainConst::CONTENT00136 . ': ' . '<b>' . $recordNumber . '<b>' . '</td>';
        $rightContent .=            '</tr>';
        $rightContent .=            '<tr>';
        $rightContent .=                '<td style="width: 50%;">';
        $rightContent .=                    HtmlHandler::createButtonWithImage(CommonProcess::generateQRCodeURL($this->id),
                                            DomainConst::CONTENT00011,
                                            DomainConst::IMG_VIEW_ICON, false);
        $rightContent .=                '</td>';
        $rightContent .=                '<td style="width: 50%;">';
        $rightContent .=                    HtmlHandler::createButtonWithImage(
                                            Yii::app()->createAbsoluteUrl(
                                                        "admin/customers/update", array("id" => $this->id)),
                                            DomainConst::CONTENT00346,
                                            DomainConst::IMG_EDIT_ICON, false);
        $rightContent .=                '</td>';
        $rightContent .=            '</tr>';
        $rightContent .=                '<tr><td>';
        $rightContent .=                    HtmlHandler::createAjaxButtonWithImage(
                '<br>' . DomainConst::CONTENT00264, DomainConst::IMG_PRINT_ALL_ICON,
                '{createPrintDialog(); $(\'#dialogPrintReceipt\').dialog(\'open\');}',
                'cursor: pointer;');
        $rightContent .=                '</td></tr>';
        $pathological = '';
        if (isset($this->rMedicalRecord)) {
            $pathological = $this->rMedicalRecord->generateMedicalHistory(", ");
            Settings::saveAjaxTempValue($this->rMedicalRecord->id);
        }
        if (!empty($pathological)) {
            $rightContent .=        '<tr>';
            $rightContent .=            '<td colspan="2">' . DomainConst::CONTENT00137 . ': ' . '<b>' . $pathological . '<b>' . '</td>';
            $rightContent .=        '</tr>';
        }                
        $rightContent .=        '</table>';
        $rightContent .=    '</div>';
        $rightContent .=    '<div class="title-2">' . DomainConst::CONTENT00201 . '</div>';
        $rightContent .=    '<div class="item-search">';                
        if (isset($this->rMedicalRecord) && isset($this->rMedicalRecord->rTreatmentSchedule)) {
            $i = count($this->rMedicalRecord->rTreatmentSchedule);
            foreach ($this->rMedicalRecord->rTreatmentSchedule as $schedule) {
                if (isset($schedule->rDetail)) {
                    $rightContent .= '<b>';
                    if ($schedule->rPathological) {
                        $rightContent .= '<p>Đợt ' . $i . ': ' . $schedule->getStartTime() . ' - ' . $schedule->rPathological->name . '</p>';
                    } else {
                        $rightContent .= '<p>Đợt ' . $i . ': ' . $schedule->getStartTime() . '</p>';
                    }
                    $rightContent .= '</b>';
                    $rightContent .= HtmlHandler::createButtonWithImage(
                                        Yii::app()->createAbsoluteUrl(
                                            "admin/treatmentScheduleDetails/create", array("schedule_id" => $schedule->id)),
                                        DomainConst::CONTENT00367,
                                        DomainConst::IMG_ADD_ICON, false, '');
                    $detailIdx = count($schedule->rDetail);
                    foreach ($schedule->rDetail as $detail) {
                        $btnTitle = $detail->getStartDate() . '<br>';
                        if ($detail->rTreatmentType) {
//                            $btnTitle = 'Lần ' . $detailIdx . ': ' . $detail->rTreatmentType->name;
                            $btnTitle .= $detail->rTreatmentType->name;
                        } else if ($detail->rDiagnosis) {
//                            $btnTitle = 'Lần ' . $detailIdx . ': ' . $detail->rDiagnosis->name;
                            $btnTitle .= $detail->rDiagnosis->name;
                        } else {
//                            $btnTitle = 'Lần ' . $detailIdx . ': ' . DomainConst::CONTENT00177;
                            $btnTitle .= DomainConst::CONTENT00177;
                        }
                        $updateTag = '';
                        switch (Yii::app()->user->role_name) {
                            case Roles::ROLE_ASSISTANT:
                                $updateTag = '<a target="_blank" href="' . Yii::app()->createAbsoluteUrl("admin/treatmentScheduleDetails/updateImageXRay",
                                        array("id" => $detail->id)) . '">' . DomainConst::CONTENT00272 . '</a>';
                                break;
                            case Roles::ROLE_RECEPTIONIST:
                                if ($detail->isCompleted()) {
                                    $updateTag = HtmlHandler::createButtonWithImage(
                                            Yii::app()->createAbsoluteUrl(
                                                        "admin/treatmentScheduleDetails/view", array("id" => $detail->id)),
                                            $btnTitle, DomainConst::IMG_COMPLETED_ICON, false);
                                } else {
                                    $updateTag = HtmlHandler::createButtonWithImage(
                                            Yii::app()->createAbsoluteUrl(
                                                        "admin/treatmentScheduleDetails/update", array("id" => $detail->id)),
                                            $btnTitle, DomainConst::IMG_NEW_ICON, false);
                                }
                            default:
                                break;
                        }
                        
//                        if ($detail->rDiagnosis) {
//                            $rightContent .= '<p>- Lần ' . $detailIdx . ': ' . $detail->rDiagnosis->name . ' - [' . $updateTag . ']</p>';
//                        } else {
//                            $rightContent .= '<p>- Lần ' . $detailIdx . ': ' . DomainConst::CONTENT00177 . ' - [' . $updateTag . ']</p>';
//                        }
                        $rightContent .= $updateTag;
//                        $rightContent .= HtmlHandler::createAjaxButtonWithImage(
//                                            DomainConst::CONTENT00379, DomainConst::IMG_PRESCRIPTION_ICON,
//                                            '{createPrescriptionDialog(); $(\'#dialogPrintReceipt\').dialog(\'open\');}',
//                                            'cursor: pointer;');
                        $detailIdx--;
                    }
                }
                $i--;
            }
        }
        $rightContent .=    '</div>';
        $rightContent .= '</div>';
        return $rightContent;
    }
    
    /**
     * Get active schedule time
     * @return string Schedule time
     */
    public function getScheduleTime() {
        $scheduleId = $this->getSchedule(false);
        if (!empty($scheduleId)) {
            $mSchedule = TreatmentScheduleDetails::model()->findByPk($scheduleId);
            if ($mSchedule) {
                return $mSchedule->getTimer();
            }
        }
        return '';
    }
    
    /**
     * Get active schedule doctor
     * @return string Doctor name
     */
    public function getScheduleDoctor() {
        $scheduleId = $this->getSchedule(false);
        if (!empty($scheduleId)) {
            $mSchedule = TreatmentScheduleDetails::model()->findByPk($scheduleId);
            if ($mSchedule) {
                return $mSchedule->getDoctor();
            }
        }
        return '';
    }
    
    /**
     * Get customer ajax schedule information
     * @return string
     */
    public function getCustomerAjaxScheduleInfo() {
        $infoSchedule = '';
        $scheduleId = $this->getSchedule();

        $infoSchedule .= HtmlHandler::createAjaxButtonWithImage(
                DomainConst::CONTENT00179, DomainConst::IMG_APPOINTMENT_ICON,
                '{createSchedule(); $(\'#dialogUpdateSchedule\').dialog(\'open\');}',
                'cursor: pointer;');
//        $infoSchedule .= '<div class="group-btn">';
//        $infoSchedule .=    '<a style="cursor: pointer;"'
//                        . ' onclick="{createSchedule(); $(\'#dialogUpdateSchedule\').dialog(\'open\');}">' . DomainConst::CONTENT00179 . '</a>';
//        $infoSchedule .= '</div>';
        if (!empty($scheduleId)) {
            Settings::saveAjaxTempValue($scheduleId);
            $mSchedule = TreatmentScheduleDetails::model()->findByPk($scheduleId);
            if ($mSchedule) {
                $infoSchedule = '<div class="title-2">' . DomainConst::CONTENT00177 . ': </div>';
                $infoSchedule .= '<div class="item-search">';
//                $infoSchedule .=    '<p>' . $mSchedule->start_date . '</p>';
                $infoSchedule .=    '<p>' . $mSchedule->getStartTime() . '</p>';
                $infoSchedule .=    '<p>' . DomainConst::CONTENT00260 . ': ' . $mSchedule->rSchedule->getInsurrance() . '</p>';
                $infoSchedule .=    '<p>Chi Tiết Công Việc: ' . $mSchedule->description . '</p>';
                $infoSchedule .=    '<p>Bác sĩ: ' . $mSchedule->getDoctor() . '</p>';
                $infoSchedule .= '</div>';
                $infoSchedule .= HtmlHandler::createAjaxButtonWithImage(
                        DomainConst::CONTENT00346, DomainConst::IMG_EDIT_ICON,
                        '{updateSchedule(); $(\'#dialogUpdateSchedule\').dialog(\'open\');}',
                        'cursor: pointer;');
            }
        }
        return $infoSchedule;
    }
    
    /**
     * Get social network information
     * @return String
     */
    public function getSocialNetworkInfo() {
        $retVal = array();
//        $retVal[] = "Điện thoại: " . $this->getPhone();
        if (isset($this->rSocialNetwork)) {
            foreach ($this->rSocialNetwork as $value) {
                $retVal[] = SocialNetworks::TYPE_NETWORKS[$value->type_network] . ": $value->value";
            }
        }
        return implode('<br>', $retVal);
    }
    
    /**
     * Get social network value
     * @param Int $type_network Network type
     * @return String
     */
    public function getSocialNetwork($type_network) {
        if (isset($this->rSocialNetwork)) {
            foreach ($this->rSocialNetwork as $value) {
                if ($value->type_network == $type_network) {
                    return $value->value;
                }
            }
        }
        return '';
    }
    
    /**
     * Get medical history html
     * @return type
     */
    public function getMedicalHistoryHtml() {
        $retVal = array();
        if (isset($this->rMedicalRecord)) {
            foreach ($this->rMedicalRecord->rJoinPathological as $item) {
                if (isset($item->rPathological)) {
                    $retVal[] = CommonProcess::createConfigJson(
                            $item->rPathological->id,
                            $item->rPathological->name);
                }
            }
        }
        return $retVal;
    }
    
    /**
     * Get list of customer's treatment schedule
     * @return Array List of customer's treatment schedule
     */
    public function getListTreatmentSchedule() {
        $retVal = array();
        if (isset($this->rMedicalRecord) && isset($this->rMedicalRecord->rTreatmentSchedule)) {
            foreach ($this->rMedicalRecord->rTreatmentSchedule as $schedule) {
//                if (isset($schedule->rDetail)) {
//                    foreach ($schedule->rDetail as $detail) {
//                        $retVal[] = $detail;
//                    }
//                }
                $retVal[] = $schedule;
            }
        }
        return $retVal;
    }
    
    /**
     * Get debt information
     * @return String
     */
    public function getDebt() {
        return CommonProcess::formatCurrency($this->debt) . ' ' . DomainConst::CONTENT00134;
    }
    
    /**
     * Get list receipt of this customer
     * @return array
     */
    public function getReceipts() {
        $retVal = array();
        
        if (isset($this->rMedicalRecord) && isset($this->rMedicalRecord->rTreatmentSchedule)) {
            // Loop for all treatment schedules
            foreach ($this->rMedicalRecord->rTreatmentSchedule as $treatmentSchedule) {
                if (isset($treatmentSchedule->rDetail)) {
                    // Loop for all treatment schedule details
                    foreach ($treatmentSchedule->rDetail as $detail) {
                        $receipt = $detail->rReceipt;
                        if (isset($receipt) && $receipt->status != Receipts::STATUS_INACTIVE) {
                            $retVal[] = $receipt;
                        }
                    }
                }
            }
        }
        
        return $retVal;
    }

    //-----------------------------------------------------
    // JSON methods
    //-----------------------------------------------------
    /**
     * Get medical history
     * @return String
     *  [
     *      {
     *          id:"1",
     *          name:"Tiểu đường",
     *      },
     *      {
     *          id:"2",
     *          name:"Chảy máu răng",
     *      }
     *      {
     *          id:"3",
     *          name:"Đang mang thai",
     *      },
     *  ]
     */
    public function getMedicalHistory() {
        $retVal = array();
        if (isset($this->rMedicalRecord)) {
            foreach ($this->rMedicalRecord->rJoinPathological as $item) {
                if (isset($item->rPathological)) {
                    $retVal[] = CommonProcess::createConfigJson(
                            $item->rPathological->id,
                            $item->rPathological->name);
                }
            }
        }
        return $retVal;
    }
    
    /**
     * Get 3 of last record TreatmentSchedules
     * @return String
     *  [
     *      {
     *          id:"1",
     *          name:"Chảy máu răng",
     *          data:{,
     *              start_date:"01/12/2017",
     *              end_date:"02/12/2017",
     *              diagnosis:"Tật không răng một phần"
     *          }
     *      },
     *      {
     *          id:"2",
     *          name:"Chảy máu răng",
     *          data:{,
     *              start_date:"01/12/2017",
     *              end_date:"02/12/2017",
     *              diagnosis:"Tật không răng một phần"
     *          }
     *      }
     *      {
     *          id:"3",
     *          name:"Đang mang thai",
     *          data:{,
     *              start_date:"01/12/2017",
     *              end_date:"02/12/2017",
     *              diagnosis:"Tật không răng một phần"
     *          }
     *      },
     *  ]
     */
    public function getTreatmentHistory() {
        $retVal = array();
        $count = 0;
        if (isset($this->rMedicalRecord)) {
            $mMedicalRecord = $this->rMedicalRecord;
            if (isset($mMedicalRecord->rTreatmentSchedule)) {
                foreach ($mMedicalRecord->rTreatmentSchedule as $schedule) {
//                    $pathological = isset($schedule->rPathological) ? $schedule->rPathological->name : '';
//                    $diagnosis = isset($schedule->rDiagnosis) ? $schedule->rDiagnosis->name : '';
//                    $retVal[] = CommonProcess::createConfigJson(
//                            $schedule->id,
//                            $pathological,
//                            array(
//                                DomainConst::KEY_START_DATE => CommonProcess::convertDateTimeWithFormat(
//                                        $schedule->start_date),
//                                DomainConst::KEY_END_DATE => CommonProcess::convertDateTimeWithFormat(
//                                        $schedule->end_date),
//                                DomainConst::KEY_DIAGNOSIS => $diagnosis,
//                            ));
                    $retVal[] = $schedule->getJsonTreatmentInfo();
                    $count++;
                    if ($count >= 3) {
                        $retVal[] = CommonProcess::createConfigJson(
                                CustomerController::ITEM_UPDATE_DATE,
                                DomainConst::CONTENT00230);
                        return $retVal;
                    }
                }
            }
        }
        $retVal[] = CommonProcess::createConfigJson(
                CustomerController::ITEM_UPDATE_DATE,
                DomainConst::CONTENT00230);
        
        return $retVal;
    }
    
    /**
     * List api return
     * @param Array $root Root value
     * @param Obj $mUser User object
     * @return CActiveDataProvider
     */
    public function apiList($root, $mUser) {
        $criteria = new CDbCriteria();
        $criteria->compare('t.status', DomainConst::DEFAULT_STATUS_ACTIVE);
//        $criteria->order = 't.id DESC';
        $criteria->order = 't.created_date DESC';
        // Set condition
        $roleName = isset($mUser->rRole) ? $mUser->rRole->role_name : '';
        switch ($roleName) {
            case Roles::ROLE_DOCTOR:
                $today = CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_6);
                $lastMonth = CommonProcess::getPreviousMonth(DomainConst::DATE_FORMAT_6);
                $from = isset($root->date_from) ? $root->date_from : $lastMonth;
                $to = isset($root->date_to) ? $root->date_to : $today;
                Loggers::info("From: $from", __FUNCTION__, __LINE__);
                Loggers::info("From: $to", __FUNCTION__, __LINE__);
                if ($mUser->isTestUser()) {
                    $from = '2018/01/01';
                    $to = '2018/12/01';
                }
                // Get list customers assign at doctor
//                $criteria->addInCondition('t.id', $mUser->getListCustomerOfDoctor($from, $to));
                return $mUser->getListCustomerOfDoctor($from, $to);
//                return TreatmentScheduleDetails::getListCustomerByDoctor($mUser->id, $from, $to);
//                break;

            default:
                break;
        }
        
        // Get return value
        $retVal = new CActiveDataProvider(
                $this,
                array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => Settings::getApiListPageSize(),
                        'currentPage' => (int)$root->page,
                    ),
                ));
        return $retVal;
    }
}