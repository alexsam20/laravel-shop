<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Image;
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

    private function backWithMessage(string $message, string $title): RedirectResponse
    {
        return redirect()->back()->with($message, $title);
    }

    private function getGuardUser(): ?Authenticatable
    {
        return Auth::guard('admin')->user();
    }
}
