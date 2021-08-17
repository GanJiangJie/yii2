<?php

namespace app\modules\crm\controllers;

use app\common\service\MemberService;
use app\components\WebController;
use common\util\DataCheck\DataCheckBase;

class MemberController extends WebController
{
    /**
     * @throws \yii\base\Exception
     */
    public function actionList()
    {
        DataCheckBase::checkValidEmpty(request()->params(), ['token']);
        $model = new MemberService(request()->params(), ['limit']);
        $model->loadParams(mToken()->getToken(), ['merchant_code']);
        $model->type = 2;
        $model->page = request()->params('page', 1);
        $model->limit = request()->params('limit', 0);
        return $model->getList();
    }
}