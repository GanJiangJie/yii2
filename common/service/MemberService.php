<?php

namespace app\common\service;

use app\common\event\model\MemberRegisterEvent;
use app\common\constant\{
    Constant as C,
    MemberC as MC
};
use app\models\Member;
use yii\base\Exception;

class MemberService extends BaseService
{
    /**
     * @return array
     * @throws Exception
     */
    public function getList(): array
    {
        $members = Member::find()
            ->select([
                'member_code',
                'member_name',
                'nick_name',
                'account',
                'birthday',
                'create_time',
            ])
            ->where('merchant_code = :merchant_code and type = :type', [
                ':merchant_code' => mTokenGet('merchant_code'),
                ':type' => rParams('type', MC::TYPE_MEMBER)
            ])
            ->filterWhere(['account' => rParams('account')]);

        if ($key_word = rParams('key_word')) {
            $members->where('(member_name like :name or nick_name like :name)', [':' => "%$key_word%"]);
        }

        $members->orderBy('id desc');

        self::queryPage($members);

        foreach ($members->each() as $member) {
            $this->list[] = [
                'member_code' => $member['member_code'],
                'member_name' => $member['member_name'],
                'nick_name' => $member['nick_name'],
                'account' => $member['account'],
                'birthday' => $member['birthday'] ?: '',
                'age' => $member['birthday'] ? birthdayToAge($member['birthday']) : '',
                'create_time' => $member['create_time']
            ];
        }

        return self::makePage();
    }

    /**
     * @throws Exception
     */
    public function register()
    {
        Member::find()
            ->where('merchant_code = :merchant_code and account = :account', [
                ':merchant_code' => rParams('merchant_code'),
                ':account' => rParams('account')
            ])
            ->exists() and throwE('会员已存在');

        $member = new Member();
        $member->member_code = createCode($member, 'member_code');
        $member->merchant_code = rParams('merchant_code');
        $member->member_name = rParams('name');
        $member->account = rParams('account');
        $member->birthday = rParams('birthday');
        if ($sex = rParams('sex')) $member->sex = $sex;
        $member->save() or throwE(json_encode($member->getErrors()), C::API_ERROR_CODE_SYSTEM_ERROR);

        //会员注册事件
        event(new MemberRegisterEvent($member->toArray()));
    }

    /**
     * @throws Exception
     */
    public function edit()
    {
        /**
         * @var Member $member
         */
        $member = Member::find()
            ->where('member_code = :member_code', [':member_code' => uTokenGet('member_code')])
            ->one() or throwE('会员不存在', C::API_ERROR_CODE_NO_DATA);

        if ($member_name = rParams('member_name')) $member->member_name = $member_name;
        if ($birthday = rParams('birthday')) $member->birthday = $birthday;

        $member->save() or throwE(json_encode($member->getErrors()), C::API_ERROR_CODE_SYSTEM_ERROR);
    }
}