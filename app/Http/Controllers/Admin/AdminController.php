<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminsRole;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminController extends Controller
{
    public function dashboard()
    {
        Session::put('page', 'dashboard');
        return view('admin.dashboard');
    }

    public function login(Request $request): Factory|View|Redirector|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $request->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|max:30',
            ]);

            if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                // Remember Admin Email and Password with cookies
                if (isset($data['remember']) && !empty($data['remember'])) {
                    setcookie('email', $data['email'], time() + 3600);
                    setcookie('password', $data['password'], time() + 3600);
                } else {
                    setcookie('email', "");
                    setcookie('password', "");
                }

                return redirect('admin/dashboard');
            } else {
                return $this->backWithMessage('error_message', 'Invalid Email or Password.');
            }
        }

        return view('admin.login');
    }

    public function logout(): Redirector|RedirectResponse
    {
        Auth::guard('admin')->logout();

        return redirect('admin/login');
    }

    public function updatePassword(Request $request): View|Factory|RedirectResponse
    {
        Session::put('page', 'update-password');
        if ($request->isMethod('post')) {
            $data = $request->all();
            // Check if current password is correct
            if (Hash::check($data['current_pwd'], $this->getGuardUser()->password)) {
                // Check new Password and Confirm Password
                if ($data['new_pwd'] == $data['confirm_pwd']) {
                    // Update new Password
                    Admin::where('id', $this->getGuardUser()->id)
                        ->update(['password' => Hash::make($data['new_pwd'])]);

                    return $this->backWithMessage('success_message', 'Password updated Successfully.');
                } else {
                    return $this->backWithMessage('error_message', 'New Password and Confirm Password does not match.');
                }
            } else {
                return $this->backWithMessage('error_message', 'Your Current Password Incorrect.');
            }
        }

        return view('admin.update_password');
    }

    public function checkCurrentPassword(Request $request): string
    {
        $data = $request->all();
        if (Hash::check($data['current_pwd'], $this->getGuardUser()->password)) {
            return "true";
        } else {
            return "false";
        }
    }

    public function updateDetails(Request $request)
    {
        Session::put('page', 'update-details');
        if ($request->isMethod('post')) {
            $data = $request->all();

            $request->validate([
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'admin_mobile' => 'required|numeric|digits:10',
                'admin_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Upload Admin Image
            if ($request->hasFile('admin_image')) {
                $image_tmp = $request->file('admin_image');
                if ($image_tmp->isValid()) {
                    // Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    // create image manager with desired driver
                    $manager = new ImageManager(new Driver());
                    // open an image file
                    $image = $manager->read($image_tmp);
                    // Generate New Image Name
                    $imageName = rand(11111, 99999) . '.' . $extension;
                    $imagePath = 'admin/img/photos/' . $imageName;
                    $image->save($imagePath);

                }
            } else if (!empty($data['current_image'])) {
                $imageName = $data['current_image'];
            } else {
                $imageName = "";
            }

            // Update Admin Details
            Admin::where('email', $this->getGuardUser()->email)
                ->update([
                'name' => $data['admin_name'],
                'mobile' => $data['admin_mobile'],
                'image' => $imageName,
            ]);

            return $this->backWithMessage('success_message', 'Admin details updated successfully.');
        }

        return view('admin.update_details');
    }

    public function subadmins()
    {
        Session::put('page', 'subadmins');
        $subadmins = Admin::where('type', 'subadmin')->get();
        return view('admin.subadmins.subadmins', compact('subadmins'));
    }

    public function updateSubadminStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
//            dd($data);
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
        }

        Admin::where('id', $data['subadmin_id'])->update(['status' => $status]);

        return response()->json(['status' => $status, 'subadmin_id' => $data['subadmin_id']]);
    }

    public function addEditSubadmin(Request $request, $id = null)
    {
        //Session::put('page', 'cms-pages');
        if ($id == "") {
            $title = "Add Subadmin";
            $subAdminData = new Admin();
            $message = "Subadmin added successfully.";
        } else {
            $title = "Edit Subadmin";
            $subAdminData = Admin::find($id);
            $message = "Subadmin updated successfully.";
        }

        if ($request->isMethod("post")) {
            $data = $request->all();

            if ($id == "") {
                $subAdminCount = Admin::where('email', $data['email'])->count();
                if ($subAdminCount > 0) {
                    return $this->backWithMessage('error_message', 'Subadmin already exists.');
                }
            }

            // Subadmin Validations
            $request->validate([
                'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'mobile' => 'required|numeric|digits:10',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Upload Admin Image
            if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {
                    // Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    // create image manager with desired driver
                    $manager = new ImageManager(new Driver());
                    // open an image file
                    $image = $manager->read($image_tmp);
                    // Generate New Image Name
                    $imageName = rand(11111, 99999) . '.' . $extension;
                    $imagePath = 'admin/img/photos/' . $imageName;
                    $image->save($imagePath);

                }
            } else if (!empty($data['current_image'])) {
                $imageName = $data['current_image'];
            } else {
                $imageName = "";
            }

            $subAdminData->image = $imageName;
            $subAdminData->name = $data['name'];
            $subAdminData->mobile = $data['mobile'];
            if ($id == "") {
                $subAdminData->email = $data['email'];
                $subAdminData->type = 'subadmin';
            }
            if ($data['password'] != "") {
                $subAdminData->password = Hash::make($data['password']);
            }
            $subAdminData->status = 1;

            if ($subAdminData->save()) {
                return redirect('admin/subadmins')->with('success_message', $message);
            }
        }

        return view('admin.subadmins.add_edit_subadmin', compact('title', 'subAdminData'));
    }

    public function deleteSubadmin($id)
    {
        Admin::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Sub Admin deleted successfully.');
    }

    public function updateRole($id, Request $request)
    {
        if ($request->isMethod("post")) {
            $data = $request->all();

            // Delete all earlier roles for Subadmin
            AdminsRole::where('subadmin_id', $id)->delete();

            // Add new roles for Subadmin Dynamically
            $recordRole = new AdminsRole();
            foreach ($data as $key => $value) {
                $recordRole->view_access = $value['view'] ?? 0;
                $recordRole->edit_access = $value['edit'] ?? 0;
                $recordRole->full_access = $value['full'] ?? 0;
            }
            $recordRole->subadmin_id = $id;
            $recordRole->module = $key;

            if ($recordRole->save()) {
                $this->backWithMessage('success_message', 'Sub Admin Roles updated successfully.');
            }
        }

        $subadminRoles = AdminsRole::where('subadmin_id', $id)->get()->toArray();
        $subadminDetails = Admin::where('id', $id)->first();
        $title = "Update " . $subadminDetails->name . " Sub Admin Role/Permissions";

        return view('admin.subadmins.update_roles', compact('title', 'id', 'subadminRoles'));
    }

    private function backWithMessage(string $message, string $title): RedirectResponse
    {
        return redirect()->back()->with($message, $title);
    }

    private function getGuardUser(): ?Authenticatable
    {
        return Auth::guard('admin')->user();
    }
}
