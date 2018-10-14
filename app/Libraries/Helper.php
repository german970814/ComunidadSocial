<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Helper
{
    public function __construct() {
        //
    }

    /**
     * Function decorator to make atomic transactions
     * 
     * @param Function $callback
     * @return void
     */
    public static function atomic_transaction ($callback) {
        try {
            DB::beginTransaction();
            $callback();
            DB::commit();
        } catch (\PDOException $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    public static function get_content_type($filename) {
        $path = Storage::disk('local')->path($filename);
        $content_type = \File::mimeType($path);
        return $content_type;
    }

    public static function get_file_icon($extension) {
        switch ($extension) {
            case 'pdf':
                return asset('assets/img/svg/pdf.svg');
                break;
            case 'doc':
                return asset('assets/img/svg/doc.svg');
                break;
            case 'docx':
                return asset('assets/img/svg/doc.svg');
                break;
            case 'ppt':
                return asset('assets/img/svg/ppt.svg');
                break;
            case 'pptx':
                return asset('assets/img/svg/ppt.svg');
                break;
            case 'png':
                return asset('assets/img/svg/picture.svg');
                break;
            case 'jpg':
                return asset('assets/img/svg/picture.svg');
                break;
            case 'gif':
                return asset('assets/img/svg/picture.svg');
                break;
            case 'jpeg':
                return asset('assets/img/svg/picture.svg');
                break;
            default:
                return asset('assets/img/svg/picture.svg');
                break;
        }
    }
}
