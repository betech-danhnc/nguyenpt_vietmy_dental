<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property string $id
 * @property string $content
 * @property integer $status
 * @property string $created_date
 * @property string $created_by
 */
class News extends BaseActiveRecord
{
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return News the static model class
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
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
                        array('content, status, description', 'safe'),
			array('id, content, status, created_date, created_by, description', 'safe', 'on'=>'search'),
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
                    'rCreated' => array(self::BELONGS_TO, 'Users', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => 'Nội dung',
			'status' => 'Trạng thái',
			'created_date' => 'Ngày tạo',
			'created_by' => 'Người tạo',
			'description' => 'Mô tả',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_date',$this->created_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
         * get field of table
         * @param string $field
         * @return string
         */
        public function getField($field = 'id'){
            return !empty($this->$field) ? $this->$field : '';
        }
        
        /**
         * get array status
         * @return array
         */
        public function getArrayStatus(){
            return [
                self::STATUS_ACTIVE => 'Hoạt động',
                self::STATUS_INACTIVE => 'Không hoạt động'
            ];
        }
        
        /**
         * get status
         * @return string
         */
        public function getStatus(){
            $aStatus = $this->getArrayStatus();
            return !empty($aStatus[$this->status]) ? $aStatus[$this->status] : '';
        }
        
        /**
         * get full name of create by
         * @return string
         */
        public function getCreatedBy(){
            $mCreatedBy = $this->rCreated;
            return !empty($mCreatedBy) ? $mCreatedBy->getFullName() : '';
        }
        
        /**
         * get created date
         * @return date
         */
        public function getCreatedDate(){
            return CommonProcess::convertDateTime($this->created_date,DomainConst::DATE_FORMAT_1,DomainConst::DATE_FORMAT_11);
        }
        
        /**
         * handle before save
         */
        public function handleBeforeSave(){
            $this->created_by = Yii::app()->user->id;
        }
        
        /**
         * get array model news by status
         * @param int $status
         * @return array model
         */
        public function getArrayNews($status = News::STATUS_ACTIVE){
            $aNews = [];
            $criteria = new CDbCriteria;
            $criteria->compare('status', $status);
            $criteria->order = 't.id DESC';
            $aNews = News::model()->findAll($criteria);
            return $aNews;
        }
}