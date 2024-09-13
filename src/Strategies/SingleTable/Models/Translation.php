<?php

namespace Nevadskiy\Translatable\Strategies\SingleTable\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Nevadskiy\Translatable\Strategies\SingleTable\HasTranslations;
use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;

use function is_array;

/**
 * @property int id
 * @property string translatable_type
 * @property int translatable_id
 * @property string translatable_attribute
 * @property-read Model|HasTranslations translatable
 * @property string value
 * @property string locale
 * @property Carbon updated_at
 * @property Carbon created_at
 */
class Translation extends Model
{
    use Filterable, Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'translatable_attribute',
        'locale',
        'value',
    ];

    /**
     * Get the translatable entity.
     */
    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope translations by the given locale.
     *
     * @param string|array $locale
     */
    protected function scopeForLocale(Builder $query, $locale): Builder
    {
        if (is_array($locale)) {
            return $query->whereIn('locale', $locale);
        }

        return $query->where('locale', $locale);
    }

    /**
     * Scope translations by the given attribute.
     */
    public function scopeForAttribute(Builder $query, string $attribute): Builder
    {
        return $query->where('translatable_attribute', $attribute);
    }
}
