<?php

namespace Nevadskiy\Translatable\Tests\Support\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Nevadskiy\Translatable\HasTranslations;

/**
 * @property int id
 * @property string title
 * @property string description
 * @property array|null content
 * @property int version
 * @property string description_short
 * @property Collection translations
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Book extends Model
{
    use HasTranslations;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that can be translatable.
     *
     * @var array
     */
    protected $translatable = [
        'title',
        'description',
        'content',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Get description short attribute.
     */
    public function getDescriptionShortAttribute(): string
    {
        return Str::limit($this->description, 3);
    }

    /**
     * Get title attribute.
     */
    public function getTitleAttribute(string $title): string
    {
        return Str::ucfirst($title);
    }

    /**
     * Set title attribute.
     */
    public function setTitleAttribute(string $title): Book
    {
        $this->attributes['title'] = Str::limit($title, 30);

        return $this;
    }
}
