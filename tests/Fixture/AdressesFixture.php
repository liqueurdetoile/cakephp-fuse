<?php
declare(strict_types=1);

namespace Lqdt\CakephpFuse\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DeploymentsFixture
 */
class AdressesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'author_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'town' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_adresses_authors_idx' => ['type' => 'index', 'columns' => ['author_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_adresses_authors' => ['type' => 'foreign', 'columns' => ['author_id'], 'references' => ['authors', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8890',
                'author_id' => 'a41bf106-dfcc-412b-b0f2-080353ec8880',
                'town' => 'Miami'
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8891',
                'author_id' => 'a41bf106-dfcc-412b-b0f2-080353ec8881',
                'town' => 'Paris'
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8892',
                'author_id' => 'a41bf106-dfcc-412b-b0f2-080353ec8882',
                'town' => 'Prague'
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8893',
                'author_id' => 'a41bf106-dfcc-412b-b0f2-080353ec8883',
                'town' => 'Tokyo'
            ],
        ];
        parent::init();
    }
}
