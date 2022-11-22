<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * The number of credentials to create a admin user.
     */
    const QUANTITY_ADMIN_CREDENTIALS = 3;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countCredentials = 0;

        if ( config('app.ADMIN_NAME') === null ) {
            echo 'Warning: There is no username in the .env file for the admin user.' . PHP_EOL;
        } else {
            $countCredentials++;
        }

        if ( config('app.ADMIN_EMAIL') === null ) {
            echo 'Warning: In the .env file, the email address is not specified for the admin user.' . PHP_EOL;
        } else {
            $countCredentials++;
        }

        if ( config('app.ADMIN_PASSWORD') === null ) {
            echo 'Warning: There is no password in the environment file for the admin user.' . PHP_EOL;
        } else {
            $countCredentials++;
        }

        if ( $countCredentials === self::QUANTITY_ADMIN_CREDENTIALS ) {

            User::on()->updateOrCreate(['email' => config('app.ADMIN_EMAIL')],
                [
                    'name' => config('app.ADMIN_NAME'),
                    'email' => config('app.ADMIN_EMAIL'),
                    'password' => bcrypt(config('app.ADMIN_PASSWORD')),
                ]);

            echo 'Admin user created successfully!' . PHP_EOL;

        } else {
            echo 'Error: Admin user has not been created. Update your .env file.' . PHP_EOL;
        }
    }
}
