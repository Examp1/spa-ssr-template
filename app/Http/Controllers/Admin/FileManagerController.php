<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Events\AuthLoginEventHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccessGroupCreateRequest;
use App\Models\AccessGroupPermissions;
use App\Models\AccessGroups;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function file()
    {
        return view('admin.file-manager.file', [

        ]);
    }

    public function image()
    {
        return view('admin.file-manager.image', [

        ]);
    }

    public function getInfo(Request $request)
    {
        $path     = get_image_uri($request->get('path'));
        $filePath = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'media' . $request->get('path');

        $uploadedFile = new \Symfony\Component\HttpFoundation\File\File($filePath);

        try {
            $name       = $uploadedFile->getFilename();
            $created_at = Carbon::createFromTimestamp($uploadedFile->getCTime())->format('d.m.Y');
            $mime       = $uploadedFile->getMimeType();
            $size       = $uploadedFile->getSize() / 1024;
            $size       = number_format($size, 2);
            [$imgWidth, $imgHeight] = getimagesize($path);

            $modal = View::make('admin.pieces.modals.media-info-modal', [
                'title'      => "Информация",
                'path'       => $path,
                'name'       => $name,
                'created_at' => $created_at,
                'mime'       => $mime,
                'size'       => $size,
                'width'      => $imgWidth,
                'height'     => $imgHeight,
                'modal_id'   => $request->get('modal_id'),
                'alt_name'   => $request->get('alt_name'),
                'alt_value'  => $request->get('alt_value'),
            ])->render();
        } catch (\Exception $e) {
            return [
                'success' => false
            ];
        }

        return [
            'success' => true,
            'modal'   => $modal
        ];
    }
}
