<?php namespace Folklore\EloquentLocalizable;


trait LocalizableTrait {

    /*
     *
     * Override this to point to the locale Eloquent Model
     *
     */
    protected function getLocaleModelClass()
    {
        return get_class($this).'Locale';
    }

    /*
     *
     * Relationships
     *
     */
    public function locales()
    {
        $localeClass = $this->getLocaleModelClass();

        return $this->hasMany($localeClass);
    }
    public function locale()
    {
        $localeClass = $this->getLocaleModelClass();

        return $this->hasOne($localeClass);
    }

    /*
     *
     * Scopes
     *
     */
    public function scopeWithLocale($query, $locale)
    {
       return $query->with(array('locale' => function($query) use ($locale)
       {
           $query->where('locale', '=', $locale);
       }));
    }

    /*
     *
     * Get Methods
     *
     */
    public function localeFromCode($locale) {

        $locales = $this->locales->first(function($key,$item) use ($locale)
        {
            return $item->locale === $locale;
        });

        return $locales;
    }

    /*
     *
     * Sync methods
     *
     */
    public function syncLocales($locales = array())
    {

        //Save locales
        if(is_array($locales) && sizeof($locales)) {

            $localeModelClass = $this->getLocaleModelClass();

            $ids = array();
            foreach($locales as $data)
            {
                $model = $this->locales()->where('locale',$data['locale'])->first();
                if(!$model)
                {
                    $model = new $localeModelClass();
                }
                $model->fill($data);
                $model->save();
                $this->locales()->save($model);
                $ids[] = $model->id;
            }

            $itemsToDelete = $this->locales()->whereNotIn('id',$ids)->get();
            foreach($itemsToDelete as $item) {
                $item->delete();
            }
        } else {
            foreach($this->locales as $item) {
                $item->delete();
            }
        }
    }

}
