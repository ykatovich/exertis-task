<?php

namespace App\Services;

use App\Models\File;
use App\Models\Log as OwnLog;
use App\Models\Settings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class FileService
{
    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public function parseCsvFile(string $path): array
    {
        // Using the library league/csv to read the file
        $csv = Reader::createFromPath($path);
        $csv->setHeaderOffset(0);

        // Get all CSV strings as associative arrays
        $records = $csv->getRecords();
        $data = iterator_to_array($records);

        return array_map('array_change_key_case', $data);
    }

    public function logSuccess(string $filePath, int $countAwards): void
    {
        OwnLog::create([
            'file_path' => $filePath,
            'status' => OwnLog::STATUS_SUCCESS,
            'records_added' => $countAwards,
        ]);
    }

    public function logError(string $filePath): void
    {
        OwnLog::create([
            'file_path' => storage_path($filePath),
            'status' => OwnLog::STATUS_FAIL,
        ]);
    }

    public function storeFile(UploadedFile $uploadedFile): File
    {
        $patternPath = Settings::where('key', 'path')->first();
        $patternFileName = Settings::where('key', 'file_name_pattern')->first();

        $fileName = uniqid($patternFileName['value'] . '_') . '.csv';

        $savedFile = $uploadedFile->storeAs($patternPath['value'], $fileName);

        return File::create([
            'file_path' => $savedFile,
            'stored_name' => $fileName,
        ]);
    }

    public function storeFailedProcessFile(string $patternFileName, string $filePath): void
    {
        $newFileName = 'failed_' . uniqid($patternFileName . '_') . '.csv';
        $newFilePath = 'public' . '/' . 'failed_import' . '/' . $newFileName;
        $oldFilePath = str_replace(storage_path() . '/app', '', $filePath);
        Storage::move($oldFilePath, $newFilePath);

    }

    public function storeSuccessProcessFile(string $newFilePath, string $filePath): void
    {
        $oldFilePath = str_replace(storage_path() . '/app', '', $filePath);
        Storage::move($oldFilePath, $newFilePath);
    }
}
