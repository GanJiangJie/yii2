<?php

namespace app\common\service;

use app\common\event\model\MemberRegisterEvent;
use app\components\Exception;
use app\models\Member;

class MemberService extends BaseService
{
    /**
     * @return array
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
                ':type' => params('type', MEMBER_TYPE_MEMBER)
            ])
            ->filterWhere(['account' => params('account')]);

        if ($key_word = params('key_word')) {
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
        if (Member::find()
            ->where('merchant_code = :merchant_code and account = :account', [
                ':merchant_code' => params('merchant_code'),
                ':account' => params('account')
            ])
            ->exists()) {
            throw new Exception('会员已存在');
        }

        $member = new Member();
        $member->member_code = createCode($member, 'member_code');
        $member->merchant_code = params('merchant_code');
        $member->member_name = params('name');
        $member->account = params('account');
        $member->birthday = params('birthday');
        if ($sex = params('sex')) $member->sex = $sex;
        if (!$member->save()) {
            throw new Exception(json_encode($member->getErrors()), API_ERROR_CODE_SYSTEM_ERROR);
        }

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
        if (empty($member)) {
            throw new Exception('会员不存在', API_ERROR_CODE_NO_DATA);
        }

        if ($member_name = params('member_name')) $member->member_name = $member_name;
        if ($birthday = params('birthday')) $member->birthday = $birthday;

        if (!$member->save()) {
            throw new Exception(json_encode($member->getErrors()), API_ERROR_CODE_SYSTEM_ERROR);
        }
    }
}