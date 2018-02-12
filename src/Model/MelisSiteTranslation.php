<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisSiteTranslation\Model;

class MelisSiteTranslation
{
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}