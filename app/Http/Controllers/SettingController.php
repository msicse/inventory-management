<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use Image;
use Toastr;
use App\Models\User;
use App\Models\Employee;
use App\Models\Transection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingController extends Controller
{
    public function profile(Request $request)
    {
        
        
        if ($request->method() == "POST"){

            //return $request->all();
            
            $this->validate($request, array(
                'name' => 'required|max:255',
                'about' => '',
            ));

            $user = User::where('id', Auth::user()->id)->first();

            $slug  = Str::slug(Auth::user()->username);
    
            if($request->hasFile('image')) {

                $employee = Employee::where('emply_id', Auth::user()->employee_id)->first();

                $upload_image = $request->file('image');
                $filename = $slug . "-" . time().".".$upload_image->getClientOriginalExtension();


                $manager = new ImageManager(new Driver());
                $image = $manager->read($upload_image);
                $image->resize(400, 400);
                $image->save('images/employee/' . $filename);

                if (file_exists('images/employee/' . $employee->image)) {
                    unlink('images/employee/' . $employee->image);
                }

            }else {
                $filename = Auth::user()->profile->image;
            }

            $user->name     = $request->name;
            $user->save();

            Toastr::success(' Succesfully Updated ', 'Success');
            return redirect()->back();



        } else {
            return view('backend.settings.profile');

        }


    }


    public function password(Request $request)
    {
        
        
        if ($request->method() == "POST"){

            $this->validate($request, array(
                'current_password'  => 'required|min:6',
                'new_password'          => 'required|min:6',
                'confirm_password'  => 'required|same:new_password|min:6',
            ));
    
                if (Hash::check($request->current_password, Auth::user()->password)) {
                    $user = Auth::user();
                    $user->password     = Hash::make($request->new_password);
                    $user->save();
                    Toastr::success(' Succesfully Updated ', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error(' Current Password not Match ', 'Error');
                    return redirect()->back();
                }
        } else {
            return view('backend.settings.password');

        }


    }
    
    public function policy($id)
    {
        
        $data = Transection::find($id);
        return view('backend.settings.policy')->with(compact('data'));

        
    }
}
