<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ApiController extends Controller
{
    protected $result = array('success'=>false,'msg'=>'group not exit');
    protected $groupInfo;

    public function beforeAction($action)
    {
        $group = Yii::app()->request->params['group'];
        $groupInfo = GroupModel::model()->find('group=:group and status=1', array(':group' => $group));
        if (!$groupInfo)
            die(CJSON::encode($this->result));
        $this->groupInfo = $groupInfo;
        return parent::beforeAction($action);
    }
}