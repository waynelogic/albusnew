<?php namespace Albus\Corporate\Classes\Api\Controllers;

use Albus\Corporate\Models\Banner;

class Slider {
    public function all()
    {
        return Banner::with(['background', 'image'])->get();
    }
}