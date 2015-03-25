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

        $notOnIndex = $any !== '';

        // Wiki prefix uri
        $prefix = Config::get('lecter.uri');

        // Create the wiki content directory if it does not exists
        if (Storage::exists('wiki') === false) {
            Storage::makeDirectory('wiki');
        }

        // Get the breadcrumbs
        $breadcrumbs = Lecter::getBreadCrumbs($any, $prefix);

        $any = 'wiki/'.$any;

        $isFile = is_file(storage_path().'/app/'.$any);
        $directoryContent = Lecter::getDirectoryContent($any, $prefix);

        if (!isset($askRaw)) {
            // Get html formatted content
            $content = Lecter::getPageContent($any);
        } else {
            $content = Lecter::getRawPageContent($any);

            if ($isAjax === true) {
                $isFile = is_file(storage_path().'/app/'.$any);

                return response()->json([
                    'content' => $content,
                    'title' => explode('.', basename($any))[0],
                    'isFile' => $isFile
                ]);
            }
        }

        if ($isAjax === true) {

            // Ajax request, return content without layout
            $html = view('lecter::controllers.wiki.content', [
                'files' => $directoryContent['files'],
                'directories' => $directoryContent['directories'],
                'content' => $content,
                'breadcrumbs' => $breadcrumbs,
                'notOnIndex' => $notOnIndex,
                'isFile' => $isFile
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
            'notOnIndex' => $notOnIndex,
            'isFile' => $isFile
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
            $message = 'Page deleted with success.';
        } catch (ContentNotFoundException $e) {
            $message = 'The page does not exists.';
        }

        return response()->json([
            'success' => $deleted,
            'message' => $message,
        ]);
    }

    /**
     * Update page content
     * @param  string $any path
     */
    public function editPage($any = '')
    {
        $content = Request::input('content');
        $name = Request::input('name');
        $success = false;
        $message = '';
        $newPath = '';
        $filePath = dirname($any);

        try {
            $any = 'wiki/'.$any;
            Lecter::checkContent($any);

            $isFile = is_file(storage_path().'/app/'.$any);
            $currentDirectoryPath = dirname($any);

            if ($isFile) {
                $success = Storage::put($any, $content);
                $content = Lecter::getPageContent($any);

                $oldName = explode('.', basename($any))[0];

                $fileExists = Lecter::checkIfPageExists($name, $currentDirectoryPath, 'file');

                // rename the markdown file
                if ($oldName !== $name) {
                    if ($fileExists === false) {
                        Storage::put($currentDirectoryPath.'/'.$name.'.md', Lecter::getRawPageContent($any));
                        $newPath = $filePath.'/'.$name.'.md';
                        Storage::delete($any);
                        $message = 'Page updated with successs.';
                    } else {
                        $success = false;
                        $message = "A page with this name already exists.";
                    }
                }
            } else {
                $directoryExists = Lecter::checkIfPageExists($name, $currentDirectoryPath, 'directory');
                if ($directoryExists === false) {
                    $success = rename(storage_path().'/app/'.$any, storage_path().'/app/'.$currentDirectoryPath.'/'.$name);
                    $newPath = $filePath.'/'.$name;
                    $message = 'Page updated with successs.';
                } else {
                    $success = false;
                    $message = "A page with this name already exists.";
                }
            }
        } catch (Exception $e) {
            $message = 'The page does not exists.';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'content' => $content,
            'newPath' => $newPath
        ]);
    }

    /**
     * Add a page
     * @param string $any current directory path
     */
    public function addPage($any = '')
    {
        $name = Request::input('name');
        $type = Request::input('type');

        if (empty($name)) {
            return response()->json([
                'success' => false,
                'message' => 'Title cannot be empty.',
            ]);
        }

        $newPath = $any;
        $any = 'wiki/'.$any;
        if ($type === 'file') {
            $exists = Lecter::checkIfPageExists($name, $any, 'file');

            if ($exists === false) {
                $success = Storage::put($any.'/'.$name.'.md', 'test');
                $message = 'Page created with success.';
            } else {
                $success = false;
                $message = 'A page with this name already exists';
            }
        } else {
            $exists = Lecter::checkIfPageExists($name, $any, 'directory');

            if ($exists === false) {
                $success = Storage::makeDirectory($any.'/'.$name);
                $message = 'Page created with success.';
            } else {
                $success = false;
                $message = 'A page with this name already exists.';
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'newPath' => $newPath
        ]);
    }
}
