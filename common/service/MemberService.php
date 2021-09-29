<?php

namespace app\common\service;

use app\common\event\model\MemberRegisterEvent;
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
                ':merchant_code' => tokenGet('merchant_code'),
                ':type' => requestParams('type', MEMBER_TYPE_MEMBER)
            ])
            ->filterWhere(['account' => requestParams('account')]);

        if ($key_word = requestParams('key_word')) {
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
                ':merchant_code' => requestParams('merchant_code'),
                ':account' => requestParams('account')
            ])
            ->exists() and tbe('会员已存在');

        $member = new Member();
        $member->member_code = createCode($member, 'member_code');
        $member->merchant_code = requestParams('merchant_code');
        $member->member_name = requestParams('name');
        $member->account = requestParams('account');
        $member->birthday = requestParams('birthday');
        if ($sex = requestParams('sex')) $member->sex = $sex;
        $member->save() or tbe(json_encode($member->getErrors()), API_ERROR_CODE_SYSTEM_ERROR);

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
            ->where('member_code = :member_code', [':member_code' => tokenGet('member_code')])
            ->one();
        empty($member) and tbe('会员不存在', API_ERROR_CODE_NO_DATA);

        if ($member_name = requestParams('member_name')) $member->member_name = $member_name;
        if ($birthday = requestParams('birthday')) $member->birthday = $birthday;

        $member->save() or tbe(json_encode($member->getErrors()), API_ERROR_CODE_SYSTEM_ERROR);
    }
}