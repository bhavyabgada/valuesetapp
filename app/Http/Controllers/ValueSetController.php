<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;
use App\Models\Valueset;
use Yajra\DataTables\Facades\DataTables;

class ValueSetController extends Controller
{
    public function create(){
        return view('valueset.create');
    }

    public function uploadCsv(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        foreach ($data as $index => $row) {
            if ($index === 0) continue;

            try {
                Valueset::create([
                    'value_set_id' => $row[0],
                    'value_set_name' => $row[1],
                    'medications' => $row[2],
                ]);
            } catch (\Exception $e) {
                return back()->with('message', "Error on row $index: " . $e->getMessage());
            }
        }

        return back()->with('message', 'CSV data has been uploaded and inserted successfully.');
    }

    public function index(){
        return view('valueset.index');
    }

    public function getValuesetList(Request $request)
    {
        if ($request->ajax()) {
            $search_keyword = '';
            $request_data = $request->all();
            if ($request->has('search_keyword')) {
                $search_keyword = $request->search_keyword;
            }
            // avoid zero column as it's checkbox so we can't sort by it
            if ($request->has('order') && $request->order[0]['column'] != 0) {
                $sort_column_number = $request->order[0]['column'];
                $sort_column_dir = $request->order[0]['dir'];
                $sort_column_key = $request->columns[$sort_column_number]['data'];
            }

            $main_query = Valueset::query();
            $query = $main_query;
            if (!empty($search_keyword)) {
                $query = $query->where('value_set_name', 'LIKE', '%' . $search_keyword . '%');
            }
            if (!empty($sort_column_key)) {
                $query = $query->orderBy($sort_column_key, $sort_column_dir);
            } else {
                $query = $query->latest();
            }
            $data = $query->get();
            $count_total = $main_query->count();
            $count_filter = $count_total;
            return DataTables::of($data)
                //->addIndexColumn()
                // ->addColumn('checkbox', function ($row) {
                //     return '<input type="checkbox" id="'.$row->id.'" name="select_row[]" class="select_row" data-row-id = "'.$row->id.'"/>';
                // })
                ->addColumn('value_set_name', function ($row) {
                    return $row->value_set_name;
                })
                ->editColumn('created_at', function ($row) {
                    return formatDate($row->created_at);
                })
                // ->addColumn('action', function ($row) {
                //     if ($row->is_admin !== 1) {
                //         $actionBtn = '<a href="' . route('admin.edit.staff', ['staff_id' => $row->id]) . '" class="btn btn-sm btn-dark" title="Edit"><i class="fa fa-pencil-alt"></i></a>';
                //         $actionBtn .= $this->deleteStaffModal($row);
                //     } else {
                //         $actionBtn = 'N/A';
                //     }

                //     return $actionBtn;
                // })
                //->rawColumns(['action', 'checkbox','status'])
                //->rawColumns(['action', 'status'])
                ->with([
                    "recordsTotal"    => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
                ->make(true);
        }
    }

    public function getValueSetCompareList(Request $request)
    {
        if ($request->ajax()) {
            $search_keyword = '';
            $with_common_medication = 0;
            //echo '<pre>'; print_r($request->all()); die;
            //$request_data = $request->all();
            if ($request->has('search_keyword')) {
                $search_keyword = $request->search_keyword;
            }
            if ($request->has('with_common_medication')) {
                $with_common_medication = $request->with_common_medication;
            }

            // avoid zero column as it's checkbox so we can't sort by it
            if ($request->has('order') && $request->order[0]['column'] != 0) {
                $sort_column_number = $request->order[0]['column'];
                $sort_column_dir = $request->order[0]['dir'];
                $sort_column_key = $request->columns[$sort_column_number]['data'];
            }

            $main_query = Valueset::query();
            $query = $main_query;
            if (!empty($search_keyword)) {
                $query = $query->where('valuesets.value_set_name', 'LIKE', '%' . $search_keyword . '%');
            }
            if (intval($with_common_medication) === 1) {

                $query = $query->join('valuesets as v2', function ($join) {
                    $join->whereRaw('FIND_IN_SET(valuesets.medications, v2.medications) > 0')
                        ->whereRaw('valuesets.id <> v2.id');
                })->select('valuesets.*','v2.medications');
            }
            if (!empty($sort_column_key)) {
                $query = $query->orderBy($sort_column_key, $sort_column_dir);
            } else {
                $query = $query->latest();
            }
            $data = $query->get();
            $count_total = $main_query->count();
            $count_filter = $count_total;
            return DataTables::of($data)
                //->addIndexColumn()
                // ->addColumn('checkbox', function ($row) {
                //     return '<input type="checkbox" id="'.$row->id.'" name="select_row[]" class="select_row" data-row-id = "'.$row->id.'"/>';
                // })

                // ->addColumn('value_set_id', function ($row) {
                //     return $row->value_set_id;
                // })
                // ->addColumn('value_set_name', function ($row) {
                //     return $row->value_set_name;
                // })
                ->addColumn('action', function ($row) {
                    return view('valueset.view', compact('row'));
                })
                ->rawColumns(['action'])
                ->with([
                    "recordsTotal"    => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
                ->make(true);
        }
    }

    public function getValueSetMedicationList(Request $request)
    {
        if ($request->ajax()) {
            $search_keyword = '';
            //$request_data = $request->all();
            if ($request->has('search_keyword')) {
                $search_keyword = $request->search_keyword;
            }
            // avoid zero column as it's checkbox so we can't sort by it
            if ($request->has('order') && $request->order[0]['column'] != 0) {
                $sort_column_number = $request->order[0]['column'];
                $sort_column_dir = $request->order[0]['dir'];
                $sort_column_key = $request->columns[$sort_column_number]['data'];
            }

            if ($request->has('valueset_id')) {
                $valuesSet = Valueset::find($request->valueset_id);
                if($valuesSet){
                    $medications = explode('|', $valuesSet->medications);
                    $main_query = Medication::whereIn('medication_id', $medications);
                    $query = $main_query;
                    if (!empty($search_keyword)) {
                        $query = $query->where('medname', 'LIKE', '%' . $search_keyword . '%')->orWhere('simple_generic_name', 'LIKE', '%' . $search_keyword . '%');
                    }
                    if (!empty($sort_column_key)) {
                        $query = $query->orderBy($sort_column_key, $sort_column_dir);
                    } else {
                        $query = $query->latest();
                    }
                    $data = $query->get();
                    $count_total = $main_query->count();
                    $count_filter = $count_total;
                    return DataTables::of($data)
                        ->with([
                            "recordsTotal"    => $count_total,
                            "recordsFiltered" => $count_filter,
                        ])
                        ->make(true);
                }
            }
        }
    }

    public function ValueSetCompare(){
        $valueset_mediactions = [];
        $common_keys = [];
        $valueset_ids = session('compare_valueset_ids');
        if(!$valueset_ids){
            return view('valueset.compare', compact('valueset_mediactions'));
        }
        //$valuesets = Valueset::whereIn($valueset_ids)->get();
        // $medications = Medication::join('valuesets', function($join) {
        //     $join->whereRaw("FIND_IN_SET(medications.medication_id, REPLACE(valuesets.medications, '|', ','))");
        // })
        // ->whereIn('valuesets.id', $valueset_ids)
        // ->select('medications.*', 'valuesets.value_set_name', 'valuesets.value_set_id')
        // ->get();
        $medications = Valueset::join('medications', function($join) {
            $join->whereRaw("FIND_IN_SET(medications.medication_id, REPLACE(valuesets.medications, '|', ','))");
        })
        ->whereIn('valuesets.id', $valueset_ids)
        ->select('medications.*', 'valuesets.value_set_name', 'valuesets.value_set_id')
        ->get();
        //echo '<pre>'; print_r($medications->toArray()); die;
        if($medications->isNotEmpty()){
            foreach($medications as $medication){
                $valueset_mediactions[$medication->value_set_name.' - '.($medication->value_set_id)][$medication->medication_id] = [
                    [
                        'label' =>'Medication Name',
                        'value' => $medication->medname
                    ],
                    [
                        'label' =>'Generic Name',
                        'value' => $medication->simple_generic_name
                    ],
                    [
                        'label' =>'Route',
                        'value' => $medication->route
                    ],
                    [
                        'label' =>'Outpatients',
                        'value' => $medication->outpatients
                    ],
                    [
                        'label' =>'Inpatients',
                        'value' => $medication->inpatients
                    ],
                    [
                        'label' =>'Patients',
                        'value' => $medication->patients
                    ],
                ];
            }
            //echo '<pre>'; print_r($valueset_mediactions); die;
            $common_keys = $this->find_common_subarray_keys($valueset_mediactions);
        }
        return view('valueset.compare', compact('valueset_mediactions', 'common_keys'));
    }


    public function ValueSetAddToCompare(Request $request)
    {
        if ($request->has('valueset_id')) {
            $valueset_id = $request->valueset_id;
            if (session()->has('compare_valueset_ids')) {
                $valueset_ids = session('compare_valueset_ids');
                if(count($valueset_ids) == 3){
                    return response()->json(['status' => 'error', 'message' => 'You can select max. 3 valuesets to compare. Remove one of the selected if you want to add another valueset.']);
                }
                $valueset_ids[] = $valueset_id;
                session()->put('compare_valueset_ids', $valueset_ids);
            } else {
                session()->put('compare_valueset_ids', [$valueset_id]);
            }
            return response()->json(['status' => 'success', 'message' => 'Valueset added to compare list.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Missing valueset'], 404);
        }
    }
    public function ValueSetRemoveCompare(Request $request)
    {
        if ($request->has('valueset_id')) {
            $valueset_id = $request->valueset_id;
            //session()->forget('key');
            if (session()->has('compare_valueset_ids')) {
                $valueset_ids = session('compare_valueset_ids');
                $remove_id = array_search ($valueset_id, $valueset_ids);
                unset($valueset_ids[$remove_id]);
                session()->put('compare_valueset_ids', $valueset_ids);
            }
            return response()->json(['status' => 'success', 'message' => 'Valueset removed from compare list.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Missing valueset'], 404);
        }
    }

    function find_common_subarray_keys($array) {
        $commonKeys = [];

    // Get the keys of the first sub-array
    $keys = array_keys($array[array_key_first($array)]);

    // Iterate over the rest of the sub-arrays
    foreach ($array as $subArray) {
        // Get the keys of the current sub-array
        $subKeys = array_keys($subArray);
        // Find the intersection of keys
        $commonKeys = empty($commonKeys) ? $subKeys : array_intersect($commonKeys, $subKeys);
    }

    return $subKeys;
    }
}
