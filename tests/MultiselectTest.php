<?php

/*
 * This file is part of the Laravel Multiselect package.
 *
 * (c) Andre Chalom <andrechalom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AndreChalom\LaravelMultiselect\Test;

use AndreChalom\LaravelMultiselect\Multiselect;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use AndreChalom\LaravelMultiselect\MultiselectException;

class MultiselectTest extends TestCase
{
    protected $basicList = [
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
    ];

    /////////////////////////////////////////////
    //  Tests for generating the span element  //
    /////////////////////////////////////////////
    public function testSpanEmpty()
    {
        $select = new Multiselect();
        $element = $select->span('name')->toHtml();
        $this->assertSame('<span id="name-span"></span>', $element);
    }

    public function testSpanClass()
    {
        $select = new Multiselect();
        $element = $select->span('name', [], [], ['class' => 'prettySpan'])->toHtml();
        $this->assertSame('<span class="prettySpan" id="name-span"></span>', $element);
    }

    public function testSpanList()
    {
        $select = new Multiselect();
        $element = $select->span('name', $this->basicList, [0])->toHtml();
        $this->assertSame('<span id="name-span"><span onClick="$(this).remove();" class="multiselector"><input type="hidden" name="name[]" value="0">Zero</span></span>', $element);
    }

    public function testSpanUndefined()
    {
        $select = new Multiselect();
        $element = $select->span('name', $this->basicList, [999])->toHtml();
        $this->assertSame('<span id="name-span"><span onClick="$(this).remove();" class="multiselector"><input type="hidden" name="name[]" value="999">Undefined</span></span>', $element);
    }

    public function testSpanStrict()
    {
        $this->expectException(MultiselectException::class);
        $select = new Multiselect();
        $element = $select->span('name', $this->basicList, [-1], [], true)->toHtml();
    }

    public function testSpanFromRequest()
    {
        $request = Request::create('/foo', 'GET', [
            'multiselect' => [0, 1],
        ]);
        $request = Request::createFromBase($request);
        $select = new Multiselect(null, $request);
        $element = $select->span('multiselect', $this->basicList, [2])->toHtml();
        $this->assertSame('<span id="multiselect-span"><span onClick="$(this).remove();" class="multiselector"><input type="hidden" name="multiselect[]" value="0">Zero</span><span onClick="$(this).remove();" class="multiselector"><input type="hidden" name="multiselect[]" value="1">One</span></span>', $element);
    }

    // TODO: how to test old values?

    /////////////////////////////////////////////
    // Tests for generating the select element //
    /////////////////////////////////////////////
    public function testSelectEmpty()
    {
        $select = new Multiselect();
        $element = $select->select('name', [], [], [], [], [], true)->toHtml();
        $this->assertSame('<select id="name-ms" class="multiselect"><option value="">&nbsp;</option></select>', $element);
    }

    public function testSelectClass()
    {
        $select = new Multiselect();
        $element = $select->select('name', [], [], ['class' => 'prettySelect'], [], [], true)->toHtml();
        $this->assertSame('<select class="prettySelect" id="name-ms"><option value="">&nbsp;</option></select>', $element);
    }

    public function testSelectList()
    {
        $select = new Multiselect();
        $element = $select->select('name', $this->basicList, [], [], [], [], true)->toHtml();
        $this->assertSame('<select id="name-ms" class="multiselect"><option value="">&nbsp;</option><option value="0">Zero</option><option value="1">One</option><option value="2">Two</option></select>', $element);
    }

    public function testSelectWithSpan()
    {
        $select = new Multiselect();
        $element = $select->select('name')->toHtml();
        $this->assertSame('<span id="name-span"></span><select id="name-ms" class="multiselect"><option value="">&nbsp;</option></select>', $element);
    }

    public function testSelectWithPlaceholder()
    {
        $select = new Multiselect();
        $element = $select->select('name', [], [], ['placeholder' => 'placeholder-text'], [], [], true)->toHtml();
        $this->assertSame('<select id="name-ms" class="multiselect"><option value="">placeholder-text</option></select>', $element);
    }

    /////////////////////////////////////////////////
    // Tests for generating the autocomplete input //
    /////////////////////////////////////////////////
    public function testInputEmpty()
    {
        $select = new Multiselect();
        $element = $select->autocomplete('name', [], [], [], [], true)->toHtml();
        $this->assertSame('<input type="text" id="name-ms" class="multiselect">', $element);
    }

    ///////////////////////////////////////////////////
    // Tests for generating the autocomplete scripts //
    ///////////////////////////////////////////////////
    public function testScriptsSimple()
    {
        $select = new Multiselect();
        $element = $select->scripts('name', 'http://url/', [])->toHtml();
        $this->assertSame('<script>$(document).ready(function() {$("#name-ms").lmsAutocomplete("http://url/", {});});</script>', $element);
    }

    public function testScriptsParams()
    {
        $select = new Multiselect();
        $element = $select->scripts('name', 'http://url/', ['minChars' => 5, 'showNoSuggestionNotice' => true])->toHtml();
        $this->assertSame('<script>$(document).ready(function() {$("#name-ms").lmsAutocomplete("http://url/", {"minChars":5,"showNoSuggestionNotice":true});});</script>', $element);
    }
}
