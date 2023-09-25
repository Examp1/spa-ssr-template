<?php

namespace App\Modules\Constructor\Collections;

class ComponentCollections
{
    const BTN_TYPE = [
        'fill'           => 'Із заливкою',
        'fill_stretch'   => 'Із заливкою (на всю ширину)',
        'stroke'         => 'З бордером',
        'stroke_stretch' => 'З бордером (на всю ширину)',
        'simple'         => 'Посилання',
        'text'           => 'Текст',
    ];
    const BTN_ICON = [
        'non'                => 'Без іконки',
        'icon-02'            => 'icon-02',
        'icon-03'            => 'icon-03',
        'icon-04'            => 'icon-04',
        'icon-05'            => 'icon-05',
        'icon-06'            => 'icon-06',
        'icon-07'            => 'icon-07',
        'icon-08'            => 'icon-08',
        'icon-09'            => 'icon-09',
        'icon-10'            => 'icon-10',
        'icon-11'            => 'icon-11',
        'icon-12'            => 'icon-12',
        'icon-13'            => 'icon-13',
        'icon-emoji-01'      => 'icon-emoji-01',
        'icon-emoji-02'      => 'icon-emoji-02',
        'icon-emoji-03'      => 'icon-emoji-03',
        'icon-emoji-04'      => 'icon-emoji-04',
        'icon-emoji-05'      => 'icon-emoji-05',
        'icon-arrow-left'    => 'icon-arrow-left',
        'icon-arrow-right'   => 'icon-arrow-right',
        'icon-check'         => 'icon-check',
        'icon-close'         => 'icon-close',
        'icon-plus'          => 'icon-plus',
        'icon-play'          => 'icon-play',
        'icon-send'          => 'icon-send',
        'icon-facebook'      => 'icon-facebook',
        'icon-telegram'      => 'icon-telegram',
        'icon-twitter'       => 'icon-twitter',
        'icon-link'          => 'icon-link',
        'icon-arrow-left-2'  => 'icon-arrow-left-2',
        'icon-arrow-right-2' => 'icon-arrow-right-2'
    ];

    const SEPARATOR = [
        'M'   => 'M',
        'S'   => 'S',
        'L'   => 'L',
        'NON' => 'NON',
    ];

    /**
     * @param $group
     * @return array
     */
    public static function widget($group)
    {
        return [
            'label'  => 'Віджет',
            'params' => [
                'labels'     => [
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'widgets'    => function ($lang) use ($group) {
                    $widgets = \App\Modules\Widgets\Models\Widget::where('lang', $lang)->get();

                    return ['' => '---'] + collect($widgets)
                            ->mapWithKeys(function ($widget) use ($group) {
                                if (in_array($group, array_keys($widget->getGroups()))) {
                                    return [$widget->id => $widget->name];
                                } else {
                                    return [];
                                }
                            })
                            ->toArray();
                },
                'shown_name' => 'widget',
                'separator'  => self::SEPARATOR,
            ],
            'rules'  => [
                'content.widget' => 'nullable',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function form()
    {
        return [
            'label'  => 'Форма',
            'params' => [
                'labels'     => [
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'forms'      => function ($lang) {
                    $forms = \App\Modules\Forms\Models\Form::query()->where('lang', $lang)->get();

                    return ['' => '---'] + collect($forms)
                            ->mapWithKeys(function ($form) {
                                return [$form->id => $form->name];
                            })
                            ->toArray();
                },
                'shown_name' => 'form',
                'separator'  => self::SEPARATOR,
            ],
            'rules'  => [
                'content.form' => 'nullable',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function imageAndText()
    {
        return [
            'label'  => 'Зображення та текст',
            'params' => [
                'image_positions' => [
                    'right' => 'Праворуч',
                    'left'  => 'Ліворуч',
                ],
                'column_width'    => [
                    '3' => '30/70',
                    '5' => '50/50',
                    '7' => '70/30',
                ],
                'font_size'       => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'labels'          => [
                    'title'            => 'Заголовок',
                    'subtitle'         => 'Підзаголовок',
                    'anker_title'      => 'Anker title',
                    'image_position'   => 'Положення зображення',
                    'column_width'     => 'Ширина колонки зображення',
                    'video_link'       => 'Посилання на відео',
                    'image'            => 'Зображення',
                    'image_mob'        => 'Зображення (моб.)',
                    'font_size'        => 'Розмір шрифту',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'image_height'     => 'Висота картинки',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'btn_type'        => self::BTN_TYPE,
                'btn_icon'        => self::BTN_ICON,
                'image_height'    => [
                    'fix'  => 'фікс. висота',
                    'text' => 'по висоті тексту',
                ],
                'separator'       => self::SEPARATOR,
                'shown_name'      => 'title'
            ],
            'rules'  => [
                'content.title'          => 'nullable|string|max:255',
                'content.font_size'      => 'nullable|string|max:255',
                'content.image'          => 'nullable|string|max:255',
                'content.image_mob'      => 'nullable|string|max:255',
                'content.image_position' => 'required|string|max:255',
                'content.column_width'   => 'required|string|max:255',
                'content.description'    => 'nullable|string|max:65000',
                'content.background'     => 'nullable|string|max:255',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function simpleText()
    {
        return [
            'label'  => 'Текст',
            'params' => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'subtitle'         => 'Підзаголовок',
                    'fon_image'        => 'Фонове зображення',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                    'text_width'       => 'Ширина тексту',
                    'text_align'       => 'Вирівнювання тексту',
                    'font_size'        => 'Розмір шрифту',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'text_width' => [
                    '60' => '60%',
                    '90' => '90%',
                ],
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'text_align' => [
                    'center' => 'По центру',
                    'left'   => 'Ліворуч',
                    'right'  => 'Праворуч',
                ],
                'separator'  => self::SEPARATOR,
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'shown_name' => 'title'
            ],
            'rules'  => [
                'content.title'            => 'nullable|string|max:255',
                'content.description'      => 'nullable|string|max:65000',
                'content.text_width'       => 'nullable|string|max:255',
                'content.text_align'       => 'nullable|string|max:255',
                'content.text_color'       => 'nullable|string|max:255',
                'content.background'       => 'nullable|string|max:255',
                'content.background_image' => 'nullable|string|max:255',
                'content.font_size'        => 'nullable|string|max:255',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function cta()
    {
        return [
            'label'  => 'СТА',
            'params' => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'fill'             => 'С заливкой',
                    'bg_color'         => 'Колір заливки',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'separator'  => self::SEPARATOR,
                'fill'       => [
                    '0' => 'Так',
                    '1' => 'Ні',
                ],
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'shown_name' => 'title'
            ],
            'rules'  => [
                'content.title' => 'nullable|string|max:255',
                'content.text'  => 'nullable|string|max:65000',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function videoAndText()
    {
        return [
            'label'    => 'Видео',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'file'             => 'Посилання на відео',
                    'image'            => 'Попередній перегляд',
                    'font_size'        => 'Розмір шрифту',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'     => 'nullable|string|max:255',
                'content.font_size' => 'nullable|string|max:255',
                'content.file'      => 'nullable|string|max:255',
                'content.image'     => 'nullable|string|max:255',
                'content.text'      => 'nullable|string|max:65000',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок максимум 255 символів',
                'content.text.max'  => 'Текст - максимум 65000 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function list()
    {
        return [
            'label'    => 'Список',
            'params'   => [
                'type'       => [
                    'ul' => 'Звичайний',
                    'ol' => 'Нумерований'
                ],
                'labels'     => [
                    'type'             => 'Тип',
                    'item'             => 'Текст',
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'       => 'nullable|string|max:255',
                'content.type'        => 'required|string|max:255',
                'content.list.*.item' => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.type.required' => 'Виберіть тип',
                'content.title.max'     => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function button()
    {
        return [
            'label'    => 'Кнопка',
            'params'   => [
                'labels'     => [
                    'title'            => 'Текст кнопки',
                    'anker_title'      => 'Anker title',
                    'link'             => 'Посилання',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title' => 'nullable|string|max:255',
                'content.link'  => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
                'content.link.max'  => 'Посилання - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function fullImage()
    {
        return [
            'label'    => 'Фото',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'width_type'       => 'Ширина фото',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'width_type' => [
                    '80'  => 'По контейнеру',
                    '100' => 'На всю ширину'
                ],
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title' => 'nullable|string|max:255',
                'content.image' => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів'
            ],
        ];
    }

    /**
     * @return array
     */
    public static function imageBlog()
    {
        return [
            'label'    => 'Фото',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title' => 'nullable|string|max:255',
                'content.image' => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів'
            ],
        ];
    }

    /**
     * @return array
     */
    public static function textNColumns()
    {
        return [
            'label'    => '1-3 колонковий текст',
            'params'   => [
                'columns'    => [
                    '1' => '1 колонка',
                    '2' => '2 колонки',
                    '3' => '3 колонки',
                ],
                'labels'     => [
                    'title'               => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'title_column_select' => 'Колонки',
                    'font_size'           => 'Розмір шрифту',
                    'top_separator'       => 'Верхній роздільник',
                    'bottom_separator'    => 'Нижній роздільник',
                    'title_font_size'     => 'Розмір шрифту заголовка',
                ],
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'              => 'nullable|string|max:255',
                'content.font_size'          => 'nullable|string|max:255',
                'content.rows'               => 'nullable|array',
                'content.rows.*.column_text' => 'nullable|array',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів'
            ],
        ];
    }

    /**
     * @return array
     */
    public static function stages()
    {
        return [
            'label'    => 'Етапи',
            'params'   => [
                'labels'           => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'text'             => 'Текст',
                    'font_size'        => 'Розмір шрифту',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'content_position' => 'Позиція контенту',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'font_size'        => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'separator'        => self::SEPARATOR,
                'content_position' => [
                    'left'   => 'Ліворуч',
                    'center' => 'По центру',
                    'right'  => 'Праворуч'
                ],
                'btn_type'         => self::BTN_TYPE,
                'btn_icon'         => self::BTN_ICON,
                'shown_name'       => 'title'
            ],
            'rules'    => [
                'content.title'              => 'nullable|string|max:255',
                'content.text'               => 'nullable|string|max:3000',
                'content.font_size'          => 'nullable|string|max:255',
                'content.rows'               => 'nullable|array',
                'content.rows.*.column_text' => 'nullable|array',
            ],
            'messages' => [
                'content.title.max'  => 'Заголовок - максимум 255 символів',
                'content.title.text' => 'Текст - максимум 3000 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function table()
    {
        $cols = [];
        for ($i = 2; $i <= 6; $i++) {
            $cols[(string) $i] = (string) $i;
        }

        return [
            'label'    => 'Таблиця',
            'params'   => [
                'icons'       => self::BTN_ICON,
                'table_width' => $cols,
                'cols_width'  => [
                    'auto' => 'auto',
                    '5%'   => '5%',
                    '10%'  => '10%',
                    '16%'  => '16%',
                    '20%'  => '20%',
                    '25%'  => '25%',
                    '33%'  => '33%',
                    '50%'  => '50%',
                    '60%'  => '60%',
                    '75%'  => '75%',
                    '80%'  => '80%',
                    '90%'  => '90%',
                ],
                'labels'      => [
                    'table_width'      => "Кол.",
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'column_text'      => "Текст",
                    'column_width'     => "Ширина колонки",
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'style_type'       => 'Тип відображення колонок',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'font_size'   => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'separator'   => self::SEPARATOR,
                'style_type'  => [
                    'default' => '-',
                    'numbers' => 'Нумерованый',
                    'icons'   => 'З іконками',
                ],
                'shown_name'  => 'title'
            ],
            'rules'    => [
                'content.table_width'        => 'required|string|max:255',
                'content.anker_title'        => 'nullable|string|max:255',
                'content.rows'               => 'nullable|array',
                'content.rows.*.column_text' => 'nullable|array',
            ],
            'messages' => [
                'content.table_width.required' => "Кол. - обов'язкове поле"
            ],
        ];
    }

    /**
     * @return array
     */
    public static function quotes()
    {
        return [
            'label'    => 'Цитата',
            'params'   => [
                'labels'     => [
                    'author'           => 'Автор',
                    'anker_title'      => 'Anker title',
                    'text'             => 'Текст',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник'
                ],
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title' => 'nullable|string|max:255',
                'content.text'  => 'nullable|string|max:65000',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
                'content.text.max'  => 'Текст - максимум 65000 символів'
            ],
        ];
    }

    /**
     * @return array
     */
    public static function accordion()
    {
        return [
            'label'    => 'Акордеон',
            'params'   => [
                'labels'           => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'content_position' => 'Позиція контенту',
                    'text'             => 'Текст',
                    'image'            => 'Зображення',
                    'type'             => 'Тип',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'type'             => [
                    'numerical' => 'З нумерацією',
                    'default'   => 'Без нумерації'
                ],
                'font_size'        => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'content_position' => [
                    'left'   => 'Ліворуч',
                    'center' => 'По центру',
                    'right'  => 'Праворуч'
                ],
                'separator'        => self::SEPARATOR,
                'btn_type'         => self::BTN_TYPE,
                'btn_icon'         => self::BTN_ICON,
                'shown_name'       => 'title'
            ],
            'rules'    => [
                'content.title'        => 'nullable|string|max:255',
                'content.list.*.title' => 'nullable|string|max:255',
                'content.list.*.text'  => 'nullable|string|max:6000',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function advantages()
    {
        return [
            'label'    => 'Переваги',
            'params'   => [
                'labels'       => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'image'            => 'Зображення',
                    'text'             => 'Текст',
                    'items_in_row'     => 'В ряду по',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'items_in_row' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4'
                ],
                'font_size'    => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'separator'    => self::SEPARATOR,
                'btn_type'     => self::BTN_TYPE,
                'btn_icon'     => self::BTN_ICON,
                'shown_name'   => 'title'
            ],
            'rules'    => [
                'content.title'        => 'nullable|string|max:255',
                'content.list.*.title' => 'nullable|string|max:255',
                'content.list.*.text'  => 'nullable|string|max:6000',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function quoteSlider()
    {
        return [
            'label'    => 'Цитата слайдер',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'author'           => 'Автор',
                    'image'            => 'Зображення',
                    'text'             => 'Текст',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'separator'  => self::SEPARATOR,
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'        => 'nullable|string|max:255',
                'content.author'       => 'nullable|string|max:255',
                'content.list.*.title' => 'nullable|string|max:255',
                'content.list.*.text'  => 'nullable|string|max:6000',
            ],
            'messages' => [
                'content.title.max'  => 'Заголовок - максимум 255 символів',
                'content.author.max' => 'Автор - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function numbers()
    {
        return [
            'label'    => 'Цифри',
            'params'   => [
                'labels'       => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'number'           => 'Цифра',
                    'text'             => 'Текст',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'items_in_row'     => 'В ряду по',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'separator'    => self::SEPARATOR,
                'font_size'    => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'items_in_row' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4'
                ],
                'btn_type'     => self::BTN_TYPE,
                'btn_icon'     => self::BTN_ICON,
                'shown_name'   => 'title'
            ],
            'rules'    => [
                'content.title'         => 'nullable|string|max:255',
                'content.list.*.title'  => 'nullable|string|max:255',
                'content.list.*.number' => 'nullable|string|max:20',
                'content.list.*.text'   => 'nullable|string|max:6000',
            ],
            'messages' => [
                'content.title.max'    => 'Заголовок - максимум 255 символів',
                'content.title.number' => 'Цифра - максимум 20 символів',
                'content.title.text'   => 'Текст - максимум 6000 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function blocks()
    {
        return [
            'label'    => 'Карточки 1-4',
            'params'   => [
                'labels'     => [
                    'title'                => 'Заголовок',
                    'anker_title'          => 'Anker title',
                    'link'                 => 'Посилання',
                    'image'                => 'Зображення',
                    'btn_name'             => 'Текст кнопки',
                    'title_column_select'  => 'Кількість у ряд',
                    'top_separator'        => 'Верхній роздільник',
                    'bottom_separator'     => 'Нижній роздільник',
                    'card_btn_style_title' => 'Вид кнопок в карточках',
                    'card_btn_style_type'  => '',
                    'card_btn_style_icon'  => '',
                    'title_font_size'      => 'Розмір шрифту заголовка',
                ],
                'columns'    => [
                    '1' => 'по 1 в ряд',
                    '2' => 'по 2 в ряд',
                    '3' => 'по 3 в ряд',
                    '4' => 'по 4 в ряд',
                ],
                'separator'  => self::SEPARATOR,
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'btn_type'   => array_merge(['none' => 'Без кнопки'], self::BTN_TYPE),
                'btn_icon'   => self::BTN_ICON,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'       => 'nullable|string|max:255',
                'content.btn_name'    => 'nullable|string|max:255',
                'content.list.*.link' => 'nullable|string|max:255',
                'content.list.*.text' => 'nullable|string|max:6000',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function gallery()
    {
        return [
            'label'    => 'Галерея',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'text'             => 'Текст',
                    'image'            => 'Зображення',
                    'image_title'      => 'Заголовок',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'size_fix'         => 'Фіксація ширина/висота',
                    'align'            => 'Вирівнювання',
                    'show_btns'        => 'Відображати кнопки керування',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'size_fix'   => [
                    'auto_height' => 'ширина - задана, висота - авто',
                    'auto_width'  => 'высота - задана, ширина - авто',
                ],
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'align'      => [
                    'top'    => 'по верхньому краю',
                    'center' => 'по центру',
                ],
                'show_btns'  => [
                    '1' => 'ні',
                    '0' => 'так',
                ],
                'separator'  => self::SEPARATOR,
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'       => 'nullable|string|max:255',
                'content.list.*.item' => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function seeAlso()
    {
        return [
            'label'    => 'Дивіться також',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'       => 'nullable|string|max:255',
                'content.list.*.item' => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function linkList()
    {
        return [
            'label'    => 'Посилання',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'name'             => 'Назва',
                    'file'             => 'Файл',
                    'icon'             => 'Іконка',
                    'file_btn_name'    => 'Напис на кнопці завантаження',
                    'link'             => 'Посилання',
                    'date'             => 'Дата',
                    'order'            => 'Сортування',
                    'link_btn_name'    => 'Назва для переходу по посиланню',
                    'type'             => 'Вид',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'type'       => [
                    'list' => 'Список',
                    'tags' => 'Тегами',
                ],
                'separator'  => self::SEPARATOR,
                'btn_type'   => config('buttons.type'),
                'btn_icon'   => config('buttons.icon'),
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title' => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function textDivider()
    {
        return [
            'label'  => 'Роздільник тексту',
            'params' => [
                'labels'     => [
                    'text'             => 'Текст',
                    'anker_title'      => 'Anker title',
                    'font_size'        => 'Розмір шрифту',
                    'background_color' => 'Колір фону',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'separator'  => self::SEPARATOR,
                'btn_type'   => config('buttons.type'),
                'btn_icon'   => config('buttons.icon'),
                'shown_name' => 'title'
            ],
            'rules'  => [
                'content.text'      => 'nullable|string|max:65000',
                'content.font_size' => 'nullable|string|max:255',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function blocksSlider()
    {
        return [
            'label'    => 'Карточка слайдер',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'description'      => 'Опис',
                    'link'             => 'Посилання',
                    'image'            => 'Зображення',
                    'btn_name'         => 'Текст кнопки',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'separator'  => self::SEPARATOR,
                'btn_type'   => array_merge(['none' => 'Без кнопки'], config('buttons.type')),
                'btn_icon'   => config('buttons.icon'),
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'       => 'nullable|string|max:255',
                'content.btn_name'    => 'nullable|string|max:255',
                'content.list.*.link' => 'nullable|string|max:255',
                'content.list.*.text' => 'nullable|string|max:6000',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function team()
    {
        return [
            'label'  => 'Команда',
            'params' => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'name'             => "ім'я",
                    'position'         => 'Посада',
                    'text'             => 'Текст',
                    'image'            => 'Зображення',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'separator'  => self::SEPARATOR,
                'shown_name' => 'title'
            ],
            'rules'  => [
                'content.title' => 'nullable|string|max:255',
            ]
        ];
    }

    /**
     * @return array
     */
    public static function theses()
    {
        return [
            'label'    => 'Тезиси',
            'params'   => [
                'labels'     => [
                    'item'             => 'Теза',
                    'anker_title'      => 'Anker title',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'separator'  => self::SEPARATOR,
                'shown_name' => ''
            ],
            'rules'    => [
                'content.item' => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.item.max' => 'Теза - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function simpleTitle()
    {
        return [
            'label'    => 'Заголовок',
            'params'   => [
                'labels'     => [
                    'title'            => 'Заголовок',
                    'anker_title'      => 'Anker title',
                    'subtitle'         => 'Підзаголовок',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'separator'  => self::SEPARATOR,
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'    => 'nullable|string|max:255',
                'content.subtitle' => 'nullable|string|max:255',
            ],
            'messages' => [
                'content.item.max'     => 'Заголовок - максимум 255 символів',
                'content.subtitle.max' => 'Підзаголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function partners()
    {
        return [
            'label'    => 'Партнери',
            'params'   => [
                'labels'     => [
                    'image'            => 'Зображення',
                    'anker_title'      => 'Anker title',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                ],
                'separator'  => self::SEPARATOR,
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.list.*.item' => 'nullable|string|max:255',
            ],
            'messages' => [],
        ];
    }

    /**
     * @return array
     */
    public static function blocksLinks()
    {
        return [
            'label'    => 'Карточки з посиланнями',
            'params'   => [
                'labels'     => [
                    'title'               => 'Заголовок',
                    'anker_title'         => 'Anker title',
                    'subtitle'            => 'Підзаголовок',
                    'link'                => 'Посилання',
                    'image'               => 'Іконка',
                    'title_column_select' => 'Кількість у ряд',
                    'top_separator'       => 'Верхній роздільник',
                    'bottom_separator'    => 'Нижній роздільник',
                    'title_font_size'     => 'Розмір шрифту заголовка',
                ],
                'columns'    => [
                    '1' => 'по 1 в ряд',
                    '2' => 'по 2 в ряд',
                    '3' => 'по 3 в ряд',
                    '4' => 'по 4 в ряд',
                ],
                'separator'  => self::SEPARATOR,
                'btn_type'   => self::BTN_TYPE,
                'btn_icon'   => self::BTN_ICON,
                'font_size'  => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'shown_name' => 'title'
            ],
            'rules'    => [
                'content.title'       => 'nullable|string|max:255',
                'content.btn_name'    => 'nullable|string|max:255',
                'content.list.*.link' => 'nullable|string|max:255',
                'content.list.*.text' => 'nullable|string|max:6000',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function accordionTable()
    {
        return [
            'label'    => 'Акордеон-таблиця',
            'params'   => [
                'icons'       => self::BTN_ICON,
                'labels'           => [
                    'title'            => 'Заголовок',
                    'date'             => 'Дати',
                    'subtitle'         => 'Підзаголовок',
                    'anker_title'      => 'Anker title',
                    'content_position' => 'Позиція контенту',
                    'text'             => 'Текст',
                    'image'            => 'Зображення',
                    'icon'             => 'Іконка',
                    'type'             => 'Тип',
                    'top_separator'    => 'Верхній роздільник',
                    'bottom_separator' => 'Нижній роздільник',
                    'title_font_size'  => 'Розмір шрифту заголовка',
                ],
                'type'             => [
                    'numerical' => 'З нумерацією',
                    'default'   => 'Без нумерації'
                ],
                'font_size'        => [
                    'normal' => 'Звичайний',
                    'small'  => 'Маленький',
                ],
                'content_position' => [
                    'left'   => 'Ліворуч',
                    'center' => 'По центру',
                    'right'  => 'Праворуч'
                ],
                'separator'        => self::SEPARATOR,
                'btn_type'         => self::BTN_TYPE,
                'btn_icon'         => self::BTN_ICON,
                'shown_name'       => 'title'
            ],
            'rules'    => [
                'content.title'        => 'nullable|string|max:255',
                'content.list.*.title' => 'nullable|string|max:255',
                'content.list.*.text'  => 'nullable|string|max:15000',
            ],
            'messages' => [
                'content.title.max' => 'Заголовок - максимум 255 символів',
            ],
        ];
    }
}

