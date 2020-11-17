<?php
declare(strict_types=1);

namespace App\Test\TestCase;

use Lqdt\CakephpFuse\Test\Model\Table\BooksTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class CoreTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Lqdt\CakephpFuse\Test\Model\Table\BooksTable
     */
    protected $Books;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'Lqdt\CakephpFuse\Test\Fixture\AuthorsFixture',
        'Lqdt\CakephpFuse\Test\Fixture\BooksFixture',
        'Lqdt\CakephpFuse\Test\Fixture\AdressesFixture',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Books') ? [] : ['className' => BooksTable::class];
        $this->Books = $this->getTableLocator()->get('Books', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Books);

        parent::tearDown();
    }

    public function testDefaultFields(): void
    {
        $this->assertEquals(['title'], $this->Books->getSearchableFields());
    }

    public function testIsFuseEnabledInTable(): void
    {
        $this->assertTrue($this->Books->isFuseEnabledInTable($this->Books));
    }

    public function testFindAutoFieldsAutoQuery(): void
    {
        $books = $this->Books->fuse('Recht ho', ['includeScore' => true])->all();

        $this->assertEquals(1, $books->count());
        $this->assertStringContainsString('Jeeves', json_encode($books));
    }

    public function testAutoFieldsFuseFinder(): void
    {
        $books = $this->Books->find('fuse', ['filter' => 'Recht ho'])->all();

        $this->assertEquals(1, $books->count());
        $this->assertStringContainsString('Jeeves', json_encode($books));
    }

    public function testCustomFieldsAtRuntime(): void
    {
        $books = $this->Books->fuse('jeves', ['keys' => ['title']])->all();

        $this->assertEquals(1, $books->count());
        $this->assertStringContainsString('Jeeves', json_encode($books));
    }

    public function testCustomFieldsAtInitialize(): void
    {
        $this->Books->setSearchableFields(['title']);
        $books = $this->Books->fuse('jeves', ['keys' => ['title']])->all();

        $this->assertEquals(1, $books->count());
        $this->assertStringContainsString('Jeeves', json_encode($books));
    }

    public function testDeepKeys(): void
    {
        $books = $this->Books->fuse('hamil', ['keys' => ['title', 'author.name']])->contain('Authors')->all();

        $this->assertEquals(2, $books->count());
        $this->assertStringContainsString('Hamilton', json_encode($books));
        $this->assertStringContainsString('HTML5', json_encode($books));
    }

    public function testDeepKeysWithAutofields(): void
    {
        $books = $this->Books->fuse('hamil')->contain(['Authors', 'Authors.Adresses'])->all();

        $this->assertEquals(3, $books->count());
        $this->assertStringContainsString('Hamilton', json_encode($books));
        $this->assertStringContainsString('HTML5', json_encode($books));
        $this->assertStringContainsString('Miami', json_encode($books));
    }

    public function testOptions(): void
    {
        // Options at behavior creation
        $authorsOptions = $this->Books->Authors->getFuseOptions();
        $this->assertEquals(0.65, $authorsOptions['threshold']);

        // Set searchable fields persistent
        $this->assertEquals([], $this->Books->getFuseOptions());
        $table = $this->Books->setSearchableFields(['title']);
        $this->assertEquals($this->Books, $table);
        $this->assertEquals(['keys' => ['title']], $this->Books->getFuseOptions());

        // Reset Options
        $this->Books->setFuseOptions([], true);
        $this->assertEquals([], $this->Books->getFuseOptions());

        // Set options persistent
        $opts = ['keys' => ['title'], 'threshold' => 0.2];
        $table = $this->Books->setFuseOptions($opts);
        $this->assertEquals($opts, $this->Books->getFuseOptions());
    }
}
