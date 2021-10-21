<?php

route()->before(['auth',], [
    'crm.member.list' => 'crm/member/list',
    'crm.member.edit' => 'crm/member/edit',
])->before(['uThrottle:10,1,注册次数已达上限，请稍后再试',], [
    'crm.member.register' => 'crm/member/register',
]);

return [
];