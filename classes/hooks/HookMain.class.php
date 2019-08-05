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
        $this->AddHook('form_add_blog_end', 'HookReturnJs');
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

    public function HookStartAction($aParams)
    {
        /**
         * Регистрируем компонент плагина (позволяет автоматически подгружать его css/js)
         */
        $this->Component_Add('imba_chat_widget:p-test');
    }

    public function HookReturnJs(){
        return '<p style="color:red;">test</p>';
    }
}