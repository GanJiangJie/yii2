<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "qx_member".
 *
 * @property integer $id
 * @property string $platform_code
 * @property string $merchant_code
 * @property string $platform_member_code
 * @property string $member_code
 * @property integer $type
 * @property integer $member_level_id
 * @property string $member_level_code
 * @property string $unionid
 * @property string $avatar
 * @property string $nickname
 * @property string $nick_name
 * @property string $user_name
 * @property string $member_name
 * @property string $account
 * @property string $pwd
 * @property integer $bonus
 * @property integer $balance
 * @property integer $growth_value
 * @property integer $refund_growth_value
 * @property string $card_id
 * @property string $member_card_code
 * @property string $birthday
 * @property integer $sex
 * @property string $educational
 * @property string $industry
 * @property string $income
 * @property string $interest
 * @property string $address
 * @property string $email
 * @property string $ID_number
 * @property string $occupation
 * @property integer $regist_source
 * @property string $regist_customer_time
 * @property string $regist_member_time
 * @property string $regist_channel_code
 * @property string $regist_store_code
 * @property string $login_time
 * @property string $token
 * @property integer $is_modify_birthday
 * @property string $province_code
 * @property string $province
 * @property string $city_code
 * @property string $city
 * @property string $region_code
 * @property string $region
 * @property string $lng
 * @property string $lat
 * @property integer $qx_member_id
 * @property string $create_time
 * @property string $last_time
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qx_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'member_level_id', 'bonus', 'balance', 'growth_value', 'refund_growth_value', 'sex', 'regist_source', 'is_modify_birthday', 'qx_member_id'], 'integer'],
            [['birthday', 'regist_customer_time', 'regist_member_time', 'login_time', 'create_time', 'last_time'], 'safe'],
            [['lng', 'lat'], 'number'],
            [['platform_code', 'merchant_code', 'platform_member_code', 'member_code', 'member_level_code', 'regist_channel_code', 'regist_store_code', 'token', 'province_code', 'city_code', 'region_code'], 'string', 'max' => 32],
            [['unionid', 'nickname', 'nick_name', 'user_name', 'member_name', 'account', 'pwd', 'card_id', 'member_card_code', 'educational', 'industry', 'income', 'interest', 'email', 'ID_number', 'occupation', 'province', 'city', 'region'], 'string', 'max' => 64],
            [['avatar'], 'string', 'max' => 1000],
            [['address'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'platform_code' => 'Platform Code',
            'merchant_code' => 'Merchant Code',
            'platform_member_code' => 'Platform Member Code',
            'member_code' => 'Member Code',
            'type' => 'Type',
            'member_level_id' => 'Member Level ID',
            'member_level_code' => 'Member Level Code',
            'unionid' => 'Unionid',
            'avatar' => 'Avatar',
            'nickname' => 'Nickname',
            'nick_name' => 'Nick Name',
            'user_name' => 'User Name',
            'member_name' => 'Member Name',
            'account' => 'Account',
            'pwd' => 'Pwd',
            'bonus' => 'Bonus',
            'balance' => 'Balance',
            'growth_value' => 'Growth Value',
            'refund_growth_value' => 'Refund Growth Value',
            'card_id' => 'Card ID',
            'member_card_code' => 'Member Card Code',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'educational' => 'Educational',
            'industry' => 'Industry',
            'income' => 'Income',
            'interest' => 'Interest',
            'address' => 'Address',
            'email' => 'Email',
            'ID_number' => 'Id Number',
            'occupation' => 'Occupation',
            'regist_source' => 'Regist Source',
            'regist_customer_time' => 'Regist Customer Time',
            'regist_member_time' => 'Regist Member Time',
            'regist_channel_code' => 'Regist Channel Code',
            'regist_store_code' => 'Regist Store Code',
            'login_time' => 'Login Time',
            'token' => 'MToken',
            'is_modify_birthday' => 'Is Modify Birthday',
            'province_code' => 'Province Code',
            'province' => 'Province',
            'city_code' => 'City Code',
            'city' => 'City',
            'region_code' => 'Region Code',
            'region' => 'Region',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'qx_member_id' => 'Qx Member ID',
            'create_time' => 'Create Time',
            'last_time' => 'Last Time',
        ];
    }
}
