<?php

return [

    //
    // Plugin
    //

    'plugin' => [
        'name' => 'Импорт товаров',
        'description' => 'Создание шаблонов, задач импорта и сохранение журнала импорта товаров',
        'access_task' => 'Управление заданиями импорта',
        'access_template' => 'Управление шаблонами импорта',
        'access_logs' => 'Управление журналом импорта'
    ],

    //
    // Tasks
    //

    'tasks' => [
        'title' => 'Задачи импорта',
        'description' => 'Просмотр списка задач импорта',
        'return_to_list' => 'Вернуться к списку задач',
    ],

    'task' => [
        // Fields
        'id' => 'ID задания',
        'status' => 'Статус импорта',
        'status_done' => 'Выполнен',
        'status_failed' => 'Не выполнен',
        'status_waiting' => 'Ожидает выполнения',
        'file' => 'Файл импорта',
        'author' => 'Автор',
        'template' => 'Шаблон импорта',
        'template_empty' => '--- Выберите шаблон импорта ---',
        'created' => 'Создано',
        'updated' => 'Обновлено',
    ],

    //
    // Templates
    //

    'templates' => [
        'title' => 'Шаблоны импорта',
        'description' => 'Просмотр списка шаблонов импорта',
        'return_to_list' => 'Вернуться к списку шаблонов',
        'create_template' => 'Создать шаблон',
    ],

    'template' => [
        'label' => 'Шаблон',
        'create_title' => 'Создание шаблона',
        'update_title' => 'Изменение шаблона',
        'preview_title' => 'Просмотр шаблона',
        'save_flash' => 'Шаблон был успешно сохранён',
        'delete_flash' => 'Шаблон был успешно удалён',
        // Fields
        'name' => 'Название шаблона',
        'file' => 'Файл импорта',
        'description' => 'Описание шаблона',
        'mapping' => 'Таблица соответствия полей ',
        'mapping_file_column' => 'Столбцы файла',
        'mapping_file_value' => 'Значения столбцов файла',
        'mapping_db_column' => 'Поля базы данных',
        'mapping_name' => 'Название товара',
        'mapping_slug' => 'URL параметр товара',
        'mapping_sku' => 'SKU код товара',
        'mapping_isbn' => 'ISBN номер товара',
        'mapping_price' => 'Цена товара',
        'mapping_description' => 'Описание товара',
        'mapping_width' => 'Ширина товара',
        'mapping_height' => 'Высота товара',
        'mapping_depth' => 'Глубина товара',
        'mapping_weight' => 'Вес товара',
        'mapping_active' => 'Статус активности',
        'mapping_searchable' => 'Статус индексации',
        'mapping_unique_text' => 'Статус уникального текста',
        'mapping_bindings' => 'Связи товара',
        'mapping_categories' => 'Категории товара',
        'mapping_properties' => 'Свойства товара',
        'mapping_publisher' => 'Издательство товара',
        'mapping_publisher_set' => 'Серия товара',
        'created' => 'Создан',
        'updated' => 'Изменён',
    ],

    //
    // Logs
    //

    'logs' => [
        'title' => 'Журнал импорта товаров',
        'description' => 'Просмотр списка успешных импортов',
    ],

    'log' => [
        // Fields
        'id' => 'ID события',
        'created_count' => 'Создано',
        'updated_count' => 'Обновлено',
        'skipper_count' => 'Пропущено',
        'warning_count' => 'Предупреждений',
        'error_count' => 'Ошибок',
        'created' => 'Дата создания',
        'updated' => 'Дата обновления',
        'author' => 'Автор',
        'template' => 'Шаблон',
    ],

];