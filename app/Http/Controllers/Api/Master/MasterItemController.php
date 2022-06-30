<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Imports\Master\MasterItem;
use App\Model\Master\MasterItem as MasterItemModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\HeadingRowImport;

class MasterItemController extends Controller
{
    function excelExtract(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:1024'
        ]);
        $sourceData = (new HeadingRowImport)->toArray($request->file('file'));

        $result = new MasterItem();
        Excel::import($result, $request->file("file"));


        $data = [
            "source_data" => $sourceData[0][0],
            "result" => $result->data
        ];
        return response()->json([
            "data" => $data
        ]);
    }

    function postMasterItems(Request $request)
    {
        DB::beginTransaction();
        try {

            $createdAt=date('Y-m-d H:i:s');
            foreach ($request->data as $v) {
                if ($v['name'] == null || $v['code'] == null || $v['chart_of_account'] == null || $v['unit_of_measurement_small'] == null || $v['unit_of_measurement_big'] == null || $v['unit_converter'] == null || $v['expired_date'] == null || $v['production_number'] == null || $v['group'] == null) {
                    return response()->json([
                        "message" => "Data Cant Be Null"
                    ]);
                }

                MasterItemModel::insert([
                    "code" => $v['code'],
                    "name" => $v['name'],
                    "chart_of_account" => $v['chart_of_account'],
                    "unit_of_measurement_small" => $v['unit_of_measurement_small'],
                    "unit_of_measurement_big" => $v['unit_of_measurement_big'],
                    "unit_converter" => $v['unit_converter'],
                    "expired_date" => date('Y-m-d', strtotime($v['expired_date'])),
                    "production_number" => $v['production_number'],
                    "group" => $v['group'],
                    "created_at" => $createdAt
                ]);
            }
            DB::commit();
            return response()
                ->json([
                    "message" => "Success to Insert Data"
                ]);
        } catch (\Throwable $err) {
            DB::rollBack();
            return response()->json([
                "message" => $err->getMessage()
            ]);
        }
    }

    function masterItemList(Request $request){
        $data=new MasterItemModel;

        if ($request->search!=null){
            $data=$data->where("code",$request->search)
                    ->orWhere("name",$request->search);
        }
        $data=pagination($data,$request->limit);



        return response()->json([
            "message" => "Success",
            "data" =>$data
        ]);
    }
}
