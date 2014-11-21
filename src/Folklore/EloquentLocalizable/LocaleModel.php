<?php namespace Folklore\EloquentLocalizable;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LocaleModel extends Eloquent {
    
    public function newCollection(array $models = array())
    {
        return new LocalesCollection($models);
    }
    
}
