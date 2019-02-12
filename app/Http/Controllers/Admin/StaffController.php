<?php

namespace App\Http\Controllers\Admin;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends ApiController
{
    public function index()
    {
        return view('admin.staff');
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:xlsx|max:10240',
        ]);

        $file = $request->file('file');

        if (! $file->isValid()) {
            return $this->error('400', '文件不可用');
        }

        $excel = Excel::filter('chunk')->load($file->getRealPath());


        DB::transaction(function () use ($excel) {
            // 全量删除删除
            Staff::query()->delete();

            // 分块插入
            $excel->chunk(200, function ($items) {
                Staff::query()->insert($items->toArray());
            });
        });

        return $this->success('导入成功');
    }
}
