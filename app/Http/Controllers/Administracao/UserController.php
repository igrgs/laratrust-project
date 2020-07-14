<?php

namespace App\Http\Controllers\Administracao;

use App\Role;
use App\Team;
use App\User;
use Response;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ListUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\AppBaseController;

class UserController extends AppBaseController
{
    /** @var $userRepository UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(ListUserRequest $request)
    {
        $users = User::with('roles')->get();

        return view('administracao.users.index')->with(compact('users'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        if (!Auth::user()->hasRole('superadministrador')) {
            $roles = Role::whereNotIn('name', ['superadministrador'])->get();
        } else {
            $roles = Role::all();
        }
        $teams = Team::pluck('display_name', 'id');
        return view('administracao.users.create', ['roles' => $roles, 'teams' => $teams]);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $team = $input['team'];
        $input['password'] = Hash::make($input['password']);
        $user = $this->userRepository->create($input);
        $user->attachRoles(array_keys($request->input('roles', [])), $team);
        Flash::success('User saved successfully.');

        return redirect(route('administracao.users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('administracao.users.index'));
        }

        return view('administracao.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        if (!Auth::user()->hasRole('superadministrador')) {
            $roles = Role::whereNotIn('name', ['superadministrador'])->get();
        } else {
            $roles = Role::all();
        }
        $teams = Team::pluck('display_name', 'id');
        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('administracao.users.index'));
        }

        return view('administracao.users.edit', compact('user', 'roles', 'teams')); //->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('administracao.users.index'));
        }
        $input =  $request->all();
        $team = $input['team'];
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }
        $user = $this->userRepository->update($input, $id);
        $user->syncRoles(array_keys($request->input('roles', [])), $team);
        Flash::success('User updated successfully.');

        return redirect(route('administracao.users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('administracao.users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('administracao.users.index'));
    }
}
