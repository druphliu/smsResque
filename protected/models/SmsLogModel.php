<?php

/**
 * This is the model class for table "s_sms_log".
 *
 * The followings are the available columns in table 's_sms_log':
 * @property integer $id
 * @property string $phone
 * @property string $content
 * @property string $create_at
 * @property string $finished_at
 * @property integer $status
 * @property integer $accountId
 * @property string $queue_id
 * @property integer $type
 * @property string $errorMsg
 */
class SmsLogModel extends CActiveRecord
{
    const TYPE_ON_TIME = 0;
    const TYPE_DAILY = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 's_sms_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone, content, type', 'required'),
			array('status, accountId, type', 'numerical', 'integerOnly'=>true),
			array('phone', 'length', 'max'=>11),
			array('content', 'length', 'max'=>255),
			array('queue_id', 'length', 'max'=>32),
			array('errorMsg', 'length', 'max'=>10),
			array('finished_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, phone, content, create_at, finished_at, status, accountId, queue_id, type, errorMsg', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => 'Phone',
			'content' => 'Content',
			'create_at' => 'Create At',
			'finished_at' => 'Finished At',
			'status' => '发送状态0:未发送1:成功;-1:失败',
			'accountId' => '使用的账号ID',
			'queue_id' => 'Queue',
			'type' => '类型:0即时,1:非即时',
			'errorMsg' => 'Error Msg',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('finished_at',$this->finished_at,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('accountId',$this->accountId);
		$criteria->compare('queue_id',$this->queue_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('errorMsg',$this->errorMsg,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SmsLogModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
