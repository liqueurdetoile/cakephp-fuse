<?php
declare(strict_types=1);

namespace Lqdt\CakephpFuse\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DeploymentsFixture
 */
class AuthorsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'name' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []]
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
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8880',
                'name' => 'John Scalzi'
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8881',
                'name' => 'Steve Hamilton'
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8882',
                'name' => 'Remy Sharp'
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8883',
                'name' => 'P.D Woodhouse'
            ],
        ];
        parent::init();
    }
}
