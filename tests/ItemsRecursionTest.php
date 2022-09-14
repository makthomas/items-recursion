<?php

use PHPUnit\Framework\TestCase;
use makthomas\ItemsRecursion\ItemsRecursion;

class ItemsRecurionTest extends TestCase
{
    private $items;

    protected function setUp() : void
    {
        $this->items = new ItemsRecursion(__DIR__.'/stubs/items.json');
    }

    /** @test */
    public function it_loads_items()
    {
        $items = ItemsRecursion::load(__DIR__.'/stubs/items.json');
        $expect = file_get_contents(__DIR__.'/stubs/items.json');

        $this->assertEquals($items, $expect);
    }

    /** @test */
    public function it_get_items()
    {
        $items = $this->items->getItems();
        $this->assertCount(5, $items);
    }

    /** @test */
    public function it_get_specific_item()
    {
        $item = $this->items->getItem(1);
        $this->assertEquals('Item 1', $item->item);
    }

    /** @test */
    public function it_get_items_with_specific_parentId()
    {
        $items = $this->items->getItemsByParentId(1);
        $this->assertCount(2, $items);
    }

    /** @test */
    public function it_generates_final_array()
    {
        $finalArray = [];
        $finalArray = $this->items->generate($finalArray, $finalArray);

        $this->assertEquals('Item 1', $finalArray[1]['item']);
        $this->assertEquals('Item 2', $finalArray[2]['item']);
        $this->assertEquals('Item 3', $finalArray[1]['children'][3]['item']);
        $this->assertEquals('Item 4', $finalArray[1]['children'][4]['item']);
        $this->assertEquals('Item 5', $finalArray[1]['children'][3]['children'][5]['item']);
    }
}
