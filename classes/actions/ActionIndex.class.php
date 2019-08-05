<?php

/**
 * Класс экшена
 */
class PluginImbaChatWidget_ActionIndex extends ActionPlugin
{

    /**
     * Инициализация экшена
     */
    public function Init()
    {
        $this->SetDefaultEvent('index');
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
        $this->AddEventPreg('/^([\w\-\_]+)$/i', '/([0-9]+)\,/i', 'EventGetUsers');
    }


    /**
     * Обработка евента index
     */
    protected function EventIndex()
    {
        /**
         * Устанавливает шаблон вывода
         */
        $this->SetTemplateAction('index');
    }
    protected function EventGetUsers()
    {
        //$this->Viewer_SetResponseAjax('json');
        $login = Config::get('plugin.imba_chat_widget.data.login');
        $password = Config::get('plugin.imba_chat_widget.data.password');
        //Authentication by developer login and password
        if(!isset($_SERVER['PHP_AUTH_USER']) || ($_SERVER['PHP_AUTH_PW']!=$password) || strtolower($_SERVER['PHP_AUTH_USER'])!=$login)
        {
            header('WWW-Authenticate: Basic realm="Backend"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Authenticate required!';
            die();
        }
        $ids = $this->getParam(0);
        $ids = explode(",", $ids);
        $users = array();
        foreach ($ids as $id){
            $user_m = $oUser = $this->User_getUserById($id);
            if(!$user_m)
                continue;
            $user = array();
            $user['name'] = $user_m->GetLogin();
            $user['user_id'] = $id;
            $users[] = $user;
        }
        echo json_encode($users);
    }
    //Log in user via login and password
    public function EventAuthUser(){
        $login = Config::get('plugin.imba_chat_widget.data.login');
        $password = Config::get('plugin.imba_chat_widget.data.password');
        //Authentication by developer login and password
        if(!isset($_SERVER['PHP_AUTH_USER']) || ($_SERVER['PHP_AUTH_PW']!=$password) || strtolower($_SERVER['PHP_AUTH_USER'])!=$login)
        {
            header('WWW-Authenticate: Basic realm="Backend"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Authenticate required!';
            die();
        }

        /**
         * Логин и пароль являются строками?
         */
        if (!is_string(getRequest('login')) or !is_string(getRequest('password'))) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
            return;
        }
        /**
         * Проверяем есть ли такой юзер по логину
         */
        if ((func_check(getRequest('login'),
                    'mail') and $oUser = $this->User_GetUserByMail(getRequest('login'))) or $oUser = $this->User_GetUserByLogin(getRequest('login'))
        ) {
            /**
             *  Выбираем сценарий валидации
             */
            $oUser->_setValidateScenario('signIn');
            /**
             * Заполняем поля (данные)
             */
            $oUser->setCaptcha(getRequestStr('captcha'));
            /**
             * Запускаем валидацию
             */
            if ($oUser->_Validate()) {
                /**
                 * Сверяем хеши паролей и проверяем активен ли юзер
                 */
                if ($this->User_VerifyAccessAuth($oUser) and $oUser->verifyPassword(getRequest('password'))) {
                    if (!$oUser->getActivate()) {
                        $this->Message_AddErrorSingle($this->Lang_Get('auth.login.notices.error_not_activated',
                            array('reactivation_path' => Router::GetPath('auth/reactivation'))));
                        return;
                    }
                    $bRemember = getRequest('remember', false) ? true : false;
                    /**
                     * Убиваем каптчу
                     */
                    $this->Session_Drop('captcha_keystring_user_auth');
                    /**
                     * Авторизуем
                     */
                    $this->User_Authorization($oUser, $bRemember);
                    /**
                     * Определяем редирект
                     */
                    $sUrl = Config::Get('module.user.redirect_after_login');
                    if (getRequestStr('return-path')) {
                        $sUrl = getRequestStr('return-path');
                    }
                    $this->Viewer_AssignAjax('sUrlRedirect', $sUrl ? $sUrl : Router::GetPath('/'));
                    return json_encode($oUser);
                }
            } else {
                /**
                 * Получаем ошибки
                 */
                $this->Viewer_AssignAjax('errors', $oUser->_getValidateErrors());
                $this->Message_AddErrorSingle(null);
                return;
            }
        }
        $this->Message_AddErrorSingle($this->Lang_Get('auth.login.notices.error_login'));
    }
    /**
     * Завершение работы экшена
     */
    public function EventShutdown()
    {
        /**
         * Здесь можно прогрузить в шаблон какие-то общие переменные для всех евентов
         */
    }
}