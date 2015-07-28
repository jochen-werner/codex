<?php
namespace Codex\Codex;

use Codex\Codex\Repositories\Interfaces\CodexRepositoryInterface;

class Codex
{
	/**
	 * @var \Codex\Codex\Repositories\Interfaces\CodexRepositoryInterface
	 */
	protected $repository;

	/**
	 * Create a new codex instance.
	 *
	 * @param  CodexRepositoryInterface  $codex
	 * @return void
	 */
	public function __construct(CodexRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	
}