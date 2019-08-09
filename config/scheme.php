<?php

/*
 * Описание настроек плагина для интерфейса редактирования в админке
 */
$config['$config_scheme$'] = array(
    'data.in_password'           => array(
        /*
         * тип: integer, string, array, boolean, float
         */
        'type'        => 'string',
        /*
         * отображаемое имя параметра, ключ языкового файла
         */
        'name'        => 'Secret key',
        /*
         * отображаемое описание параметра, ключ языкового файла
         */
        'description' => 'Secret key',
        /*
         * валидатор (не обязательно)
         */
        'validator'   => array(
            /*
             * тип валидатора, существующие типы валидаторов движка:
             * Boolean, Compare, Date, Email, Number, Regexp, Required, String, Tags, Type, Url, Array (специальный валидатор, см. документацию)
             */
            'type'   => 'String',
            /*
             * параметры, которые будут переданы в валидатор
             */
            'params' => array(
                'min'         => 1,
                /*
                 * не допускать пустое значение
                 */
                'allowEmpty'  => false,
            ),
        ),
    ),
    'data.dev_id'           => array(
        /*
         * тип: integer, string, array, boolean, float
         */
        'type'        => 'integer',
        /*
         * отображаемое имя параметра, ключ языкового файла
         */
        'name'        => 'Developer id',
        /*
         * отображаемое описание параметра, ключ языкового файла
         */
        'description' => 'Developer id',
        /*
         * валидатор (не обязательно)
         */
        'validator'   => array(
            /*
             * тип валидатора, существующие типы валидаторов движка:
             * Boolean, Compare, Date, Email, Number, Regexp, Required, String, Tags, Type, Url, Array (специальный валидатор, см. документацию)
             */
            'type'   => 'Number',
            /*
             * параметры, которые будут переданы в валидатор
             */
            'params' => array(
                'min'         => 1,
                /*
                 * не допускать пустое значение
                 */
                'allowEmpty'  => false,
            ),
        ),
    ),
    'data.login'           => array(
        /*
         * тип: integer, string, array, boolean, float
         */
        'type'        => 'string',
        /*
         * отображаемое имя параметра, ключ языкового файла
         */
        'name'        => 'Login',
        /*
         * отображаемое описание параметра, ключ языкового файла
         */
        'description' => 'Developer login',
        /*
         * валидатор (не обязательно)
         */
        'validator'   => array(
            /*
             * тип валидатора, существующие типы валидаторов движка:
             * Boolean, Compare, Date, Email, Number, Regexp, Required, String, Tags, Type, Url, Array (специальный валидатор, см. документацию)
             */
            'type'   => 'String',
            /*
             * параметры, которые будут переданы в валидатор
             */
            'params' => array(
                'min'         => 1,
                /*
                 * не допускать пустое значение
                 */
                'allowEmpty'  => false,
            ),
        ),
    ),
    'data.password'           => array(
        /*
         * тип: integer, string, array, boolean, float
         */
        'type'        => 'string',
        /*
         * отображаемое имя параметра, ключ языкового файла
         */
        'name'        => 'Password',
        /*
         * отображаемое описание параметра, ключ языкового файла
         */
        'description' => 'Developer password',
        /*
         * валидатор (не обязательно)
         */
        'validator'   => array(
            /*
             * тип валидатора, существующие типы валидаторов движка:
             * Boolean, Compare, Date, Email, Number, Regexp, Required, String, Tags, Type, Url, Array (специальный валидатор, см. документацию)
             */
            'type'   => 'String',
            /*
             * параметры, которые будут переданы в валидатор
             */
            'params' => array(
                'min'         => 1,
                /*
                 * не допускать пустое значение
                 */
                'allowEmpty'  => false,
            ),
        ),
    ),
);

/**
 * Описание разделов для настроек
 * Каждый раздел группирует определенные параметры конфига
 */
$config['$config_sections$'] = array(
    /**
     * Настройки раздела
     */
    array(
        /**
         * Название раздела
         */
        'name'         => 'config_sections.one',
        /**
         * Список параметров для отображения в разделе
         */
        'allowed_keys' => array(

            'data.dev_id',
            'data.login',
            'data.password',
            'data.in_password',
        ),
    ),
);

return $config;