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

class AdminController extends Controller
{
    public function dashboard()
    {
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
        if ($request->isMethod('post')) {
            $data = $request->all();

            $request->validate([
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'admin_mobile' => 'required|numeric|digits:10',
            ]);

            // Update Admin Details
            Admin::where('email', $this->getGuardUser()->email)
                ->update([
                'name' => $data['admin_name'],
                'mobile' => $data['admin_mobile']
            ]);

            return $this->backWithMessage('success_message', 'Admin details updated successfully.');


            /*if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                return redirect('admin/dashboard');
            } else {
                return $this->backWithMessage('error_message', 'Invalid Email or Password.');
            }*/
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
