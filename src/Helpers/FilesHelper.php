<?php

namespace MElaraby\Emerald\Helpers;

use Illuminate\Support\Str;

trait FilesHelper
{
    /**
     * @param object $file
     * @param $location
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
     * @param $extension
     * @return string
     */
    private function uniqueName(string $extension): string
    {
        return time() . '_' . Str::random(6) . '.' . $extension;
    }
}
