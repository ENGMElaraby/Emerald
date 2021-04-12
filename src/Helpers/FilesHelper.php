<?php

namespace MElaraby\Emerald\Helpers;

use Illuminate\Support\Str;

trait FilesHelper
{
    private $fileName;

    /**
     * @param array $files
     * @param string $location
     * @return array
     */
    protected function uploadMultiFiles(array $files, string $location): array
    {
        $uploadedFiles = [];
        foreach ($files as $key => $file) {
            $uploadedFiles[$key]['url'] = $this->fileUpload($file, $location);
            $uploadedFiles[$key]['name'] = $this->fileName;
        }

        return $uploadedFiles;
    }

    /**
     * @param object $file
     * @param string $location
     * @return string|null
     */
    protected function fileUpload(object $file, string $location): ?string
    {
        if (!is_file($file)) {
            return null;
        }

        $fileOriginalExtension = $file->getClientOriginalExtension();
        $fileUniqueName = $this->uniqueName($fileOriginalExtension);
        $file->storeAs('public/uploads/' . $location, $fileUniqueName);
        return url()->to('/storage/uploads/' . $location . '/' . $fileUniqueName);
    }

    /**
     * @param string $extension
     * @return string
     */
    private function uniqueName(string $extension): string
    {
        return time() . '_' . Str::random(6) . '.' . $extension;
    }
}
