<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medication;
use Yajra\DataTables\Facades\DataTables;

class MedicationController extends Controller
{
    public function create(){
        return view('medications.create');
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
                Medication::create([
                    'medication_id' => $row[0],
                    'medname' => $row[1],
                    'simple_generic_name' => $row[2],
                    'route' => $row[3],
                    'outpatients' => $row[4],
                    'inpatients' => $row[5],
                    'patients' => $row[6]
                ]);
            } catch (\Exception $e) {
                return back()->with('message', "Error on row $index: " . $e->getMessage());
            }
        }

        return back()->with('message', 'CSV data has been uploaded and inserted successfully.');
    }

    public function index(){
        return view('medications.index');
    }

    public function getMedications(Request $request)
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

            $main_query = Medication::query();
            $query = $main_query;
            if (!empty($search_keyword)) {
                $query = $query->where('medname', 'LIKE', '%' . $search_keyword . '%');
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
                ->addColumn('medname', function ($row) {
                    return $row->medname;
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
}
