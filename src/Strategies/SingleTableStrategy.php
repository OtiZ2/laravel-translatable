<?php

namespace Nevadskiy\Translatable\Strategies;

use Illuminate\Database\Eloquent\Model;
use Nevadskiy\Translatable\HasTranslations;
use Nevadskiy\Translatable\Models\Translation;

class SingleTableStrategy implements TranslatorStrategy
{
    /**
     * The translatable model instance.
     *
     * @var Model|HasTranslations
     */
    private $model;

    /**
     * A list of pending translation insertions.
     *
     * @var array
     */
    private $pendingTranslations = [];

    /**
     * Make a new strategy instance.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function get(string $attribute, string $locale)
    {
        if (isset($this->pendingTranslations[$locale][$attribute])) {
            return $this->pendingTranslations[$locale][$attribute];
        }

        return $this->model->translations->first(static function (Translation $translation) use ($attribute, $locale) {
            return $translation->translatable_attribute === $attribute
                && $translation->locale === $locale;
        })->value ?? null;
    }

    /**
     * @inheritdoc
     */
    public function set(string $attribute, $value, string $locale): void
    {
        if ($this->isFallbackLocale($locale)) {
            $this->model->setOriginalAttribute($attribute, $value);
        } else {
            $this->pendingTranslations[$locale][$attribute] = $this->model->withAttributeSetter($attribute, $value);
        }
    }

    /**
     * Determine if the given locale is fallback locale.
     */
    private function isFallbackLocale(string $locale): bool
    {
        return $locale === 'en';
    }

    public function save(): void
    {
        foreach ($this->pullPendingTranslations() as $locale => $attributes) {
            foreach (array_filter($attributes) as $attribute => $value) {
                $this->model->translations()->updateOrCreate([
                    'translatable_attribute' => $attribute,
                    'locale' => $locale,
                ], [
                    'value' => $value,
                ]);
            }
        }
    }

    private function pullPendingTranslations(): array
    {
        $pendingTranslations = $this->pendingTranslations;

        $this->pendingTranslations = [];

        return $pendingTranslations;
    }
}
