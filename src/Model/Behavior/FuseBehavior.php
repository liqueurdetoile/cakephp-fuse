<?php
namespace Lqdt\CakephpFuse\Model\Behavior;

use Adbar\Dot;
use Cake\Core\Exception\Exception;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Fuse\Fuse;

/**
 * Fuse behavior for cakePHP framework
 *
 * @license MIT
 * @author  Liqueur de Toile <contact@liqueurdetoile.com>
 */
class FuseBehavior extends Behavior
{
    /**
     * Stores persistent fuse options for the given model
     *
     * @var  array
     * @link https://github.com/loilo/Fuse#Options
     */
    protected $_fuseOptions = [];

    /**
     * Apply given options if anay
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   array $config Fuse options
     */
    public function initialize(array $config = []): void
    {
        $this->setFuseOptions($config);
    }

    /**
     * Check if Fuse behavior is applied in a table
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   Table $table Table to test
     * @return  bool
     */
    public function isFuseEnabledInTable(Table $table): bool
    {
        return $table->hasBehavior('Lqdt\CakephpFuse\Model\Behavior\FuseBehavior') || $table->hasBehavior('Lqdt/CakephpFuse.Fuse');
    }

    /**
     * Returns the table linked to behavior
     *
     * Since 4.2.0, `Behavior::getTable` have been deprecated in favor of `Behavior::table`
     *
     * @return table
     */
    public function getCompatibilityTable(): table
    {
        if(method_exists($this, 'table')) {
            return $this->table();
        }

        return $this->getTable();
    }

    /**
     * Parse table columns and extract string fields that can be matched against fuse filter
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   Table $table  Table to scan
     * @param   array $fields Previous fields
     * @return  array             Updated fields
     */
    public function getFuseAutoFields(Table $table, array $fields = []): array
    {
        $schema = $table->getSchema();
        $columns = $schema->columns();

        foreach ($columns as $column) {
            $type = $schema->getColumnType($column);
            if ($type === 'string') {
                $fields[] = $column;
            }
        }

        return $fields;
    }

    /**
     * Return the searchable fields in current table. If none are already set up, it will scan current table
     * for searchable fields
     *
     * @version 1.0.0
     * @since   1.0.0
     * @return  array     Searchable fields
     */
    public function getSearchableFields(): array
    {
        if (!empty($this->_fuseOptions['keys'])) {
            return $this->_fuseOptions['keys'];
        }

        $table = $this->getCompatibilityTable();
        $fields = $this->getFuseAutoFields($table);
        $this->setSearchableFields($fields);

        return $fields;
    }

    /**
     * Set the serachable fields in the current table
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   array $fields Fields to search
     * @return  Table             Current table (for chaining purpose)
     */
    public function setSearchableFields(array $fields): Table
    {
        $this->_fuseOptions['keys'] = $fields;

        return $this->getCompatibilityTable();
    }

    /**
     * Get the current persistent fuse options from the current table
     *
     * @version 1.0.0
     * @since   1.0.0
     * @return  array     Fuse options
     * @link    https://github.com/loilo/Fuse#Options
     */
    public function getFuseOptions(): array
    {
        return $this->_fuseOptions;
    }

    /**
     * Set the persistent fuse options for the current table
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   array $options Options
     * @param   bool  $replace If set to true, the provided options will replace current options
     * @return  Table              Current table (for chaining purpose)
     * @link    https://github.com/loilo/Fuse#Options
     */
    public function setFuseOptions(array $options, bool $replace = false): Table
    {
        $this->_fuseOptions = $replace ? $options : array_merge($this->_fuseOptions, $options);

        return $this->getCompatibilityTable();
    }

    /**
     * Automatically extract searchable fields from all models contained in a query (if Fuse behavior have been applied)
     * It returns a list that fits `keys` fuse option requirement to fetch nested data
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   Query $query Query
     * @return  array            Fields list
     */
    public function getFuseKeysFromQuery(Query $query): array
    {
        $fields = $this->getSearchableFields();
        $joints = $query->getContain();
        $fields = $this->_getKeysFromAssociatedData($this->getCompatibilityTable(), $joints, $fields);

        return $fields;
    }

    /**
     * Main processor for the fuse search. It simply use a mapper to filter out fuzzy search.
     *
     * Options are merged between persistent model options and provided options.
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   string $filter  Filter to apply
     * @param   array  $options Optional options
     * @param   Query  $query   Optional query
     * @return  Query              Updated query
     */
    public function fuse(string $filter, array $options = [], Query $query = null): Query
    {
        $query = $query ?? $this->getCompatibilityTable()->find();
        $options = array_merge($this->_fuseOptions, $options);

        $formatter = function (\Cake\Collection\CollectionInterface $items) use ($filter, $options, $query) {
            if (empty($options['keys'])) {
                $options['keys'] = $this->getFuseKeysFromQuery($query);
            }
            $fuse = new Fuse($items->toArray(), $options);

            return $fuse->search($filter);
        };

        $query->formatResults($formatter);

        return $query;
    }

    /**
     * Wrapper for `fuse` method to be used as a custom finder
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   Query $query   Query
     * @param   array $options Runtime options (optional)
     * @return  Query              Updated Query
     */
    public function findFuse(Query $query, array $options = []): Query
    {
        if (!array_key_exists('filter', $options)) {
            throw new Exception('Missing mandatory parameter "filter" for fuse finder', 500);
        }

        return $this->fuse($options['filter'], $options['fuse'] ?? [], $query);
    }

    /**
     * Recursive method to process autofields detection when no keys are provided at runtime
     *
     * @version 1.0.0
     * @since   1.0.0
     * @param   Table $table        Starting table
     * @param   array $associations Associations to be processed
     * @param   array $fields       Current keys array
     * @param   array $prefixes     Current prefixes
     * @return  array                   Updated fields list
     */
    protected function _getKeysFromAssociatedData(Table $table, array $associations, array $fields, array $prefixes = []): array
    {
        $childs = array_keys($associations);

        foreach ($childs as $name) {
            $association = $table->$name;

            // Avoid processing ?_TO_MANY as it can't be processed with fuse
            if ($association->type() !== $association::ONE_TO_ONE && $association->type() !== $association::MANY_TO_ONE) {
                continue;
            }

            $target = $association->getTarget();
            $nextPrefixes = array_merge($prefixes, [$association->getProperty()]);
            $prefix = implode('.', $nextPrefixes);

            // Fetch fields from direct childs
            if ($this->isFuseEnabledInTable($target)) {
                $keys = $target->getSearchableFields();
                array_walk(
                    $keys, function (string $key) use (&$fields, $prefix) {
                        $fields[] = $prefix . '.' . $key;
                    }
                );
            }

            // Process subdata
            $fields = $this->_getKeysFromAssociatedData($target, $associations[$name], $fields, $nextPrefixes);
        }

        return $fields;
    }
}
