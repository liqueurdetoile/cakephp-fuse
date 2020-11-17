<?php
declare(strict_types=1);

namespace Lqdt\CakephpFuse\Test\Model\Table;

use Cake\ORM\Table;

class AuthorsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->hasOne('Adresses', [
          'className' => 'Lqdt\CakephpFuse\Test\Model\Table\AdressesTable'
        ]);
        $this->addBehavior('Lqdt\CakephpFuse\Model\Behavior\FuseBehavior');
    }
}
