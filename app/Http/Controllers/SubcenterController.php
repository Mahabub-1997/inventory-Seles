<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Subcenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class SubcenterController extends Controller
{
    //-------------- Get All Areas ---------------\\

    public function index(Request $request)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('area_view')){

            if ($request->ajax()) {
                $data = Subcenter::orderBy('id', 'desc')->get();

                return Datatables::of($data)->addIndexColumn()

                    ->addColumn('action', function($row){

                        $btn = '<a id="' .$row->id. '"  class="edit cursor-pointer ul-link-action text-success"
                        data-toggle="tooltip" data-placement="top" title="Edit"><i class="i-Edit"></i></a>';
                        $btn .= '&nbsp;&nbsp;';

                        $btn .= '<a id="' .$row->id. '" class="delete cursor-pointer ul-link-action text-danger"
                        data-toggle="tooltip" data-placement="top" title="Remove"><i class="i-Close-Window"></i></a>';
                        $btn .= '&nbsp;&nbsp;';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('area_centers.subcenters');

        }
        return abort('403', __('You are not authorized'));

    }

    //-------------- Store New Category ---------------\\

    public function store(Request $request)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('area_view')){

            request()->validate([
                'name' => 'required',
                'area_id' => 'required',
                'status' => 'required',
            ]);
            $area = Area::findOrFail($request['area_id']);

            Subcenter::create([
                'area_id' => $request['area_id'],
                'area_name' => $area->name,
                'status' => $request['status'],
                'name' => $request['name'],
            ]);
            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));
    }

    //------------ function show -----------\\

    public function show($id){
        //

    }

    public function edit(Request $request, $id)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('area_edit')){

            $center = Subcenter::findOrFail($id);

            return response()->json([
                'Subcenter' => $center,
            ]);

        }
        return abort('403', __('You are not authorized'));
    }

    //-------------- Update Category ---------------\\

    public function update(Request $request, $id)
    {

        $user_auth = auth()->user();
        if ($user_auth->can('user_edit')){

            request()->validate([
                'name' => 'required',
                'area_id' => 'required',
                'status' => 'required',
            ]);
            $area = Area::findOrFail($request['area_id']);

            Subcenter::whereId($id)->update([
                'status' => $request['status'],
                'area_id' => $request['area_id'],
                'area_name' => $area->name,
                'name' => $request['name'],
            ]);

            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));

    }

    //-------------- Remove Category ---------------\\

    public function destroy(Request $request, $id)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('area_delete')){

            Subcenter::whereId($id)->delete();
            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('category')){

            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $category_id) {
                Area::whereId($category_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }

            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));
    }
}
