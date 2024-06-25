<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Location of the Excel file
        $excelFile = storage_path('app/public/usersHRIS2.xlsx');

        // Reading the Excel file
        $spreadsheet = IOFactory::load($excelFile);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Iterating through each row from the Excel file
        foreach ($rows as $row) {
            // Check if the row is not empty
            if (!empty($row[0]) && !empty($row[1]) && !empty($row[2])) {
                // Create a new user
                $user = new User();
                $user->id = Str::uuid(); // Use UUID as user ID
                $user->name = $row[0]; // Name column
                $user->email = $row[1]; // Email column
                $user->password = Hash::make($row[2]); // Hash the password
                $user->save();

                // Assign role
                $user->assignRole('karyawan');
            }
        }
    }
}
