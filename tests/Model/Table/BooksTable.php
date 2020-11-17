<?php
declare(strict_types=1);

namespace Lqdt\CakephpFuse\Test\Model\Table;

use Cake\ORM\Table;

class BooksTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->belongsTo('Authors', [
          'className' => 'Lqdt\CakephpFuse\Test\Model\Table\AuthorsTable'
        ]);
        $this->addBehavior('Lqdt\CakephpFuse\Model\Behavior\FuseBehavior');
    }
}
