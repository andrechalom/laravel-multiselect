<?php

/*
 * This file is part of the Laravel Multiselect package.
 *
 * (c) Andre Chalom <andrechalom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AndreChalom\LaravelMultiselect;
use Illuminate\Support\HtmlString;

class Multiselect
{

    // The select elements registered, to create the JS
    protected $registered = [];

    /**
     * Create a new Multiselect instance
     */
    public function __construct()
    {
        // constructor body. Do we need to register the session / request here?
    }

    protected function register($name) {
        $this->registered[] = $name;
    }

  /**
     * Build a single attribute element.
   * Cloned from LaravelCollective\html
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        // This will convert HTML attributes such as "required" to a correct
        // form instead of using incorrect numerics.
        if (is_numeric($key)) {
            return $value;
        }
        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key != 'value') {
            return $value ? $key : '';
        }
        if (! is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }

  /**
   * Build an HTML attribute string from an array.
   * Cloned from LaravelCollective\html
   *
   * @param array $attributes
   *
   * @return string
   */
    protected function attributes($attributes)
    {
        $html = [];
        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);
            if (! is_null($element)) {
                $html[] = $element;
            }
        }
        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }
     /**
     * Transform the string to an Html serializable object
     *
     * @param $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }

    /** Generates a single option for the select dropdown */
    protected function option($display, $value, array $attributes = [])
    {
        $options = ['value' => $value ] + $attributes;
        return $this->toHtmlString('<option' . $this->attributes($options) . '>' . e($display) . '</option>');
    }

    /**
     * Create the multi-select select box field and optionally the already selected span field.
     * This method interface mimicks LaravelCollective\html select method.
     *
     * @param  string $name The name of the select element. Will be used by the JS to add onChange handler
     * @param  array  $list A Laravel collection or list of elements
     * @param  string $selected A laravel collection or list of elements
     * @param  array  $selectAttributes
     * @param  array  $optionsAttributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function select(
        $name,
        $list = [],
        $selected = [],
        array $selectAttributes = [],
        array $optionsAttributes = [],
        array $spanAttributes = [],
        array $spanElementsAttributes = [],
        $selectOnly = false
    ) {
        // Register this name in order to create the JS code
        $this->register($name);

        // Forces the ID attribute
        $selectAttributes['id'] = $name . "-ms";

        // We will concatenate the span html unless $selectOnly is passed as false
        $spanHtml = $selectOnly ? "" : $this->span($name, $list, $selected, $spanAttributes, $spanElementsAttributes);

        // Here, we generate the list of options
        $html = [];
        if (isset($selectAttributes['placeholder'])) {
            $html[] = $this->option($selectAttributes['placeholder'], '');
            unset($selectAttributes['placeholder']);
        } else {
            $html[] = $this->option('&nbsp;', '');
        }
        foreach ($list as $value => $display) {
            $optionAttributes = isset($optionsAttributes[$value]) ? $optionsAttributes[$value] : [];
            $html[] = $this->option($display, $value, $optionAttributes);
        }
        $list = implode('', $html);

        $selectAttributes = $this->attributes($selectAttributes);
        return $spanHtml . "<select{$selectAttributes}>{$list}</select>";
    }
    /**
     * Create the multi-select span with the already selected values.
     * This method is called from Multiselect::select by default, but you may wish to call it elsewhere in your html.
     * If you call it explicitly, remember to pass $selectOnly = false to the select Multiselect::select method.
     *
     * @param  string $name The name of the select element. Will be used by the JS to add elements under this
     * @param  array  $list A Laravel collection or list of elements
     * @param  string $selected A laravel collection or list of elements
     * @param  array  $selectAttributes
     * @param  array  $optionsAttributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function span(
        $name,
        $list = [],
        $selected = [],
        array $spanAttributes = [],
        array $spanElementsAttributes = [],
        $selectOnly = false
    ) {
        // Forces the ID attribute
        $spanAttributes['id'] = $name . "-span";

        // Here, we generate the list of already selected options IF THERE'S NO "OLD"
        $html = [];
        foreach ($selected as $value) {
            $elementAttributes = isset($spanElementsAttributes[$value]) ? $spanElementsAttributes[$value] : [];
            $html[] = $this->spanElement($name, $list[$value], $value, $elementAttributes);
        }

        $spanAttributes = $this->attributes($spanAttributes);
        $list = implode('', $html);
        return "<span{$spanAttributes}>{$list}</span>";
    }

    // generates a single span with relevant options and code
    public function spanElement($name, $display, $value, $attributes) {
        $options = ['onClick' => '$(this).remove();' ] + $attributes;
        return $this->toHtmlString(
            '<span' . $this->attributes($options) . '>'.
            '<input type="hidden" name="' . $name . '[]" value="' . $value . '>'
            . e($display) . 
            '</span>');
    }

    /** 
     * Create the jQuery code to add the selected values to the display and to remove the selected values on click
     * Must be called AFTER all instances of Multiselect::select
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function js() {
        echo implode(",",$this->registered);
    }

}
