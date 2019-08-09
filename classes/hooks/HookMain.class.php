<?php

/**
 * Класс обработчиков хуков
 */
class PluginImbaChatWidget_HookMain extends Hook
{
    /**
     * Регистрация событий на хуки
     * В данном методе необходимо обявлять тольео хуки, без какой-либо дополнительной логики с вызовом других модулей,
     * например, проверка авторизации пользователя и т.п. Это может нарушить работу движка и других плагинов.
     */
    public function RegisterHook()
    {
        /**
         * Хук 'topic_edit_after' вызывается после редактирование топика.
         * В качестве обработчика назначается метод HookTopicEditAfter
         */
        $this->AddHook('topic_edit_after', 'HookTopicEditAfter');

        /**
         * Хук на старте экшенов. Выполняется один раз в отличии от хука "init_action"
         * Четвертый параметр 1000 - это приоритет выполнение среди остальных навешанных хуков (чем больше, тем раньше будет выполнен обработчик)
         */
        $this->AddHook('start_action', 'HookStartAction', __CLASS__, 1000);
    }

    /**
     * Обработчик хука
     */
    public function HookTopicEditAfter($aParams)
    {
        /**
         * Получаем топик из параметров
         */
        $oTopic = $aParams['oTopic'];

    }

    public function getJWT()
    {
// Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $pass = Config::get('plugin.imba_chat_widget.data.in_password');
        $data = array();
        $data['exp'] = (int)date('U')+3600*7;
        $data['user_id'] = $this->User_GetUserCurrent()->GetId();

        if(isset($data['user_id']))
        {
            $data['user_id'] = (int)$data['user_id'];
        }

// Create token payload as a JSON string
        $payload = json_encode($data);

// Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

// Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $pass, true);

// Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create JWT
        return trim($base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature);
    }

    public function HookStartAction($aParams)
    {   
        if($this->User_GetUserCurrent()){
            $token = $this->getJWT();
            $user_id = $this->User_GetUserCurrent()->GetId();
            $settings = array
            (
                    // Предустановленные значения по умолчанию
                    //"language" => self::property('language'),
                    "user_id" => $user_id,
                    "token" => $token,
                    /*"resizable" => self::property('resizable'),
                    "draggable" => self::property('draggable'),
                    "theme" => self::property('theme'),
                    "position" => self::property('position'),
                    "useFaviconBadge" => self::property('useFaviconBadge'),
                    "updateTitle" => self::property('updateTitle'),*/
            );
            $settings = json_encode($settings);
            $this->Viewer_Assign('settings',$settings);
            echo $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'default.tpl');
        }
        
    }
    public function inj_body_begin(){
        $this->Viewer_Assign('sSWFBallsTemplateWebPath',Plugin::GetTemplateWebPath(__CLASS__).'nyb.swf');
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'inj_body_begin.tpl');
    }
}