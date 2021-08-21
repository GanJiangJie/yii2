<?php

namespace app\modules\crm\controllers;

use app\common\service\MemberService;
use app\components\WebController;
use common\util\DataCheck\DataCheckBase;

class MemberController extends WebController
{
    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionList()
    {
        DataCheckBase::checkValidEmpty(request()->params(), ['token']);
        $model = new MemberService();
        $model->assignAttributes(request()->params(), ['account', 'key_word']);
        $model->merchant_code = mToken()->get('merchant_code');
        $model->type = 2;
        $model->page = request()->params('page', 1);
        $model->limit = request()->params('limit', 0);
        return $model->getList();
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionRegister()
    {
        DataCheckBase::checkValidEmpty(request()->params(), [
            'name',
            'account',
            'birthday'
        ]);
        $model = new MemberService();
        $model->assignAttributes(request()->params(), ['account', 'birthday']);
        $model->merchant_code = mToken()->get('merchant_code');
        $model->member_name = request()->params('name');
        $model->register();
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionEdit()
    {
        $model = new MemberService();
        $model->assignAttributes(request()->params(), ['member_code']);
        $model->edit();
    }
}