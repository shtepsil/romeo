<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 13.05.2019
 * Time: 19:42
 */

namespace frontend\components;

use frontend\controllers\MainController as d;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use Yii;

class CustomBreadcrumbs extends Breadcrumbs
{

    /**
     * Renders the widget.
     */
    public function run()
    {
        if (empty($this->links)) {
            return;
        }
        $links = [];
        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                'label' => Yii::t('yii', 'Home'),
                'url' => Yii::$app->homeUrl,
            ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }

        echo Html::tag($this->tag, implode('', $links), $this->options);
    }

    /**
     * Renders a single breadcrumb item.
     * @param array $link the link to be rendered. It must contain the "label" element. The "url" element is optional.
     * @param string $template the template to be used to rendered the link. The token "{link}" will be replaced by the link.
     * @return string the rendering result
     * @throws InvalidConfigException if `$link` does not have "label" element.
     */
    protected function renderItem($link, $template)
    {
        $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
        $serial_number = '0';
        if (array_key_exists('label', $link)) {
            $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }
        if (isset($link['template'])) {
            $template = $link['template'];
        }
        if(isset($link['coption'])){
            if(isset($link['coption']['serial_number'])) $serial_number = $link['coption']['serial_number'];
            else $serial_number = '0';

            // Больше этот элемент не нужен
            unset($link['coption']);
        }
        if (isset($link['url'])) {
            $options = $link;
            unset($options['template'], $options['label'], $options['url']);

            $label = '<span itemprop="name">'.
                $label.
                '</span><meta itemprop="position" content="'.$serial_number.'">';

            $link = Html::a($label, $link['url'], $options);
        } else {
            $link = '<span itemprop="name">'.
                $label.
                '</span><meta itemprop="position" content="'.$serial_number.'">';
        }

        return strtr($template, ['{link}' => $link]);
    }
}