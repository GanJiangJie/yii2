<?php

namespace app\modules\crm\controllers;

use app\common\service\MemberService;
use common\util\DataCheck\Validator;
use app\components\WebController;
use yii\base\Exception;

class MemberController extends WebController
{
    /**
     * 会员列表
     * @return array
     * @throws Exception
     */
    public function actionList(): array
    {
        mTokenGet();
        $model = new MemberService();
        $model->page = rParams('page', 1);
        $model->limit = rParams('limit', 0);
        return $model->getList();
    }

    /**
     * 注册会员
     * @throws Exception
     */
    public function actionRegister()
    {
        Validator::notEmpty(rParams(), ['merchant_code', 'name', 'account', 'birthday']);
        $model = new MemberService();
        $model->register();
    }

    /**
     * 编辑会员
     * @throws Exception
     */
    public function actionEdit()
    {
        uTokenGet();
        $model = new MemberService();
        $model->edit();
    }
}