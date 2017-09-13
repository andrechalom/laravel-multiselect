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
use Illuminate\Http\Request;
use Illuminate\Contracts\Session\Session;

class Multiselect
{
    protected $session;
    protected $request;

    /**
     * Create a new Multiselect instance. This method expects both a session and a request arguments, in order to be able to look up "old" values
     */
    public function __construct(Session $session = null, Request $request = null)
    {
        $this->session = $session;
        $this->request = $request;
    }

    /** Gets the array of values that must be set on page load. In order, these are:
     * 1 - "old" session values
     * 2 - $request values
     * 3 - default values, passed as $selected
     * 4 - empty array
     *
     * @param string $key
     * @param array $value
     *
     * @return array
     */
    protected function getValueArray($name, $value = null)
    {
        // Looks for the values in "old"
        if (! $this->oldInputIsEmpty()) {
            return $this->session->getOldInput($name);
        }
        // Looks for the values in the Request
        if (isset($this->request) and $this->request->input($name)) {
            return $this->request->input($name);
        }
        // Returns the default value
        return empty($value) ? [] : $value;
    }

    public function oldInputIsEmpty()
    {
        return (is_null($this->session) or count($this->session->getOldInput()) == 0);
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

    /**
     * Generates a single option for the select dropdown
     *
     * @param string $display
     * @param string $value
     * @param array $attributes
     *
     * @return \Illuminate\Support\HtmlString
     * */
    protected function option($display, $value, array $attributes = [])
    {
        $options = ['value' => $value ] + $attributes;

        return $this->toHtmlString('<option' . $this->attributes($options) . '>' . e($display) . '</option>');
    }

    /**
     * Create the multi-select select box field and optionally the already selected span field.
     * This method interface mimicks LaravelCollective\html select method.
     *
     * @param  string  $name The name of the select element. Will be used by the JS to add hidden inputs
     * @param  array   $list A Laravel collection or list of key => values
     * @param  array   $selected A laravel collection or list of keys
     * @param  array   $selectAttributes
     * @param  array   $optionsAttributes
     * @param  array   $spanAttributes
     * @param  boolean $selectOnly
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
        $selectOnly = false
    ) {
        // Forces the ID attribute
        $selectAttributes['id'] = $name . "-ms";
        if (!isset($selectAttributes['class'])) {
            $selectAttributes['class'] = "multiselect";
        }

        // We will concatenate the span html unless $selectOnly is passed as false
        $spanHtml = $selectOnly ? "" : $this->span($name, $list, $selected, $spanAttributes);

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

        return $this->toHtmlString($spanHtml . "<select{$selectAttributes}>{$list}</select>");
    }

    /**
     * Create the multi-select span with the already selected values.
     * This method is called from Multiselect::select by default, but you may wish to call it elsewhere in your html.
     * If you call it explicitly, remember to pass $selectOnly = false to the select Multiselect::select method.
     *
     * @param  string $name The name of the select element. Will be used by the JS to add elements under this
     * @param  array  $list A Laravel collection or list of elements
     * @param  array  $default A laravel collection or list of elements
     * @param  array  $spanAttributes
     * @param  bool   $strict If true, will throw a Undefined Offset exception in case a value in $default is not present in $list. If false, the item is generated with an "Undefined" label
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function span(
        $name,
        $list = [],
        $default = [],
        array $spanAttributes = [],
        $strict = false
    ) {
        // Forces the ID attribute
        $spanAttributes['id'] = $name . "-span";

        // Here, we generate the list of already selected options considering "old" values
        $html = [];
        $selected = $this->getValueArray($name, $default);
        foreach ($selected as $value) {
            // This block avoids Undefined Offsets
            if (isset($list[$value])) {
                $html[] = $this->spanElement($name, $list[$value], $value);
            } else { // Undefined offset! What to do now depends on the value of parameter $strict
                if ($strict) {
                    throw new MultiselectException("Undefined offset $value!");
                } else {
                    $html[] = $this->spanElement($name, "Undefined", $value);
                }
            }
        }

        $spanAttributes = $this->attributes($spanAttributes);
        $list = implode('', $html);

        return $this->toHtmlString("<span{$spanAttributes}>{$list}</span>");
    }

    /**
     * Generates a single span with pre-selected options with relevant options
     *
     * @param string $name
     * @param string $display
     * @param string $value
     *
     * @return \Illuminate\Support\HtmlString
     * */
    public function spanElement($name, $display, $value)
    {
        $options = ['onClick' => '$(this).remove();', 'class' => 'multiselector' ];

        return $this->toHtmlString(
            '<span' . $this->attributes($options) . '>'.
            '<input type="hidden" name="' . $name . '[]" value="' . $value . '">'
            . e($display) .
            '</span>');
    }
}
