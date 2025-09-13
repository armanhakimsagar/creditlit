<?php
namespace App\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Image;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    // To show all Role
    public function index(Request $request)
    {
        $data = DB::table('users')
            ->leftjoin('roles', 'users.roles_id', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('user.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('user.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
                    return $action_btn;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == 'inactive') {
                        return '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        return '<span class="badge badge-danger">Cancel</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('User::user.index');
    }

    // To show Student create page
    public function create()
    {
        $addPage = "Add User";
        $roles = ['' => 'Please Select A Role'] + Role::pluck('name', 'id')->all();
        return view("User::user.create", compact('roles', 'addPage'));
    }

    //  To show Studen store page
    public function store(Request $request)
    {
        // Validation Data
        $request->validate([
            'email' => 'max:100|email|unique:users,email',
            'mobile_no' => 'max:16|unique:users,mobile_no',
            'password' => 'min:6',
        ]);

        DB::beginTransaction();
        try {
            //Student Imagge processing
            if ($request->photo) {
                $photo = $request->photo;
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/users/' . $photoName);

            } else {
                $photoName = 'profile.png';
            }

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile_no = $request->mobile_no;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->expiry_date = $request->expiry_date;
            $user->status = $request->status;
            $user->type = 'admin';
            $user->roles_id = $request->role_name;
            $user->image = $photoName;
            $user->save();
            if ($request->role_name) {
                $user->assignRole($request->role_name);
            }
            DB::commit();
            Session::flash('success', "User Created Successfully ");
            return redirect()->route('user.index');
        } catch (\Exception$e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To show Student create page
    public function edit($id)
    {
        $editPage = "Edit User";
        $roles = ['' => 'Please Select A Role'] + Role::pluck('name', 'id')->all();
        $user = DB::table('users')
            ->where('users.id', $id)
            ->join('roles', 'users.roles_id', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->first();
        return view("User::user.edit", compact('roles', 'editPage', 'user'));
    }

    // To edit user profile as user
    public function profileEdit($id){
        $editPage = "Edit Profile";
        $roles = ['' => 'Please Select A Role'] + Role::pluck('name', 'id')->all();
        $user = DB::table('users')
            ->where('users.id', $id)
            ->join('roles', 'users.roles_id', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->first();
        return view("User::user.edit", compact('roles', 'editPage', 'user'));
    }

    // To update user data
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //Student Imagge processing
            if ($request->photo) {
                $old_photo = $request->old_photo;

                if ($old_photo == "profile.png") {
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/users/' . $photoName);
                } else if (File::exists(base_path() . '/public/backend/images/users/' . $request->old_photo)) {
                    if (File::exists(base_path() . '/public/backend/images/users/' . $request->old_photo)) {
                        unlink(base_path() . '/public/backend/images/users/' . $request->old_photo);
                    }
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/users/' . $photoName);
                } else {
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/users/' . $photoName);
                }
            } else {
                $photoName = $request->old_photo;
            }

            $user = User::find($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile_no = $request->mobile_no;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->expiry_date = $request->expiry_date;
            $user->status = $request->status;
            $user->type = 'admin';
            $user->roles_id = $request->role_name;
            $user->image = $photoName;
            $user->save();
            $user->roles()->detach();
            if ($request->role_name) {
                $user->assignRole($request->role_name);
            }
            DB::commit();
            if($request->page_name == 'Edit Profile'){
                Session::flash('success', "Your Profile Updated Successfully ");
                return redirect()->back();
            }else{
                Session::flash('success', "User Updated Successfully ");
                return redirect()->route('user.index');
            }
        } catch (\Exception$e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // Destroy user
    public function destroy($id)
    {

        $user = User::find($id);
        $user->delete();
        Session::flash('success', "User Deleted Successfully ");
        return redirect()->back();
    }

    // User Password Edit
    public function userPasswordEdit($id)
    {
        $pageTitle = "Password Reset";

        // Find user
        $data = DB::table('users')->where('users.id', $id)
        ->first();

        // If user not found
        if (!isset($data)) {
            Session::flash('danger', 'User not found.');
            return redirect()->route('admin.user.index');
        }

        // Return view
        return view("User::user.password_edit", compact('data', 'pageTitle'));
    }

    // User Password Update
    public function userPasswordUpdate(Request $request, $id)
    {

        
        // Auth checking
        $user = User::findOrFail($id);

        // Get all request
        $request = $request->all();
        

        // Get parameter
        $old_password = $request['old_password'];
        $newPassword = $request['password'];

        if (isset($user)) {
            // Transaction Start
            DB::beginTransaction();

            try {

                if (Hash::check($old_password, $user->password)) {

                    $model = DB::table('users')
                        ->where('id', $id)
                        ->update([
                            'password' => Hash::make($newPassword),
                        ]);

                    DB::commit();
                    Session::flash('message', 'Password Changed Successfully.');
                } else {
                    Session::flash('danger', 'Sorry! password not updated.');
                }
            } catch (\Exception$e) {
                DB::rollback();
                Session::flash('danger', $e->getMessage());
            }

        }
        // Redirect back to last step
        Auth::logout();
        return redirect('/');
    }

}
