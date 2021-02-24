<?php
/**
 * User: wangkuan
 * Date: 2021/2/23
 * Time: 10:34
 */

namespace ChastePhp\LaravelExtras\Supports;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasIn extends HasMany
{
    public function __construct(Model $parent, $related, $parentKey, $childKey)
    {
        $instance = tap(new $related, function ($instance) use ($parent) {
            if (!$instance->getConnectionName()) {
                $instance->setConnection($parent->getConnectionName());
            }
        });

        parent::__construct($instance->newQuery(), $parent, $childKey, $parentKey);
    }

    protected function getKeys(array $models, $key = null): array
    {
        return collect($models)->map(function ($value) use ($key) {
            return $key ? $value->getAttribute($key) : $value->getKey();
        })->values()->flatten()->unique(null, true)->sort()->all();
    }

    protected function matchOneOrMany(array $models, Collection $results, $relation, $type): array
    {
        $dictionary = $this->buildDictionary($results);

        // Once we have the dictionary we can simply spin through the parent models to
        // link them up with their children using the keyed dictionary to make the
        // matching very convenient and easy work. Then we'll just return them.
        foreach ($models as $model) {
            $keys = (array) $model->getAttribute($this->localKey);
            $related = [];
            foreach ($keys as $key) {
                if (isset($dictionary[$key])) {
                    $related = array_merge($related, $dictionary[$key]);
                }
            }
            $model->setRelation(
                $relation, $this->related->newCollection($related)
            );
        }

        return $models;
    }

    public function addConstraints()
    {
        if (static::$constraints) {
            $this->query->whereIn($this->foreignKey, $this->getParentKey());

            $this->query->whereNotNull($this->foreignKey);
        }
    }
}
