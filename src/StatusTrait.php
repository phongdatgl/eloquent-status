<?php namespace Fatagroup\EloquentStatus;

trait StatusTrait {

	/**
	 * Boot the status trait for a model.
	 *
	 * @return void
	 */
	public static function bootStatusTrait()
	{
		static::addGlobalScope(new StatusScope);
	}

	/**
	 * //
	 *
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public static function withDraft()
	{
		return with(new static)->newQueryWithoutScope(new StatusScope);
	}

	/**
	 * //
	 *
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public static function onlyDraft()
	{
		$instance = new static;
		
		$column   = $instance->getQualifiedStatusColumn();

		return $instance->newQueryWithoutScope(new StatusScope)->where($column, $instance->getDraftConst());
	}

	/**
	 * //
	 * 
	 * @return boolean
	 */
	public function isDraft()
	{
		return $this->{$this->getStatusColumn()} === $this->getDraftConst();
	}

	/**
	 * Get the name of the "status" column.
	 *
	 * @return string
	 */
	public function getStatusColumn()
	{
		return defined('static::STATUS') ? static::STATUS : 'status';
	}

	/**
	 * Get the fully qualified "status" column.
	 *
	 * @return string
	 */
	public function getQualifiedStatusColumn()
	{
		return $this->getTable().'.'.$this->getStatusColumn();
	}

	/**
	 * //
	 * 
	 * @return string
	 */
	public function getDraftConst()
	{
		return defined('static::DRAFT') ? static::DRAFT : 'DRAFT';
	}

	/**
	 * //
	 * 
	 * @return string
	 */
	public function getApprovedConst()
	{
		return defined('static::APPROVED') ? static::APPROVED : 'APPROVED';
	}

}
