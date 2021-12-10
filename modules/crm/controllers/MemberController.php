<?php

namespace app\modules\crm\controllers;

use app\common\service\MemberService;
use common\util\DataCheck\Validator;
use app\components\WebController;
use app\components\Exception;

class MemberController extends WebController
{
    /**
     * 会员列表
     * @return array
     */
    public function actionList(): array
    {
        $model = new MemberService();
        $model->page = params('page', 1);
        $model->limit = params('limit', 0);
        return $model->getList();
    }

    /**
     * 注册会员
     * @throws Exception
     */
    public function actionRegister()
    {
        Validator::notEmpty(request()->params, ['merchant_code', 'name', 'account', 'birthday']);
        $model = new MemberService();
        $model->register();
    }

    /**
     * 编辑会员
     * @throws Exception
     */
    public function actionEdit()
    {
        $model = new MemberService();
        $model->edit();
    }
}