<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medication;

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
}
