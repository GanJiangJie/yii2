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
     * @return array
     * @throws Exception
     */
    public function actionList(): array
    {
        Validator::checkValidEmpty(request()->params(), ['token']);
        $model = new MemberService();
        $model->assignAttributes(request()->params(), ['account', 'key_word']);
        $model->merchant_code = mToken()->get('merchant_code');
        $model->type = request()->params('type', MC::TYPE_MEMBER);
        $model->page = request()->params('page', 1);
        $model->limit = request()->params('limit', 0);
        return $model->getList();
    }

    /**
     * @throws Exception
     */
    public function actionRegister()
    {
        Validator::checkValidEmpty(request()->params(), [
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
     * @throws Exception
     */
    public function actionEdit()
    {
        $model = new MemberService();
        $model->assignAttributes(request()->params(), ['member_code']);
        $model->edit();
    }
}