<?php

namespace app\modules\crm\controllers;

use app\common\service\MemberService;
use common\util\DataCheck\Validator;
use app\components\WebController;
use app\common\constant\MemberC as MC;
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
        Validator::notEmpty(rParams(), ['token']);
        $model = new MemberService();
        $model->assignAttrs(rParams(), ['account', 'key_word']);
        $model->merchant_code = mTokenGet('merchant_code');
        $model->type = rParams('type', MC::TYPE_MEMBER);
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
        Validator::notEmpty(rParams(), ['name', 'account', 'birthday']);
        $model = new MemberService();
        $model->assignAttrs(rParams(), ['account', 'birthday']);
        $model->merchant_code = mTokenGet('merchant_code');
        $model->member_name = rParams('name');
        $model->register();
    }

    /**
     * 编辑会员
     * @throws Exception
     */
    public function actionEdit()
    {
        $model = new MemberService();
        $model->assignAttrs(rParams(), ['member_code']);
        $model->edit();
    }
}