<?php
declare(strict_types=1);

namespace Lqdt\CakephpFuse\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DeploymentsFixture
 */
class BooksFixture extends TestFixture
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
        'title' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        // 'summary' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_books_authors_idx' => ['type' => 'index', 'columns' => ['author_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_books_authors' => ['type' => 'foreign', 'columns' => ['author_id'], 'references' => ['authors', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8870',
                'author_id' => 'a41bf106-dfcc-412b-b0f2-080353ec8880',
                'title' => 'Old Man\'s War',
                'summary' => "Old Man's War is about a soldier named John Perry and his exploits in the Colonial Defense Forces (CDF). The first-person narrative follows Perry's military career from CDF recruit to the rank of captain.",
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8871',
                'author_id' => 'a41bf106-dfcc-412b-b0f2-080353ec8881',
                'title' => 'The Lock Artist',
                'summary' => "Years after a family tragedy steals his speech at the age of 8, Mike discovers his talent at picking locks, but when his gift comes to the attention of the wrong people, Mike loses everything. The Lock Artist is a sympathetic tale which shows the devastating effects of a childhood trauma on the narrator.",
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8872',
                'author_id' => 'a41bf106-dfcc-412b-b0f2-080353ec8882',
                'title' => 'HTML5',
                'summary' => "Suddenly, everyone's talking about HTML5, and ready or not, you need to get acquainted with this powerful new development in web and application design. Some of its new features are already unlocked by existing browsers, and much more is around the corner.",
            ],
            [
                'id' => 'a41bf106-dfcc-412b-b0f2-080353ec8873',
                'author_id' => 'a41bf106-dfcc-412b-b0f2-080353ec8883',
                'title' => 'Right Ho Jeeves',
                'summary' => "Bertie returns to London from several weeks in Cannes spent in the company of his Aunt Dahlia Travers and her daughter Angela. In Bertie's absence, Jeeves has been advising Bertie's old school friend, Gussie Fink-Nottle, who is in love with a goofy, sentimental, whimsical, childish girl named Madeline Bassett.",
            ],
        ];
        parent::init();
    }
}
