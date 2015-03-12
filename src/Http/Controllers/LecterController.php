<?php

namespace MrJuliuss\Lecter\Http\Controllers;

use Illuminate\Routing\Controller;
use MrJuliuss\Lecter\Facades\Lecter;
use Illuminate\Support\Facades\Storage;

/**
 * This is the log viewer controller class.
 *
 * @author Julien Richarte <julien.richarte@gmail.com>
 */
class LecterController extends Controller
{
    /**
     * Redirect to the main page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex($any = '')
    {
        // Create the wiki content directory if it does not exists
        if(Storage::exists('wiki') === false) {
            Storage::makeDirectory('wiki');
        }

        // Get the navigation bar
        $navBar = Lecter::getNavBar(storage_path().'/app/wiki');

        // Get the breadcrumbs
        $breadcrumbs = Lecter::getBreadCrumbs($any);

        $any = 'wiki/'.$any;
        // Get file content
        $content = Lecter::getPageContent($any);

        $directoryContent = Lecter::getDirectoryContent($any);

        return view('lecter::index', [
            'files' => $directoryContent['files'],
            'directories' => $directoryContent['directories'],
            'content' => $content,
            'breadcrumbs' => $breadcrumbs,
            'navBar' => $navBar,
            'root' => '',
        ]);
    }
}
