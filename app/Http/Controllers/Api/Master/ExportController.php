<?php

namespace App\Http\Controllers\Api\Master;

use App\Exports\CustomerExports;

use App\Exports\TenantExports;
use App\Http\Controllers\Controller;
use App\Model\CloudStorage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function export()
    {

        $user = tenant(auth()->user()->id);
        if (!in_array('read customer', $user->getPermissions())) {
            return response()->json([
                'message' => 'cannot export data'
            ],401);
        }
        $defaultBranch = array();
        foreach ($user->branches as $branch) {
            $defaultBranch[] = [
                $branch->id
            ];
        }

        $mytime = Carbon::now();

        $key = Str::random(16);
        $fileName = 'Transfer Item Send_' . $mytime->toDateTimeString();
        $fileExt = 'xlsx';
        $path = 'tmp/tenant' . '/' . $key . '.' . $fileExt;
        Excel::store(new CustomerExports($defaultBranch), $path, env('STORAGE_DISK'));

        $cloudStorage = new CloudStorage();
        $cloudStorage->file_name = $fileName;
        $cloudStorage->file_ext = $fileExt;
        $cloudStorage->feature = 'export customer';
        $cloudStorage->key = $key;
        $cloudStorage->path = $path;
        $cloudStorage->disk = env('STORAGE_DISK');
        $cloudStorage->owner_id = auth()->user()->id;
        $cloudStorage->download_url = env('API_URL') . '/download?key=' . $key;
        $cloudStorage->save();
        return response()->json([
            'data' => [
                'url' => $cloudStorage->download_url,
            ],
        ], 200);
    }
}
