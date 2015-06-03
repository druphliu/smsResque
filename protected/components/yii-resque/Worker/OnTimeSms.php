<?php

/**
 * Worker for ClassWorker
 */
class Worker_OnTimeSms
{
    public function setUp()
    {
        # Set up environment for this job
        echo "Set up\n";
    }

    public function perform()
    {
        # Run task
        $logId = $this->args['id'];
        $logModel = SmsLogModel::model()->findByPk($logId);
        $accountId = $logModel->accountId;
        $accountInfo = AccountModel::model()->findByPk($accountId);
        if (class_exists($accountInfo->class)) {
            $class = new $accountInfo->class;
            $result = $class->sendOneSms($logModel->phone, $logModel->content . $accountInfo->template, $accountInfo->name, $accountInfo->pswd, $accountInfo->user_id);
            if ($result['success']) {
                $logModel->status = 1;
            } else {
                $logModel->status = -1;
                $logModel->errorMsg = $result['msg'];
            }
            $logModel->save();
        }
        echo "Run\n";
    }

    public function tearDown()
    {
        # Remove environment for this job
        echo "Tear down\n";
    }
}