<?php

namespace app\common\constant;

class Constant
{
    //基础常量
    const API_ERROR_CODE_SUCCESS = 10000;
    const API_ERROR_CODE_FAIL = 10001;
    const API_ERROR_CODE_LACK_PARAMS = 10002;
    const API_ERROR_CODE_INVALID_SIGN_TYPE = 10003;
    const API_ERROR_CODE_INVALID_APP_ID = 10004;
    const API_ERROR_CODE_INVALID_VERSION = 10005;
    const API_ERROR_CODE_INVALID_METHOD = 10006;
    const API_ERROR_CODE_INVALID_SIGN = 10007;
    const API_ERROR_CODE_LACK_TOKEN = 10008;
    const API_ERROR_CODE_INVALID_TOKEN = 10009;
    const API_ERROR_CODE_INVALID_PARAMS = 10010;
    const API_ERROR_CODE_SYSTEM_ERROR = 10011;
    const API_ERROR_CODE_NOT_AUTH = 10012;
    const API_ERROR_CODE_NO_DATA = 10013;
    const __API_ERROR_CODE = [
        self::API_ERROR_CODE_SUCCESS => 'SUCCESS',
        self::API_ERROR_CODE_FAIL => 'FAIL',
        self::API_ERROR_CODE_LACK_PARAMS => '缺少必要参数',
        self::API_ERROR_CODE_INVALID_SIGN_TYPE => '无效签名类型',
        self::API_ERROR_CODE_INVALID_APP_ID => '无效app_id',
        self::API_ERROR_CODE_INVALID_VERSION => '无效的版本号',
        self::API_ERROR_CODE_INVALID_METHOD => '无效的方法名',
        self::API_ERROR_CODE_INVALID_SIGN => '无效签名',
        self::API_ERROR_CODE_LACK_TOKEN => '登录令牌必传',
        self::API_ERROR_CODE_INVALID_TOKEN => '令牌已经失效',
        self::API_ERROR_CODE_INVALID_PARAMS => '无效参数',
        self::API_ERROR_CODE_SYSTEM_ERROR => '系统错误',
        self::API_ERROR_CODE_NOT_AUTH => '无权限访问',
        self::API_ERROR_CODE_NO_DATA => '未查询到数据',
    ];

    //是否常量
    const YES = 1;
    const NO = 0;
}