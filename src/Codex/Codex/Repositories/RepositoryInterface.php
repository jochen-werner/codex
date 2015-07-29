<?php
namespace Codex\Codex\Repositories;

interface RepositoryInterface
{
	/**
	 * Get the given documentation page.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 * @param  string  $page
	 * @return string|null
	 */
	public function get($project, $version, $page);

	/**
	 * Get all projects.
	 *
	 * @return array
	 */
	public function getProjects();

	/**
	 * Get all versions for the given project.
	 *
	 * @param  string  $project
	 * @return array
	 */
	public function getVersions($project);

	/**
	 * Search project for given string.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 * @param  string  $needle
	 * @return array
	 */
	public function search($project, $version, $needle = '');
}