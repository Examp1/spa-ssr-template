<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscribes;
use Illuminate\Http\Request;

class BlogSubscribeController extends Controller
{
    public function __construct() {
        $this->middleware('permission:blog_subscribe_view')->only('index');
    }

    public function index()
    {
        $model = Subscribes::query()
            ->orderBy('created_at','desc')
            ->paginate(50);

        return view('admin.blog.subscribe.index',[
            'model' => $model
        ]);
    }

    public function list()
    {
        $model = Subscribes::query()
            ->orderBy('created_at','desc')
            ->get();

        $data = "";

        foreach ($model as $key => $item){
            $data .= $item->email;

            if($key < count($model) - 1){
                $data .= PHP_EOL;
            }
        }

        return view('admin.blog.subscribe.list',[
            'data' => $data
        ]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'),true);

        if(count($ids)){
            foreach ($ids as $id){
                Subscribes::query()->where('id',$id)->delete();
            }

            return redirect()->back()->with('success','Записи успешно удалены!');
        }

        return redirect()->back()->with('error','Ошибка удаления!');
    }
}
