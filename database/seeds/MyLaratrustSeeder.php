<?php

use App\Role;
use App\Team;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class MyLaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Truncating  Role and Permission tables');
        $this->truncateLaratrustTables();

        $teams = [
            'administracao',
            'instituicao',
            'bolsista',
        ];

        $configAdministracao =
            [
                'superadministrador' => [
                    'audits' => 'r,l,i,e,p,up,dw',
                    'users' => 'c,r,u,d,l',
                    'profiles' => 'r,u',
                    'my_profile' => 'c,r,u'
                ],

                'administrador' => [
                    'users' => 'c,r,u,d,l',
                    'profiles' => 'r,u',
                    'my_profile' => 'c,r,u'

                ],

                'developer' => [
                    'teams' => 'r,l,i,e,p,up,dw',
                    'roles' => 'r,l,i,e,p',
                    'permissions' => 'r,l,i,e,p',
                    'users' => 'r',
                    'profiles' => 'r',
                    'my_profile' => 'c,r,u'

                ],


            ];

        $configInstituicao =
            [
                'owner' => [
                    'users' => 'c,r,u,d,l',
                    'profiles' => 'r,u',
                    'my_profile' => 'c,r,u'

                ],
                'financeiro' => [
                    'my_profile' => 'c,r,u'

                ],


            ];

        $configBolsista =
            [
                'user' => [
                    'my_profile' => 'c,r,u'

                ],

            ];


        $permision_map =
            [
                'c' => 'create',
                'r' => 'read',
                'u' => 'update',
                'd' => 'delete',
                'l' => 'list',
                'i' => 'import',
                'e' => 'export',
                'p' => 'print',
                'up' => 'upload',
                'dw' => 'download'
            ];
        $mapPermission = collect($permision_map);

        //name is access key for team
        //display_name is a human redable name, it contains a tranlatable text
        //  'acl/teams.'.$key.'.display_name'
        foreach ($teams as $team) {
            $oTeam = \App\Team::create([
                'name' => $team,
                'display_name' => ucwords(str_replace('_', ' ', $team)),
                'description' => ucwords(str_replace('_', ' ', $team)),
            ]);

            if ($team == 'administracao') {
                $config = $configAdministracao;
            } else {
                if ($team == 'instituicao') {
                    $config = $configInstituicao;
                } else {
                    $config = $configBolsista;
                }
            }

            foreach ($config as $role => $modules) {

                // Create a new role
                $oRole = \App\Role::create([
                    'name' => $role,
                    'display_name' => ucwords(str_replace('_', ' ', $role)),
                    'description' => ucwords(str_replace('_', ' ', $role)),
                ]);
                $permissions = [];

                $this->command->info('Creating Role ' . strtoupper($role));

                // Reading role permission modules
                foreach ($modules as $module => $value) {

                    foreach (explode(',', $value) as $p => $perm) {

                        $permissionValue = $mapPermission->get($perm);

                        $permissions[] = \App\Permission::firstOrCreate(
                            [
                                'name' => $module . '-' . $permissionValue
                            ],
                            [
                                'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                                'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            ]
                        )->id;

                        $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
                    }
                }

                // Attach all permissions to the role
                $oRole->permissions()->sync($permissions);
            }
        }

        $this->command->info("Creating user");

        // Create default user for each role

        $team = Team::where('name', 'administracao')->first();
        $role = Role::where('name', 'superadministrador')->first();

        /**
         * Se desejar modifique aqui o usuário master
         */
        $user = \App\User::firstOrCreate(
            ['email' => 'super@teste.com.br'],
            [
                'name' => 'Super Admin System',
                'password' => Hash::make('password'),
                'email_verified_at' => Carbon::now(),
            ]
        );

        $user->attachRole($role, $team);
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();

        if (Config::get('database.default') == 'pgsql') {
            DB::table('permission_role')->truncate();
            DB::table('permission_user')->truncate();
            DB::table('role_user')->truncate();
            $teamsTable = (new \App\Team)->getTable();
            $rolesTable = (new \App\Role)->getTable();
            $permissionsTable = (new \App\Permission)->getTable();
            DB::statement("TRUNCATE TABLE {$teamsTable} CASCADE");
            DB::statement("TRUNCATE TABLE {$rolesTable} CASCADE");
            DB::statement("TRUNCATE TABLE {$permissionsTable} CASCADE");
        } else {

            DB::table('permission_role')->truncate();
            DB::table('permission_user')->truncate();
            DB::table('role_user')->truncate();
            \App\Team::truncate();
            \App\Role::truncate();
            \App\Permission::truncate();
        }
        Schema::enableForeignKeyConstraints();
    }
}
