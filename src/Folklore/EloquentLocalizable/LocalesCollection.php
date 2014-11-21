<?php namespace Folklore\EloquentLocalizable;

use Illuminate\Database\Eloquent\Collection;

class LocalesCollection extends Collection {
    
    /*
    *
    * Get a specific locale
    *
    */
    public function __get($locale)
    {
        
        return $this->first(function($key,$model) use ($locale) {
            return $model->locale === $locale;
        });
        
    }
    
}
