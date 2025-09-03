<?php 

namespace webpo;

use webpo\Registry;
class Controller
{
    protected function render($path, $data = [])
    {

        $registryData = Registry::all();
        $data = array_merge($registryData, $data);

        extract($data);
        include "Views/$path";
    }
}
