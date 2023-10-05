<?php

namespace Database\Seeders;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $pass = Str::random(12); // new password each time

        Admin::create([
            'name' => 'Admin',
            'email' => config('app.name') . '@owlweb.com.ua',
            'email_verified_at' => now(),
            'phone' => '380000000000',
            'phone_verified_at' => now(),
            'status' => 1,
            'password' => bcrypt($pass), //
            'remember_token' => Str::random(10),
        ]);

        // output to console password
        $output = new \Symfony\Component\Console\Output\ConsoleOutput(2);
        $output->writeln("Admin password: " . $pass);

        if (isset($this->command)) {
            $this->command->getOutput()->writeln("<bg=magenta;options=bold>ADMIN PASSWORD:</> " . $pass);
        }
    }
}
