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

use PHPUnit\Framework\TestCase;
use AndreChalom\LaravelMultiselect\Multiselect;

class BasicTest extends TestCase
{
    protected $basicList = [
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
    ];
    public function testSpanEmpty()
    {
        $select = new Multiselect();
        $element = $select->span('name');
        $this->assertSame('<span id="name-span"></span>', $element);
    }
    public function testSpanClass()
    {
        $select = new Multiselect();
        $element = $select->span('name', [], [], ['class' => 'prettySpan']);
        $this->assertSame('<span class="prettySpan" id="name-span"></span>', $element);
    }
    public function testSpanList()
    {
        $select = new Multiselect();
        $element = $select->span('name', $this->basicList, [0]);
        $this->assertSame('<span id="name-span"><span onClick="$(this).remove();"><input type="hidden" name="name[]" value="0>Zero</span></span>', $element);
    }
    public function testSelectEmpty()
    {
        $select = new Multiselect();
        $element = $select->select('name', [], [], [], [], [], [] , true);
        $this->assertSame('<select id="name-ms"><option value="">&nbsp;</option></select>', $element);
    }
    public function testSelectClass()
    {
        $select = new Multiselect();
        $element = $select->select('name', [], [], ['class' => 'prettySelect'], [], [], [] , true);
        $this->assertSame('<select class="prettySelect" id="name-ms"><option value="">&nbsp;</option></select>', $element);
    }
    public function testSelectList()
    {
        $select = new Multiselect();
        $element = $select->select('name', $this->basicList, [], [], [], [], [] , true);
        $this->assertSame('<select id="name-ms"><option value="">&nbsp;</option><option value="0">Zero</option><option value="1">One</option><option value="2">Two</option></select>', $element);
    }
    public function testSelectWithSpan()
    {
        $select = new Multiselect();
        $element = $select->select('name');
        $this->assertSame('<span id="name-span"></span><select id="name-ms"><option value="">&nbsp;</option></select>', $element);
    }
}
