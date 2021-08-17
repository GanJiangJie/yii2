<?php

namespace app\commands;

use app\common\event\model\DefaultEvent;
use app\components\ConsoleController;
use app\models\Member;

class TestController extends ConsoleController
{
    public function actionIndex()
    {
        /**
         * @var Member $member
         */
        $member = Member::find()->where('account = :account', [':account' => '13554157913'])->one();
        $member->member_name = 'JieG';
        $member->birthday = '2000-01-01';
        $res = $member->save();
        dd($res, $member->toArray());
        $res = event(new DefaultEvent(['merchant_code' => '123', 'store_code' => '456']));
        var_dump($res);
    }
}