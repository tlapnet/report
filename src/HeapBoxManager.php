<?php

namespace Tlapnet\Report;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\HeapBox\Heapable;
use Tlapnet\Report\HeapBox\HeapBox;
use Tlapnet\Report\Utils\Suggestions;

class HeapBoxManager
{

    /** @var HeapBox[]|Heapable[] */
    private $boxes = [];

    /**
     * @param HeapBox $box
     */
    public function addHeapBox(HeapBox $box)
    {
        $this->boxes[] = $box;
    }

    /**
     * @return HeapBox[]
     */
    public function getHeapBoxes()
    {
        return $this->boxes;
    }

    /**
     * @param string $uid
     * @return HeapBox
     */
    public function getHeapBox($uid)
    {
        foreach ($this->boxes as $box) {
            if ($box->getUid() == $uid) {
                return $box;
            }
        }

        $hint = Suggestions::getSuggestion(array_map(function (HeapBox $box) {
            return $box->getUid();
        }, $this->boxes), $uid);

        throw new InvalidStateException("HeapBox '$uid' not found" . ($hint ? ", did you mean '$hint'?" : '.'));
    }

    /**
     * @param string $uid
     * @return bool
     */
    public function hasHeapBox($uid)
    {
        foreach ($this->boxes as $box) {
            if ($box->getUid() == $uid) {
                return TRUE;
            }
        }

        return FALSE;
    }

}
