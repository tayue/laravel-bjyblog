<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Artisan;
use Baijunyao\LaravelUpload\Upload;
use Cache;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $config = cache('config');
        $assign = compact('config');

        return view('admin.config.edit', $assign);
    }

    /**
     * 邮箱设置
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function email()
    {
        $config = cache('config');
        $assign = compact('config');

        return view('admin.config.email', $assign);
    }

    /**
     * 第三方登录设置
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function oauth()
    {
        $config = cache('config');
        $assign = compact('config');

        return view('admin.config.oauth', $assign);
    }

    /**
     * QQ群设置
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function qqQun()
    {
        $config = cache('config');
        $assign = compact('config');

        return view('admin.config.qqQun', $assign);
    }

    /**
     * 备份
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function backup()
    {
        $config = cache('config');
        $assign = compact('config');

        return view('admin.config.backup', $assign);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Config $configModel)
    {
        $data = $request->except('_token');
        if ($request->hasFile('153')) {
            $file        = Upload::file('153', 'uploads/images', [], false);
            $result      = $file['status_code'] === 200 ? $file['data'][0]['path'] : '';
            $data['153'] = $result;
        }

        if (isset($data['165']) && empty($data['164'])) {
            $data['164'] = [];
        }

        $editData = [];

        foreach ($data as $k => $v) {
            $editData[] = [
                'id'    => $k,
                'value' => is_array($v) ? json_encode($v) : $v,
            ];
        }

        $result = $configModel->updateBatch($editData);

        if ($result) {
            // 更新缓存
            Cache::forget('config');
        }

        return redirect()->back();
    }

    /**
     * 清空各种缓存
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('clear-compiled');
        flash_success('操作成功');

        return redirect()->back();
    }
}
