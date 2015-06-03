<?php

class SmsController extends ApiController
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $sign = Yii::app()->request->getParam('sign');
        $phone = Yii::app()->request->getParam('phone');
        $content = Yii::app()->request->getParam('content');
        $type = Yii::app()->request->getParam('type');
        //check sign
        $secret = $this->groupInfo->secret;
        $string = md5($phone . $content . $secret);
        if ($sign != $string) {
            $this->result['msg'] = 'sign error';
            die(CJSON::encode($this->result));
        }
        //�����
        //�ж����� ��ʱ������� �Ǽ�ʱ���
        //���
        $logModel = new SmsLogModel();
        $logModel->content = $content;
        $logModel->phone = $phone;
        $logModel->accountId = $this->groupInfo->accountId;
        switch ($type) {
            case SmsLogModel::TYPE_ON_TIME:
                $logModel->type = SmsLogModel::TYPE_ON_TIME;
                $accountInfo = AccountModel::model()->findByPk($this->groupInfo->accountId);
                if (class_exists($accountInfo->class)) {
                    $class = new $accountInfo->class;
                    $result = $class->sendOneSms($logModel->phone, $logModel->content . $accountInfo->template, $accountInfo->name, $accountInfo->pswd, $accountInfo->user_id);
                    if ($result['success']) {
                        $logModel->status = 1;
                        $this->result['success'] = true;
                        $this->result['msg'] = '';
                    } else {
                        $logModel->status = -1;
                        $this->result['msg'] = $logModel->errorMsg = $result['msg'];
                    }
                } else {
                    $logModel->status = -1;
                    $this->result['msg'] = $logModel->errorMsg = 'utils class not exit';
                }
                break;
            case SmsLogModel::TYPE_DAILY:
            default:
                $logModel->type = SmsLogModel::TYPE_DAILY;
                $worker = 'Worker_DailySms';
                break;
        }
        $logModel->save();
        if ($type != SmsLogModel::TYPE_ON_TIME) {
            $queueId = Yii::app()->resque->createJob('DailySms', $worker, $args = array('id' => $logModel->id));
            if ($queueId) {
                $logModel->queue_id = $queueId;
                $logModel->save();
                $this->result['success'] = true;
                $this->result['msg'] = '';
                $this->result['queueId'] = $queueId;
            }
        }
        die(CJSON::encode($this->result));
    }

    public function actionStatus()
    {
        $sign = Yii::app()->request->params['sign'];
        $queueId = Yii::app()->request->params['queueId'];
        $string = md5($queueId . $this->groupInfo->secret);
        if ($string != $sign) {
            $this->result['msg'] = 'sign error';
            die(CJSON::encode($this->result));
        }
        $queue = SmsLogModel::model()->find('queue_id=:queue_id', array(':queue_id' => $queueId));
        if (!$queue) {
            $this->result['msg'] = 'queue not exit';
            die(CJSON::encode($this->result));
        }
        $this->result['success'] = true;
        $this->result['status'] = $queue->status;
        $this->result['msg'] = '';
        die(CJSON::encode($this->result));
    }
}