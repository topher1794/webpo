<?php 

namespace stockalignment;

class Controller
{
    protected function render($path, $data = [])
    {
        extract($data);
        include "Views/$path";
    }
}
