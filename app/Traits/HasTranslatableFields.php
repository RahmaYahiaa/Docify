<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasTranslatableFields
{
    /**
     * Get the translated value for a field
     *
     * @param  Model  $model  The model instance
     * @param  string  $field  The field name to translate
     * @return mixed
     */
    protected function getTranslatableField(Model $model, string $field)
    {
        $withTranslations = request()->boolean('with_translations', true);

        if ($withTranslations) {
            return $model->getTranslations($field);
        }

        return $model->getTranslation($field, app()->getLocale());
    }

    /**
     * Check if translations are requested
     */
    protected function shouldIncludeTranslations(): bool
    {
        return request()->boolean('with_translations', true);
    }

    /**
     * Get all translatable fields with their values
     *
     * @param  mixed  $model  The model instance
     * @param  array  $fields  Array of translatable field names
     */
    protected function getTranslatableFields(Model $model, array $fields): array
    {
        $result = [];
        $withTranslations = $this->shouldIncludeTranslations();

        foreach ($fields as $field) {
            if ($withTranslations) {
                $result[$field] = $model->getTranslations($field);
            } else {
                $result[$field] = $model->getTranslation($field, app()->getLocale());
            }
        }

        return $result;
    }
}
