<?php

route()->before([
    'auth',
], [
    'crm.member.list' => 'crm/member/list',
])->before([
    'mThrottle' . config('params.throttle.api.common')
], [
    'crm.member.edit' => 'crm/member/edit',
])->before([
    'uThrottle' . config('params.throttle.member_register'),
], [
    'crm.member.register' => 'crm/member/register',
]);

return [
];