<?php

/**
 * Worker for ClassWorker
 */
class Worker_DailySms
{
    public function setUp()
    {
        # Set up environment for this job
       echo "Set up\n";
    }

    public function perform()
    {
        # Run task
        $jobId= $this->job->payload['id'];
        $phone = $this->args['phone'];
        $accountId = $this->args['accountId'];
        $accountInfo = AccountModel::model()->findByPk($accountId);
        $content = $this->args['content'] . $accountInfo->template;
        $logModel = new SmsLogModel();
        $logModel->phone = $phone;
        $logModel->accountId = $accountId;
        $logModel->type = SmsLogModel::TYPE_DAILY;
        $logModel->content = $content;
        $logModel->queue_id = $jobId;
        if (class_exists($accountInfo->class)) {
            $class = new $accountInfo->class;
            $result = $class->sendOneSms($phone,$content , $accountInfo->name, $accountInfo->pswd, $accountInfo->user_id);
            if ($result['success']) {
                $logModel->status = 1;
            } else {
                $logModel->status = -1;
                $logModel->errorMsg = $result['msg'];
            }
        }else{
            $logModel->status = -1;
            $logModel->errorMsg = 'utils class not exit';
        }
        $logModel->save();
     echo "Run".$jobId."\n";
       // echo "Run\n";
    }

    public function tearDown()
    {
        # Remove environment for this job
        echo "Tear down\n";
    }
}