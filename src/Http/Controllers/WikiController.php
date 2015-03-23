<?php

namespace MrJuliuss\Lecter\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use MrJuliuss\Lecter\Facades\Lecter;
use MrJuliuss\Lecter\Exception\ContentNotFoundException;
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
     * Get wiki content
     * @param  string $any wiki page path
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

    /**
     * Delete content
     * @param  string $any path
     */
    public function deletePage($any = '')
    {
        $deleted = false;
        $message = '';

        try {
            $any = 'wiki/'.$any;
            Lecter::checkContent($any);
            $deleted = Lecter::deleteContent($any);
            $message = 'Content deleted with success.';
        } catch (ContentNotFoundException $e) {
            $message = 'Content does not exists.';
        }

        return response()->json([
            'success' => $deleted,
            'message' => $message,
        ]);
    }
}
