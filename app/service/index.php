<?php
return [
    //服务提供者
    'providers' => [
        'core\alipay\AlipayProvider',
        'core\cache\CacheProvider',
        'core\code\CodeProvider',
        'core\config\ConfigProvider',
        'core\cookie\CookieProvider',
        'core\db\DBProvider',
        'core\error\ErrorProvider',
        'core\event\EventProvider',
        'core\file\FileProvider',
        'core\hook\HookProvider',
        'core\lang\LangProvider',
        'core\log\LogProvider',
        'core\mail\MailProvider',
        'core\qq\QQProvider',
        'core\rbac\RbacProvider',
        'core\request\RequestProvider',
        'core\response\ResponseProvider',
        'core\route\RouteProvider',
        'core\security\SecurityProvider',
        'core\session\SessionProvider',
        'core\validate\ValidateProvider',
        'core\view\ViewProvider',
        'core\weixin\WeixinProvider',
        /*自定义*/
        'service\form\FormProvider',
        'service\html\HtmlProvider',
        'service\page\PageProvider',
    ],
    //服务外观
    'facades' => [
        'Alipay' => 'core\alipay\AlipayFacade',
        'Cache' => 'core\cache\CacheFacade',
        'Code' => 'core\code\CodeFacade',
        'Config' => 'core\config\ConfigFacade',
        'Cookie' => 'core\cookie\CookieFacade',
        'DB' => 'core\db\DBFacade',
        'Error' => 'core\error\ErrorFacade',
        'Event' => 'core\event\EventFacade',
        'File' => 'core\file\FileFacade',
        'Hook' => 'core\hook\HookFacade',
        'Lang' => 'core\lang\LangFacade',
        'Log' => 'core\log\LogFacade',
        'Mail' => 'core\mail\MailFacade',
        'QQ' => 'core\qq\QQFacade',
        'Rbac' => 'core\rbac\RbacFacade',
        'Request' => 'core\request\RequestFacade',
        'Response' => 'core\response\ResponseFacade',
        'Route' => 'core\route\RouteFacade',
        'Security' => 'core\security\SecurityFacade',
        'Session' => 'core\session\SessionFacade',
        'Validate' => 'core\validate\ValidateFacade',
        'View' => 'core\view\ViewFacade',
        'WeiXin' => 'core\weixin\WeixinFacade',
        /*自定义*/
        'Form' => 'service\form\FormFacade',
        'Html' => 'service\html\HtmlFacade',
        'Page' => 'service\page\PageFacade',
    ],
];