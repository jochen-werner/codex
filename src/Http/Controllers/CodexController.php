<?php
namespace Codex\Codex\Http\Controllers;

use App\Http\Controllers\Controller;
use Codex\Codex\Factory;

class CodexController extends Controller
{
    protected $factory;

    /**
     * Create a new CodexContrller instance.
     *
     * @param \Codex\Codex\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Redirect to the default project and version.
     *
     * @return Redirect
     */
    public function index()
    {
        return redirect(route('codex.document', [
            'projectSlug' => config('codex.default_project')
        ]));
    }

    /**
     * Render the documentation page for the given project and version.
     *
     * @param string   $projectSlug
     * @param string|null   $ref
     * @param string $path
     * @return $this
     */
    public function document($projectSlug, $ref = null, $path = '')
    {
        $project = $this->factory->make($projectSlug);

        if ( is_null($ref) )
        {
            $ref = $project->getDefaultRef();
        }

        $project->setRef($ref);
        $document = $project->getDocument($path);
        $menu = $project->getMenu()->toArray();


        return view('codex::document', compact('project', 'document', 'menu'))->with([
            'projectName' => $project->getName(),
            'projectRef' => $ref
        ]);

    }
}
