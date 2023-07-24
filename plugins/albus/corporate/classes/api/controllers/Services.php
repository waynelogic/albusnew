<?php namespace Albus\Corporate\Classes\Api\Controllers;

use Albus\Corporate\Models\ServiceCategory;
use Albus\Corporate\Models\Service;
use Albus\Corporate\Classes\Api\Controllers\AbstractController;
use Input;

class Services extends AbstractController {
    public function category($method) : string
    {
        $q = ServiceCategory::query();

        if (isset($this->params['id'])) {
            $q->whereId($this->params['id']);
        }
        if (isset($this->params['slug'])) {
            $q->whereSlug($this->params['slug']);
        }
        if ($method === 'list') {
            $this->json_data = $q->get();
        } elseif ($method === 'item') {
            $this->json_data = $q->first();
        }
        return $this->json_data;
    }

    public function service($method) : string
    {
        $q = Service::with('cover', 'background', 'image');
        if (isset($this->params['id'])) {
            $q->whereId($this->params['id']);
        }
        if (isset($this->params['slug'])) {
            $q->whereSlug($this->params['slug']);
        }
        if (isset($this->params['category'])) {
            $q->where('category_id', $this->params['category']);
        }
        if ($method === 'list') {
            $this->json_data = $q->get();
        } elseif ($method === 'item') {
            $this->json_data = $q->first();
        }

        return $this->json_data;
    }
}