<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\QueryException;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRolesRequest;
use App\Http\Requests\Admin\UpdateRolesRequest;

class RolesController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }

//        $roles = Role::paginate(5);
//
//        return view('admin.roles.index', compact('roles'));


        $page = request('pageSize');
        $name = request('name');

        ## GET ALL CATEGORIES PARENTS
        $query = Role::where('id','!=',1)->select();
//        $categories = Category::paginate($pageSize);


        if ($name != '') {
            $query->where('name', 'like', "%$name%");
        }

        //$roles = $query->paginate(($page) ?: 10);
        
        $roles = $query->orderBy('created_at','Desc')->get();


        // if ($name != '') {
        //     $roles->setPath('roles?name=' . $name);
        // } else {
        //     $roles->setPath('roles');
        // }


        if ($request->ajax()) {
            return view('admin.roles.load', ['roles' => $roles])->render();
        }

        ## SHOW CATEGORIES LIST VIEW WITH SEND CATEGORIES DATA.
        return view('admin.roles.index')
            ->with(compact('roles'));


    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }

        $abilities = Ability::get();
//        $abilities = Ability::get()->pluck('name', 'name');


        $abilities = $abilities->filter(function ($q) {
            return $q->name != '*';
        });


        return view('admin.roles.create', compact('abilities'))->render();


    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreRolesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRolesRequest $request)
    {

        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }

        $collection = collect($request->input('abilities'));

        $abilites = $collection->filter(function ($value) {
            return $value != "*";
        })->values();


        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        try {
            $role = Role::create($request->all());

            $role->allow($abilites);


            session()->flash('success', 'لقد تم إضافة دور جديد للمستخدمين بنجاح.');

            return redirect(route('roles.index'));

        } catch (QueryException $e) {

            if ($e->getCode() === '23000') {
                session()->flash('error', 'هذا الدور موجود من قبل.');
                return redirect()->back()->withInput();


            }


        }


//        return redirect()->route('admin.roles.index');
    }


    /**
     * Show the form for editing Role.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }

        $abilities = Ability::get();
//        $abilities = Ability::get()->pluck('id', 'name');

        $abilities = $abilities->filter(function ($q) {
            return $q->name != '*';
        });


        $role = Role::findOrFail($id);

        return view('admin.roles.edit', compact('role', 'abilities'));
    }

    /**
     * Update Role in storage.
     *
     * @param  \App\Http\Requests\UpdateRolesRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRolesRequest $request, $id)
    {
        
        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }
        $role = Role::findOrFail($id);
        $role->update($request->all());


        foreach ($role->getAbilities() as $ability) {
            $role->disallow($ability->name);
        }
        $role->allow($request->input('abilities'));



        session()->flash('success', "لقد تم تعديل الدور  ($role->name) بنجاح");

        return redirect(route('roles.index'));
    }


    /**
     * Remove Role from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }

        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.roles.index');
    }


    /**
     * Remove Role from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }

        $role = Role::findOrFail($request->id);
        $role->delete();

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ]
        ]);
    }


    function filter(Request $request)
    {

        $name = $request->keyName;

        $page = $request->pageSize;

        ## GET ALL CATEGORIES PARENTS
        $query = Role::select();
        // $categories = Category::paginate($pageSize);


        if ($name != '') {
            $query->where('name', 'like', "%$name%");
        }

        $query->orderBy('created_at', 'DESC');
        $roles = $query->paginate(($page) ?: 10);

        if ($name != '') {
            $roles->setPath('roles?name=' . $name);
        } else {
            $roles->setPath('roles');
        }


        if ($request->ajax()) {
            return view('admin.roles.load', ['roles' => $roles])->render();
        }
        ## SHOW CATEGORIES LIST VIEW WITH SEND CATEGORIES DATA.
        return view('admin.roles.index')
            ->with(compact('users'));
    }


    /**
     * Remove User from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function groupDelete(Request $request)
    {

        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {
            $role = Role::findOrFail($id);
            $role->delete();
        }


        return response()->json([
            'status' => true,
            'data' => [
                'id' => $request->id
            ]
        ]);
    }


    /**
     * Delete all selected Role at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('admin_manage')) {
            return abort(401);
        }
        
        if ($request->input('ids')) {
            $entries = Role::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
