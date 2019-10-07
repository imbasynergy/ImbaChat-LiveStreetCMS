<?php
use engine\classes\Mapper;
//use engine\classes\Engine;
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
        $this->AddEventPreg('/^searchusers$/i', '/\w/', 'EventSearchUsers');
        $this->AddEventPreg('/^getusers$/i', '/[1-9]\d{0,5}/', 'EventGetUsers');
        $this->AddEventPreg('/^authuser$/i', 'EventAuthUser');
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
    public function EventGetUsers()
    {
        $this->testAuthOrDie();
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
    public function EventSearchUsers()
    {
        $this->testAuthOrDie();
        $mysqli = new mysqli(Config::get('db.params.host'), Config::get('db.params.user'), Config::get('db.params.pass'), Config::get('db.params.dbname'), 3306);
        $res = $mysqli->query("SELECT * FROM user WHERE user_login LIKE '%".$mysqli->real_escape_string($this->getParam(0))."%'");
        $users = array();
        while ($row = $res->fetch_assoc()) {
            $user = array();
            $user['name'] = $row['user_login'];
            $user['user_id'] = $row['user_id'];
            $users[] = $user;
        }
        echo json_encode($users);
    }
    //Log in user via login and password
    public function EventAuthUser(){
        $this->testAuthOrDie();

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
                    
                    $this->User_Authorization($oUser, fasle);
                    echo (json_encode(['name'=>$oUser->GetLogin(), 'id' => $oUser->GetId()]));
                    return;
                }else{
                    echo json_encode(['errors' => ['Username and password do not match!']]);
                    return;
                }
            } else {
                /**
                 * Получаем ошибки
                 */
                echo json_encode(['errors' => [$oUser->_getValidateErrors()]]);
                return;
            }
        }
        echo json_encode(['errors' => ['User is missing!']]);
        return;
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
    protected function testAuthOrDie()
    {
        $login = Config::get('plugin.imba_chat_widget.data.login');
        $password = Config::get('plugin.imba_chat_widget.data.password');
        if(!isset($_SERVER['PHP_AUTH_USER'])
                || ($_SERVER['PHP_AUTH_PW']!=$password) 
                || strtolower($_SERVER['PHP_AUTH_USER'])!=$login)
        {
            header('WWW-Authenticate: Basic realm="Backend"');
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode([
                "code" => 401,
                "version" => $this->getApiVersion()['version'],
                "error" => 'Authenticate required!',
                'debug' => ''
            ]);
            die();
        }
    }
}