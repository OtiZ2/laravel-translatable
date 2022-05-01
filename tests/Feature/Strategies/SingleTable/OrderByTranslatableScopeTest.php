<?php

namespace Nevadskiy\Translatable\Tests\Feature\Strategies\SingleTable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Nevadskiy\Translatable\Strategies\SingleTable\HasTranslations;
use Nevadskiy\Translatable\Tests\TestCase;

class OrderByTranslatableScopeTest extends TestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    /**
     * Set up the database schema.
     */
    private function createSchema(): void
    {
        $this->schema()->create('books', function (Blueprint $table) {
            $table->id();
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /** @test */
    public function it_orders_records_by_translatable_attribute_using_current_locale(): void
    {
        $book1 = new BookOrderByTranslatable();
        $book1->translator()->set('title', 'Son of the earth', 'en');
        $book1->translator()->set('title', 'Син землі', 'uk');
        $book1->save();

        $book2 = new BookOrderByTranslatable();
        $book2->translator()->set('title', 'The last prophet', 'en');
        $book2->translator()->set('title', 'Останній пророк', 'uk');
        $book2->save();

        $this->app->setLocale('uk');

        $records = BookOrderByTranslatable::query()->orderByTranslatable('title')->get();

        self::assertTrue($records[0]->is($book2));
        self::assertTrue($records[1]->is($book1));
    }

    /** @test */
    public function it_orders_records_by_translatable_attribute_in_descending_order_using_current_locale(): void
    {
        $book1 = new BookOrderByTranslatable();
        $book1->translator()->set('title', 'Son of the earth', 'en');
        $book1->translator()->set('title', 'Син землі', 'uk');
        $book1->save();

        $book2 = new BookOrderByTranslatable();
        $book2->translator()->set('title', 'The last prophet', 'en');
        $book2->translator()->set('title', 'Останній пророк', 'uk');
        $book2->save();

        $this->app->setLocale('uk');

        $records = BookOrderByTranslatable::query()->orderByTranslatable('title', 'desc')->get();

        self::assertTrue($records[0]->is($book1));
        self::assertTrue($records[1]->is($book2));
    }

    /** @test */
    public function it_orders_records_by_translatable_attribute_using_custom_locale(): void
    {
        $book1 = new BookOrderByTranslatable();
        $book1->translator()->set('title', 'Son of the earth', 'en');
        $book1->translator()->set('title', 'Син землі', 'uk');
        $book1->save();

        $book2 = new BookOrderByTranslatable();
        $book2->translator()->set('title', 'The last prophet', 'en');
        $book2->translator()->set('title', 'Останній пророк', 'uk');
        $book2->save();

        $records = BookOrderByTranslatable::query()->orderByTranslatable('title', 'asc', 'uk')->get();

        self::assertTrue($records[0]->is($book2));
        self::assertTrue($records[1]->is($book1));
    }

    /** @test */
    public function it_orders_records_by_translatable_attribute_using_fallback_locale(): void
    {
        $book1 = new BookOrderByTranslatable();
        $book1->title = 'The last prophet';
        $book1->save();

        $book2 = new BookOrderByTranslatable();
        $book2->title = 'Son of the earth';
        $book2->save();

        $records = BookOrderByTranslatable::query()
            ->orderByTranslatable('title', 'asc', $this->app->getFallbackLocale())
            ->get();

        self::assertTrue($records[0]->is($book2));
        self::assertTrue($records[1]->is($book1));
    }

    /** @test */
    public function it_does_not_override_original_attributes_with_joined_attributes_from_translatable_table(): void
    {
        $book = new BookOrderByTranslatable();
        $book->translator()->set('title', 'Son of the earth', 'en');
        $book->translator()->set('title', 'Син землі', 'uk');
        $book->value = 'Original value';
        $book->save();

        $this->app->setLocale('uk');

        $records = BookOrderByTranslatable::query()->orderByTranslatable('title', 'desc')->get();

        self::assertTrue($records[0]->is($book));
        self::assertEquals('Original value', $records[0]->value);
        self::assertNull($records[0]->translatable_attribute);
    }

    /** @test */
    public function it_can_select_only_specified_attributes_when_ordering_by_translatable_column(): void
    {
        $book1 = new BookOrderByTranslatable();
        $book1->translator()->set('title', 'Son of the earth', 'en');
        $book1->translator()->set('title', 'Син землі', 'uk');
        $book1->save();

        $book2 = new BookOrderByTranslatable();
        $book2->translator()->set('title', 'The last prophet', 'en');
        $book2->translator()->set('title', 'Останній пророк', 'uk');
        $book2->save();

        $this->app->setLocale('uk');

        $records = BookOrderByTranslatable::query()
            ->select(['books.id'])
            ->orderByTranslatable('title')
            ->get();

        self::assertTrue($records[0]->is($book2));
        self::assertTrue($records[1]->is($book1));
        self::assertFalse(isset($records[0]->created_at));
        self::assertFalse(isset($records[0]->translatable_attribute));
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        $this->schema()->drop('books');
        parent::tearDown();
    }
}

/**
 * @property string title
 * @property string|null value
 */
class BookOrderByTranslatable extends Model
{
    use HasTranslations;

    protected $table = 'books';

    protected $translatable = [
        'title',
    ];
}
