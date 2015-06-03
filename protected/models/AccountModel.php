<?php

/**
 * This is the model class for table "s_account".
 *
 * The followings are the available columns in table 's_account':
 * @property integer $id
 * @property string $name
 * @property string $pswd
 * @property string $user_id
 * @property string $type
 * @property string $template
 * @property string $class
 */
class AccountModel extends CActiveRecord
{
    const TYPE_SMS = 'sms';
    const TYPE_EMAIL = 'email';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 's_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>15),
			array('pswd', 'length', 'max'=>45),
			array('user_id', 'length', 'max'=>10),
			array('type', 'length', 'max'=>5),
			array('template', 'length', 'max'=>255),
			array('class', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, pswd, user_id, type, template, class', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'pswd' => 'Pswd',
			'user_id' => 'User',
			'type' => 'Type',
			'template' => '模板[尾巴]',
			'class' => '发�?类名',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pswd',$this->pswd,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('class',$this->class,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
