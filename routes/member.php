<?php

route()->before([
    'auth',
], [
    'crm.member.list' => 'crm/member/list',
    'crm.member.edit' => 'crm/member/edit',
]);

return [
    'crm.member.register' => 'crm/member/register',
];