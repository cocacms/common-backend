<?php

return [
    /**
     * 小程序APPID
     */
    'appid' => 'wx22b167f872d0e478',
    /**
     * 小程序Secret
     */
    'secret' => 'f3177db7ed1bb17c641c5049c86d484b',
    /**
     * 小程序登录凭证 code 获取 session_key 和 openid 地址，不需要改动
     */
    'code2session_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
];

