<?php namespace Fatagroup\EloquentStatus;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class StatusScope implements ScopeInterface {

	/**
	 * All of the extensions to be added to the builder.
	 *
	 * @var array
	 */
	protected $extensions = array('withDraft', 'onlyDraft');

	/**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function apply(Builder $builder)
	{
		$model = $builder->getModel();

		$builder->where($model->getQualifiedStatusColumn(), $model->getApprovedConst());
		
		$this->extend($builder);
	}

	/**
	 * Remove the scope from the given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function remove(Builder $builder)
	{
		$column = $builder->getModel()->getQualifiedStatusColumn();

		$query  = $builder->getQuery();

		foreach ((array) $query->wheres as $key => $where)
		{
			if (array_get($where, 'column') === $column)
			{
				$bindings = $query->getBindings();

				unset($bindings[$key]);
				unset($query->wheres[$key]);

				$query->wheres = array_values($query->wheres);
				$query->setBindings(array_values($bindings));
			}
		}
	}

	/**
	 * Extend the query builder with the needed functions.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function extend(Builder $builder)
	{
		foreach ($this->extensions as $extension)
		{
			$this->{"add{$extension}"}($builder);
		}
	}

	/**
	 * Add the with-fraft extension to the builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	protected function addWithDraft(Builder $builder)
	{
		$builder->macro('withDraft', function(Builder $builder)
		{
			$this->remove($builder);

			return $builder;
		});
	}

	/**
	 * Add the only-fraft extension to the builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	protected function addOnlyDraft(Builder $builder)
	{
		$builder->macro('onlyDraft', function(Builder $builder)
		{
			$this->remove($builder);

			$model = $builder->getModel();

			$builder->getQuery()->where($model->getQualifiedStatusColumn(), $model->getDraftConst());

			return $builder;
		});
	}

}
