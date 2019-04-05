<?php

namespace App\Vars;

class UploadManager
{
    const STATUS_OK             = 0;
    const STATUS_NOT_EXISTS     = 1;
    const STATUS_UPLOAD_ERR     = 2;
    const STATUS_UNMATCH_EXT    = 3;
    const STATUS_TOO_LARGE      = 4;

    const EXTENSION_IMAGE = [
        'jpg',
        'jpeg',
        'png'
    ];


    /**
     * Check if uploaded file OK
     * 
     * @param array     $uploadedFiles files being uploaded
     * @param string    $field field used to identify file when uploaded through POST
     * @param array     $extensions filter file extension, lowercased
     * @param int       $maxSize max allowed size for uploaded file
     *                  in Megabytes
     * @return int STATUS
     */
    public static function checkFile($uploadedFiles, $field, array $extensions=[], $maxSize=2)
    {
        // check if 
        if (isset($uploadedFiles[$field])) {
            $uploadedFile = $uploadedFiles[$field];
            switch ($uploadedFile->getError())
            {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    return [self::STATUS_NOT_EXISTS, 'Tidak ada file yang diupload'];
                default:
                    return [self::STATUS_UPLOAD_ERR, 'Gagal upload'];
            }

            $extension = self::getExtension($uploadedFile);
            if (count($extensions) > 0 && !in_array($extension, $extensions)) {
                return [self::STATUS_UNMATCH_EXT, 'Ekstensi file tidak sesuai. Harus: '. implode(', ', $extensions)];
            }

            $size = $uploadedFile->getSize();
            if ($size > ($maxSize * 1024 * 1024)) {
                return [self::STATUS_TOO_LARGE, 'Ukuran terlalu besar. Max: '. number_format($maxSize * 1024) .' KB'];
            }

            return [self::STATUS_OK, 'OK'];
        }

        return [self::STATUS_NOT_EXISTS, 'Tidak ada file yang diupload'];
    }

    /**
     * Get file extension
     * 
     * @param Slim\Http\UploadedFile $uploadedFile file to get extension
     * @return string extension lowercased
     */
    public static function getExtension($uploadedFile)
    {
        return strtolower(pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION));
    }
    
    /**
     * Move uploaded file to $directory
     * 
     * @param Slim\Http\UploadedFile    $uploadedFile uploaded file to move
     * @param string                    $directory directory without '/' endings
     * @param string                    $filename file name
     */
    public static function moveUploadedFile($uploadedFile, $directory, $filename) {
        self::createDirectory($directory, 0775);    
        $uploadedFile->moveTo($directory .'/'. $filename);
    }

    /**
     * Create a directory if not exists and set chmod to it
     * 
     * @param string    $directory directory path
     * @param int       $chmod
     */
    public static function createDirectory($directory, $chmod) {
        if (!file_exists($directory)) {
            mkdir($directory, $chmod, true);
        }

        chmod($directory, $chmod);
    }

    /**
     * Delete a file if exists and a file
     * 
     * @param string $filename full path to file
     */
    public static function deleteFile($filename) {
        if (file_exists($filename) && is_file($filename)) {
            unlink($filename);
        }
    }
}