<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_nomenclature".
 *
 * @property int $id
 * @property string $article_of_manufacture артикул производителя - вводится пользователем в веб интерфейсе в соответствии с документом контрагента
 * @property string $nomenclature_name наименование номенклатуры - вводится пользователем в веб интерфейсе включая указание товарной группы, наименование бренда, и артикула поставщика
 * @property int $commodity_group_code код товарной группы - справочник
 * @property int $brand_code код бренда - справочник
 * @property int $code_sex код пол - справочник
 * @property int $code_pattern код рисунок/узор - справочник
 * @property int $code_color код цвет - справочник
 * @property int $code_composition_top код состав верх - справочник
 * @property int $code_filler_composition код состав наполнитель - справочник
 * @property int $code_composition_lining код состав подклад - справочник
 * @property int $code_insulation код утеплитель - справочник
 * @property int $code_collar код ворот - справочник
 * @property int $code_clasp код застежка - справочник
 * @property int $code_number_of_buttons код число пуговиц - справочник
 * @property int $code_pockets код карманы - справочник
 * @property int $code_hood код капюшон - справочник
 * @property int $code_length код длина - справочник
 * @property int $code_width код ширина - справочник
 * @property int $code_sleeve код рукав - справочник
 * @property int $сode_silhouette код силуэт - справочник
 * @property int $code_belt код пояс - справочник
 * @property int $code_buckle код пряжка - справочник
 * @property int $code_landing_line код линия посадки - справочник
 * @property int $security_code код защипы - справочник
 * @property int $code_slots код шлицы - справочник
 * @property int $code_season код сезон - справочник
 * @property string $features_of_the_model особенности модели - вводится пользователем в веб интерфейсе
 * @property string $product_description_on_the_site описание товара на сайте - вводится пользователем в веб интерфейсе
 * @property string $labeling надпись на этикетке - вводится пользователем в веб интерфейсе
 * @property string $nomenclature_codes_similar_products номенклатурные коды похожие товары - вводится пользователем в веб интерфейсе
 * @property string $nomenclatural_codes номенклатурные коды подборка - вводится пользователем в веб интерфейсе
 * @property string $novelty_of_the_season признак новинка сезона - вводится пользователем в веб интерфейсе
 * @property int $display признак отображать на сайте - по умолчаниюзначение "да", может быть изменено обработкой "отображение на сайте"
 * @property string $detail_page_url адрес страницы товара на сайте - генерируется автоматически при создании номенклатурного кода
 *
 * @property Product[] $products
 */
class ProductNomenclature extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_nomenclature';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'brand_code',
                    'article_of_manufacture',
//                    'nomenclature_name',
                    'commodity_group_code',
//                    'brand_code',
                    'code_sex',
//                    'code_pattern', 'code_color',
//                    'code_composition_top',
//                    'code_filler_composition',
//                    'code_composition_lining',
//                    'code_insulation',
//                    'code_collar',
//                    'code_clasp',
//                    'code_number_of_buttons',
//                    'code_pockets',
//                    'code_hood',
//                    'code_length',
//                    'code_width',
//                    'code_sleeve',
//                    'сode_silhouette',
//                    'code_belt',
//                    'code_buckle',
//                    'code_landing_line',
//                    'security_code',
//                    'code_slots',
//                    'code_season',
//                    'features_of_the_model',
//                    'product_description_on_the_site',
//                    'labeling',
//                    'nomenclature_codes_similar_products',
//                    'nomenclatural_codes',
//                    'novelty_of_the_season',
//                    'detail_page_url'
                ],
                'required',// обязательно для заполнения
                'message' => 'Поле обязательно для заполнения'
            ],
            [
                [
//                    'commodity_group_code',
//                    'brand_code',
//                    'code_sex',
//                    'code_pattern',
//                    'code_color',
//                    'code_composition_top',
//                    'code_filler_composition',
//                    'code_composition_lining',
//                    'code_insulation',
//                    'code_collar',
//                    'code_clasp',
//                    'code_number_of_buttons',
//                    'code_pockets',
//                    'code_hood',
//                    'code_length',
//                    'code_width',
//                    'code_sleeve',
//                    'сode_silhouette',
//                    'code_belt',
//                    'code_buckle',
//                    'code_landing_line',
//                    'security_code',
//                    'code_slots',
//                    'code_season',
//                    'display'
                ],
                'integer',// переменная может быть только целочисленным числом
                'message' => 'В поле должны быть только цифры'
            ],
            [
                [
//                    'product_description_on_the_site',
//                    'detail_page_url'
                ],
                'string',// переменная может быть только строкой
                'message' => 'Поле должно содержать только string1'
            ],
            [
                [
//                    'article_of_manufacture'
                ],
                'string',// переменная может быть только строкой
                'max' => 32,
                'message' => 'Поле должно содержать только string2'
            ],
            [
                [
//                    'nomenclature_name',
//                    'features_of_the_model'
                ],
                'string',// переменная может быть только строкой
                'max' => 64,
                'message' => 'Поле должно содержать только string3'
            ],
            [
                [
//                    'labeling',
//                    'nomenclature_codes_similar_products',
//                    'nomenclatural_codes'
                ],
                'string',// переменная может быть только строкой
                'max' => 128,
                'message' => 'Поле должно содержать только string4'
            ],
            [
                [
//                    'novelty_of_the_season'
                ],
                'string',// переменная может быть только строкой
                'max' => 10,
                'message' => 'Поле должно содержать только string5'
            ],
            // валидация по нескольким полям
            [
                ['article_of_manufacture', 'brand_code'], 'unique',
                'targetAttribute' => ['article_of_manufacture', 'brand_code'],
                'message' => 'Обнаружено совпадение'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_of_manufacture' => 'Артикул производителя',
            'nomenclature_name' => 'Наименование номенклатуры',
            'commodity_group_code' => 'Товарная группа',
            'brand_code' => 'Бренд',
            'code_sex' => 'Пол',
            'code_pattern' => 'Рисунок/узор',
            'code_color' => 'Цвет',
            'code_composition_top' => 'Состав верх',
            'code_filler_composition' => 'Состав наполнитель',
            'code_composition_lining' => 'Состав подклад',
            'code_insulation' => 'Утеплитель',
            'code_collar' => 'Ворот',
            'code_clasp' => 'Застежка',
            'code_number_of_buttons' => 'Число пуговиц',
            'code_pockets' => 'Карманы',
            'code_hood' => 'Капюшон',
            'code_length' => 'Длина',
            'code_width' => 'Ширина',
            'code_sleeve' => 'Рукав',
            'code_silhouette' => 'Силуэт',
            'code_belt' => 'Пояс',
            'code_buckle' => 'Пряжка',
            'code_landing_line' => 'Линия посадки',
            'security_code' => 'Защипы',
            'code_slots' => 'Шлицы',
            'code_season' => 'Сезон',
            'features_of_the_model' => 'Особенности модели',
            'product_description_on_the_site' => 'Описание товара на сайте',
            'labeling' => 'Надпись на этикетке',
            'nomenclature_codes_similar_products' => 'Номенклатурные коды похожие товары',
            'nomenclatural_codes' => 'Номенклатурные коды подборка',
            'novelty_of_the_season' => 'Признак новинка сезона',
            'display' => 'Признак отображать на сайте',
            'detail_page_url' => 'Адрес страницы товара на сайте',
        ];
    }
}
