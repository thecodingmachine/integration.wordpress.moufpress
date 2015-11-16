<?php

namespace Mouf\Integration\Wordpress\Moufpress;

use Mouf\Html\HtmlElement\HtmlBlock;
use Mouf\Html\Template\BaseTemplate\BaseTemplate;

/**
 * This class represents the template currently configured in Wordpress.
 * It always comes with a "wordpressTemplate" instance and you should call the "toHtml" method to trigger a rendering of the
 * template in Wordpress.
 *
 * @author David Négrier
 */
class WordpressTemplate extends BaseTemplate
{
    /**
     * True if the toHtml method has been called, false otherwise.
     *
     * @var bool
     */
    protected $displayTriggered = false;

    public function getContentBlock()
    {
        return $this->content;
    }

    /**
     * The content of the page is represented by this object.
     * Using this object, you can add content to your page.
     *
     * @param HtmlBlock $value
     */
    public function setContentBlock($value)
    {
        $this->content = $value;
    }

    /**
     * Tells wordpress that the content should be rendered into the theme.
     * This does actually not call any real rendering.
     * It just sets a flag to inform Drupal that rendering should be performed (instead of going the Ajax way).
     *
     * The toHtml() name is kept so that we can keep the same code between Splash and Druplash.
     */
    public function toHtml()
    {
        $this->displayTriggered = true;
        $this->getDefaultRenderer()->setTemplateRenderer($this->getTemplateRenderer());
    }

    /**
     * Returns true if the toHtml method has been called, false otherwise.
     *
     * @return bool
     */
    public function isDisplayTriggered()
    {
        return $this->displayTriggered;
    }
}
