<?php

namespace MrJuliuss\Lecter\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use MrJuliuss\Lecter\Facades\Lecter;
use MrJuliuss\Lecter\Exceptions\ContentNotFoundException;
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
        // Create the wiki content directory if it does not exists
        if (Storage::exists('wiki') === false) {
            Storage::makeDirectory('wiki');
        }

        $isAjax = Request::ajax();
        $askRaw = Request::input('raw');

        // Wiki prefix uri
        $prefix = Config::get('lecter.uri');

        // Get the breadcrumbs
        $breadcrumbs = Lecter::getBreadCrumbs($any, $prefix);

        $notOnIndex = $any !== '';
        $any = 'wiki/'.$any;
        $isFile = is_file(storage_path().'/app/'.$any);

        if (isset($askRaw) && $isFile) {
            $content = Lecter::getRawPageContent($any);

            if ($isAjax === true) {
                return response()->json([
                    'content' => $content,
                    'title' => explode('.', basename($any))[0],
                    'isFile' => $isFile
                ]);
            }
        }

        // Get html formatted content
        $content = Lecter::getPageContent($any);
        $directoryContent = Lecter::getDirectoryContent($any, $prefix);

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
        $any = 'wiki/'.$any;

        try {
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
        $newPath = '';
        $filePath = dirname($any);

        try {
            $any = 'wiki/'.$any;
            Lecter::checkContent($any);

            $isFile = is_file(storage_path().'/app/'.$any);
            $currentDirectoryPath = dirname($any);

            $pageExists = Lecter::checkIfPageExists($name, $currentDirectoryPath, $isFile ? 'file' : 'dir');
            if ($isFile === true) {
                $oldName = explode('.', basename($any))[0];
            } else {
                $explode = explode('/', $any);
                $oldName = $explode[count($explode) - 1];
            }

            if ($oldName !== $name && $pageExists === true) {
                $message = "A page with this name already exists.";
            } else {
                if ($isFile) {
                    // rename the markdown file
                    if ($oldName !== $name) {
                        Storage::put($currentDirectoryPath.'/'.$name.'.md', Lecter::getRawPageContent($any));
                        Storage::delete($any);
                        $any = $newPath = $filePath.'/'.$name.'.md';
                    } else {
                        Storage::put($any, $content);
                    }

                    $success = true;
                    $content = Lecter::getPageContent($any);
                    $message = 'Page updated with successs.';
                } else {
                    $success = rename(storage_path().'/app/'.$any, storage_path().'/app/'.$currentDirectoryPath.'/'.$name);
                    $newPath = $filePath.'/'.$name;
                    $message = 'Page updated with successs.';
                }
            }
        } catch (ContentNotFoundException $e) {
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

        $success = false;

        if (empty($name)) {
            return response()->json([
                'success' => $success,
                'message' => 'Title cannot be empty.',
            ]);
        }

        $newPath = $any !== '/' ? $any : '';
        $any = 'wiki/'.$any;
        $exists = Lecter::checkIfPageExists($name, $any, $type);

        if ($exists === false) {
            if ($type === 'file') {
                $success = Storage::put($any.'/'.$name.'.md', '');
            } else {
                $success = Storage::makeDirectory($any.'/'.$name);
            }
        } else {
            $message = 'A page with this name already exists';
        }

        if ($success === true) {
            $message = 'Page created with success.';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'newPath' => $newPath
        ]);
    }
}
