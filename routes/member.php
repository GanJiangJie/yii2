<?php

route()->before([
    //'auth',
    'mThrottle' . config('params.throttle.api.common')
], [
    'crm.member.list' => 'crm/member/list',
    'crm.member.edit' => 'crm/member/edit',
])->before([
    'uThrottle' . config('params.throttle.member_register'),
], [
    'crm.member.register' => 'crm/member/register',
]);

return [
];