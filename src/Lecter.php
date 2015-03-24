<?php

namespace MrJuliuss\Lecter;

use Illuminate\Support\Facades\Storage;
use MrJuliuss\Lecter\Exceptions\ContentNotFoundException;

/**
 * This is the Lecter class.
 *
 * @author Julien Richarte <julien.richarte@gmail.com>
 */
class Lecter
{
    /**
     * Extension listed in Lecter
     * @var array
     */
    private $availableExtension = [
        'markdown',
        'mdown',
        'mkdn',
        'md',
        'mkd',
        'mdwn',
        'mdtxt',
        'mdtext',
        'text'
    ];

    /**
     * Get the wiki root directory files tree
     * @param  string wiki root directory
     * @return array
     */
    public function getNavBar($navPath)
    {
        $recursiveIteratorIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($navPath, \FilesystemIterator::SKIP_DOTS));
        $filesTree = [];

        foreach ($recursiveIteratorIterator as $splFileInfo) {
            $path = $splFileInfo->isDir()
                 ? array($splFileInfo->getFilename() => [])
                 : array($splFileInfo->getFilename());

            for ($depth = $recursiveIteratorIterator->getDepth() - 1; $depth >= 0; $depth--) {
                $path = [$recursiveIteratorIterator->getSubIterator($depth)->current()->getFilename() => $path];
            }

            $filesTree = array_merge_recursive($filesTree, $path);
        }

        return $filesTree;
    }

    /**
     * Get a breadcrumbs from a path
     * @param  string $path path to format to breadcrumbs
     * @return array [description]
     */
    public function getBreadCrumbs($path, $prefix)
    {
        $explodedPath = explode('/', $path);
        $breadcrumbs = [];

        if (!empty($explodedPath)) {
            $number = count($explodedPath);
            $link = $prefix;

            foreach ($explodedPath as $key => $breadcrumb) {
                if ($breadcrumb !== '') {
                    $link .= '/'.$breadcrumb;
                    $breadcrumbs[] = [
                        'name' => explode('.', $breadcrumb)[0],
                        'link' => $link,
                        'active' => $key === $number - 1,
                    ];
                }
            }
        }

        return $breadcrumbs;
    }

    /**
     * Get the content of a file
     * @param  string $path file path
     * @return string markdown formated string
     */
    public function getPageContent($path)
    {
        $extra = new \ParsedownExtra();

        $content = null;
        if (Storage::exists($path) === true) {
            $content = $extra->text(Storage::get($path));
        }

        return $content;
    }

    /**
     * Get the raw page content
     * @param  string $path file path
     * @return string
     */
    public function getRawPageContent($path)
    {
        $content = null;
        if (Storage::exists($path) === true) {
            $content = Storage::get($path);
        }

        return $content;
    }

    /**
     * Get the current directory content
     * @param  string $path path to scan
     * @return array
     */
    public function getDirectoryContent($path, $prefix)
    {
        $d = Storage::directories($path);
        $f = Storage::files($path);

        $files = [];
        foreach ($f as $key => $file) {
            $exp = explode('/', $file);
            $name = $exp[count($exp) - 1];

            $info = new \SplFileInfo($name);
            if (!in_array($info->getExtension(), $this->availableExtension)) {
                continue;
            }

            if (isset($exp[0])) {
                unset($exp[0]);
            }

            $link = $prefix.'/'.implode('/', $exp);
            $files[] = [
                'name' => explode('.', $name)[0],
                'link' => $link,
            ];
        }

        $directories = [];
        foreach ($d as $key => $directory) {
            $exp = explode('/', $directory);
            $name = $exp[count($exp) - 1];

            if (isset($exp[0])) {
                unset($exp[0]);
            }

            $link = $prefix.'/'.implode('/', $exp);
            $directories[] = [
                'name' => $name,
                'link' => $link
            ];
        }

        return [
            'files' => $files,
            'directories' => $directories,
        ];
    }

    /**
     * Check if content exists
     * @param  string $path content path
     */
    public function checkContent($path)
    {
        if (Storage::exists($path) === false) {
            throw new ContentNotFoundException('The content with path `'.$path.'`does not exists.');
        }
    }

    /**
     * Check if a directory or a file already exists in the current directory
     * @param  string $name file or directory name
     * @param  string $path current directory path
     * @param  string $type search a file or a directory
     * @return bool
     */
    public function checkIfPageExists($name, $path, $type = 'file')
    {
        if ($type === 'file') {
            $files = Storage::files($path);
            foreach ($files as $file) {
                $explodeFilePath = explode('/', $file);
                $fileName = explode('.', $explodeFilePath[count($explodeFilePath) - 1])[0];

                if ($name === $fileName) {
                    return true;
                }
            }
        } else {
            $directories = Storage::directories($path);
            foreach ($directories as $directory) {
                $explodeFilePath = explode('/', $directory);
                $fileName = $explodeFilePath[count($explodeFilePath) - 1];

                if ($name === $fileName) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Delete content
     * @param  string $path path to the content
     * @return bool
     */
    public function deleteContent($path)
    {
        if (is_file($path)) {
            return Storage::delete($path);
        }

        return Storage::deleteDirectory($path);
    }
}
