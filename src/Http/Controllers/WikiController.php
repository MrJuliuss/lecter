<?php

namespace MrJuliuss\Lecter\Http\Controllers;

use Illuminate\Routing\Controller;
use MrJuliuss\Lecter\Facades\Lecter;
use Illuminate\Support\Facades\Storage;
use Config;
use Request;

/**
 * This is the wiki controller class.
 *
 * @author Julien Richarte <julien.richarte@gmail.com>
 */
class WikiController extends Controller
{
    /**
     * Redirect to the main page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex($any = '')
    {
        $isAjax = Request::ajax();
        $askRaw = Request::input('raw');

        // Wiki prefix uri
        $prefix = Config::get('lecter.uri');

        // Create the wiki content directory if it does not exists
        if (Storage::exists('wiki') === false) {
            Storage::makeDirectory('wiki');
        }

        // Get the breadcrumbs
        $breadcrumbs = Lecter::getBreadCrumbs($any, $prefix);

        $any = 'wiki/'.$any;

        $directoryContent = Lecter::getDirectoryContent($any, $prefix);

        if (!isset($askRaw)) {
            // Get html formatted content
            $content = Lecter::getPageContent($any);
        } else {
            $content = Lecter::getRawPageContent($any);

            if ($isAjax === true) {
                return response()->json(['content' => $content]);
            }
        }

        if ($isAjax === true) {
            // Ajax request, return content without layout
            $html = view('lecter::controllers.wiki.content', [
                'files' => $directoryContent['files'],
                'directories' => $directoryContent['directories'],
                'content' => $content,
                'breadcrumbs' => $breadcrumbs
            ])->render();

            return response()->json(['html' => $html]);
        } else {
            // Get the navigation bar
            $navBar = Lecter::getNavBar(storage_path().'/app/wiki');
        }

        return view('lecter::controllers.wiki.index', [
            'files' => $directoryContent['files'],
            'directories' => $directoryContent['directories'],
            'content' => $content,
            'breadcrumbs' => $breadcrumbs,
            'navBar' => $navBar,
            'root' => $prefix,
        ]);
    }
}
