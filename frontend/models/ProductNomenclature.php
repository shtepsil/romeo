<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "product_nomenclature".
 *
 * @property int $id идентифицирует бренд и артикул производителя (наименование номенклатуры) товара, значение присваивается автоматически по возрастанию
 * @property string $article_of_manufacture артикул производителя - вводится пользователем в веб интерфейсе в соответствии с документом контрагента
 * @property string $nomenclature_name наименование номенклатуры - вводится пользователем в веб интерфейсе включая указание товарной группы, наименование бренда, и артикула поставщика
 * @property string $commodity_group_code код товарной группы - справочник
 * @property string $brand_code код бренда - справочник
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
 * @property int $code_silhouette код сидует - справочник
 * @property int $cufflink_code код запонки - справочник
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
            [['article_of_manufacture', 'nomenclature_name', 'commodity_group_code', 'brand_code', 'code_sex', 'code_pattern', 'code_color', 'code_composition_top', 'code_filler_composition', 'code_composition_lining', 'code_insulation', 'code_collar', 'code_clasp', 'code_number_of_buttons', 'code_pockets', 'code_hood', 'code_length', 'code_width', 'code_sleeve', 'code_silhouette', 'cufflink_code', 'code_belt', 'code_buckle', 'code_landing_line', 'security_code', 'code_slots', 'code_season', 'features_of_the_model', 'product_description_on_the_site', 'labeling', 'nomenclature_codes_similar_products', 'nomenclatural_codes', 'novelty_of_the_season'], 'required'],
            [['nomenclature_name', 'product_description_on_the_site', 'detail_page_url'], 'string'],
            [['commodity_group_code', 'brand_code', 'code_sex', 'code_pattern', 'code_color', 'code_composition_top', 'code_filler_composition', 'code_composition_lining', 'code_insulation', 'code_collar', 'code_clasp', 'code_number_of_buttons', 'code_pockets', 'code_hood', 'code_length', 'code_width', 'code_sleeve', 'code_silhouette', 'cufflink_code', 'code_belt', 'code_buckle', 'code_landing_line', 'security_code', 'code_slots', 'code_season', 'display'], 'integer'],
            [['article_of_manufacture'], 'string', 'max' => 32],
            [['features_of_the_model'], 'string', 'max' => 64],
            [['labeling', 'nomenclature_codes_similar_products', 'nomenclatural_codes'], 'string', 'max' => 128],
            [['novelty_of_the_season'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'идентифицирует бренд и артикул производителя (наименование номенклатуры) товара, значение присваивается автоматически по возрастанию',
            'article_of_manufacture' => 'артикул производителя - вводится пользователем в веб интерфейсе в соответствии с документом контрагента',
            'nomenclature_name' => 'наименование номенклатуры - вводится пользователем в веб интерфейсе включая указание товарной группы, наименование бренда, и артикула поставщика',
            'commodity_group_code' => 'код товарной группы - справочник',
            'brand_code' => 'код бренда - справочник',
            'code_sex' => 'код пол - справочник',
            'code_pattern' => 'код рисунок/узор - справочник',
            'code_color' => 'код цвет - справочник',
            'code_composition_top' => 'код состав верх - справочник',
            'code_filler_composition' => 'код состав наполнитель - справочник',
            'code_composition_lining' => 'код состав подклад - справочник',
            'code_insulation' => 'код утеплитель - справочник',
            'code_collar' => 'код ворот - справочник',
            'code_clasp' => 'код застежка - справочник',
            'code_number_of_buttons' => 'код число пуговиц - справочник',
            'code_pockets' => 'код карманы - справочник',
            'code_hood' => 'код капюшон - справочник',
            'code_length' => 'код длина - справочник',
            'code_width' => 'код ширина - справочник',
            'code_sleeve' => 'код рукав - справочник',
            'code_silhouette' => 'код силует - справочник',
            'cufflink_code' => 'код запонки - справочник',
            'code_belt' => 'код пояс - справочник',
            'code_buckle' => 'код пряжка - справочник',
            'code_landing_line' => 'код линия посадки - справочник',
            'security_code' => 'код защипы - справочник',
            'code_slots' => 'код шлицы - справочник',
            'code_season' => 'код сезон - справочник',
            'features_of_the_model' => 'особенности модели - вводится пользователем в веб интерфейсе',
            'product_description_on_the_site' => 'описание товара на сайте - вводится пользователем в веб интерфейсе',
            'labeling' => 'надпись на этикетке - вводится пользователем в веб интерфейсе',
            'nomenclature_codes_similar_products' => 'номенклатурные коды похожие товары - вводится пользователем в веб интерфейсе',
            'nomenclatural_codes' => 'номенклатурные коды подборка - вводится пользователем в веб интерфейсе',
            'novelty_of_the_season' => 'признак новинка сезона - вводится пользователем в веб интерфейсе',
            'display' => 'признак отображать на сайте - по умолчаниюзначение \"да\", может быть изменено обработкой \"отображение на сайте\"',
            'detail_page_url' => 'адрес страницы товара на сайте - генерируется автоматически при создании номенклатурного кода',
        ];
    }
}
