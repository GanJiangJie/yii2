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
        Validator::checkValidEmpty(request()->param(), ['token']);
        $model = new MemberService();
        $model->assignAttributes(request()->param(), ['account', 'key_word']);
        $model->merchant_code = mToken()->get('merchant_code');
        $model->type = request()->param('type', MC::TYPE_MEMBER);
        $model->page = request()->param('page', 1);
        $model->limit = request()->param('limit', 0);
        return $model->getList();
    }

    /**
     * @throws Exception
     */
    public function actionRegister()
    {
        Validator::checkValidEmpty(request()->param(), [
            'name',
            'account',
            'birthday'
        ]);
        $model = new MemberService();
        $model->assignAttributes(request()->param(), ['account', 'birthday']);
        $model->merchant_code = mToken()->get('merchant_code');
        $model->member_name = request()->param('name');
        $model->register();
    }

    /**
     * @throws Exception
     */
    public function actionEdit()
    {
        $model = new MemberService();
        $model->assignAttributes(request()->param(), ['member_code']);
        $model->edit();
    }
}