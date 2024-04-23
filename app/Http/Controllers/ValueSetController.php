<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValueSet;

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
                ValueSet::create([
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
}
