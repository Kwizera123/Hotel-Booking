<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Models\BookArea;

class TeamController extends Controller
{
    public function AllTeam(){

        $team = Team::latest()->get();
        return view('backend.team.all_team',compact('team'));
    }// End of Method

    public function AddTeam(){
        return view('backend.team.add_team');
    }// End of Method

    public function StoreTeam(Request $request){
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(550,670)->save('upload/team/'.$name_gen);
        $save_url = 'upload/team/'.$name_gen;

        Team::insert([
            'name' => $request->name,
            'position' => $request->position,
            'facebook' => $request->facebook,
            'image' => $save_url,
            'tweeter' => $request->tweeter,
            'instagram' => $request->instagram,
            'pinterest' => $request->pinterest,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Team Data have been saved Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.team')->with($notification);
    }// End of Method

    public function EditTeam($id){

        $item = Team::findOrFail($id);
        return view('backend.team.edit_team',compact('item'));
    }// End Method

    public function UpdateTeam(Request $request){
        $team_id = $request->id;

        if($request->file('image')){

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(550,670)->save('upload/team/'.$name_gen);
            $save_url = 'upload/team/'.$name_gen;
    
            Team::findOrFail($team_id)->update([
                'name' => $request->name,
                'position' => $request->position,
                'facebook' => $request->facebook,
                'image' => $save_url,
                'tweeter' => $request->tweeter,
                'instagram' => $request->instagram,
                'pinterest' => $request->pinterest,
                'created_at' => Carbon::now(),
            ]);
    
            $notification = array(
                'message' => 'Team Update with Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.team')->with($notification);
        }else{

            Team::findOrFail($team_id)->update([
                'name' => $request->name,
                'position' => $request->position,
                'facebook' => $request->facebook,
                'tweeter' => $request->tweeter,
                'instagram' => $request->instagram,
                'pinterest' => $request->pinterest,
                'created_at' => Carbon::now(),
            ]);
    
            $notification = array(
                'message' => 'Team Update without Image Successfully',
                'alert-type' => 'info'
            );
    
            return redirect()->route('all.team')->with($notification);

        }// if condition ends
    }// End Method

    public function DeleteTeam($id){

        $item = Team::findOrFail($id);
        $img = $item->image;
        unlink($img);

        Team::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Team Image Deleted Successfully',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);

    }// End Method

    // ----------------------------BOOK AREA------------------------------
    public function BookArea(){
        $book = BookArea::find(1);
        return view('backend.bookarea.book_area',compact('book'));
    }// End Method

    public function BookAreaUpdate(Request $request){

        $book_id = $request->id;

        if($request->file('image')){

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(1000,1000)->save('upload/bookarea/'.$name_gen);
            $save_url = 'upload/bookarea/'.$name_gen;
    
            BookArea::findOrFail($book_id)->update([
                'short_title' => $request->short_title,
                'main_title' => $request->main_title,
                'short_desc' => $request->short_desc,
                'link_url' => $request->link_url,
                'image' => $save_url,
            
            ]);
    
            $notification = array(
                'message' => 'Book Area Update with Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->back()->with($notification);
        }else{

            BookArea::findOrFail($book_id)->update([
                'short_title' => $request->short_title,
                'main_title' => $request->main_title,
                'short_desc' => $request->short_desc,
                'link_url' => $request->link_url,
                'created_at' => Carbon::now(),
            ]);
    
            $notification = array(
                'message' => 'Book Area Update without Image Successfully',
                'alert-type' => 'info'
            );
    
            return redirect()->back()->with($notification);

        }// if condition ends

    }// End Method
}
