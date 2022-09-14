<?php

namespace makthomas\ItemsRecursion;

class ItemsRecursion
{
    public $items;
    public $path;

    function __construct($path)
    {
        $this->path = $path;
        $this->items = $this->getItems($path);
    }

    /**
     * loading file with items
     * @param  string $path file path
     * @return string
     */
    public static function load(string $path) :string
    {
        return file_get_contents($path);
    }

    /**
     * fetch all items
     * @return array data
    */
    public function getItems() :array
    {
        $items = self::load($this->path);
        return json_decode($items);
    }

    /**
     * fetch specific item
     * @param  int    $id item
     * @return object   item
     */
    public function getItem(int $id) :object
    {
        $items = $this->getItems();
        foreach($items as $item){
            if($item->id == $id)
                return $item;
        }
        return null;
    }

    /**
     * fetch items with specific parant_id
     * @param  int    $id parent_id
     * @return array     items with parent_id
     */
    public function getItemsByParentId(?int $id) : array
    {
        $data = [];
        $items = $this->getItems();
        foreach($items as $item){
            if($item->parent_id == $id)
                $data[] = $item;
        }
        return $data;
    }

    /**
     * generates array with items using recursion
     * @param  array    &$finalArray      generated array
     * @param  array    &$parentLevel     parent level
     * @param  int|null $parentItemId parent item id
     * @return array
     */
    public function generate(array &$finalArray, ?array &$parentLevel, ?int $parentItemId = null) : array
    {
        $items = $this->getItemsByParentId($parentItemId);
        foreach($items as $item){
            $parentLevel[$item->id] = $this->createItem($item);
            $this->generate($finalArray, $parentLevel[$item->id]['children'], $item->id);
        }

        return $finalArray;
    }

    /**
     * item format
     * @param  object $item item
     * @return array
     */
    public function createItem(object $item) : array
    {
        return [
            'id' => $item->id,
            'item' => $item->item,
            'parent_id' =>  $item->parent_id,
            'children' => null
        ];
    }

}
