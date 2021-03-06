<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\model\DeveloperInfo;
use Illuminate\Http\Request;
use Auth;
use Session;

class DeveloperInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['developers']=DeveloperInfo::all();
        $data['users']=DeveloperInfo::count();
        return view('backend.developerInfo.developer-view',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('backend.developerInfo.create-developer');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'profession' => 'required',
            'short_description' => 'required',

        ]);
       $developerData=new DeveloperInfo();
       $developerData->name=$request->name;
       $developerData->profession=$request->profession;
       $developerData->short_description=$request->short_description;
       if($request->hasFile('image')){
        $file=$request->file('image');
        $extension=$file->getClientOriginalExtension();
        $newImage=time().'.'.$extension;
        $file->move('upload/user_images/',$newImage);
        $developerData->image=$newImage;
    }else{
        return $request;
        $developerData->image='';
    }
       $developerData->save();
       Session::flash('success','Data Inserted successfully');
       return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $developerId=DeveloperInfo::find($id);
        return view('backend.developerInfo.edit-developer',compact('developerId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update=DeveloperInfo::find($id);
        $update->name=$request->name;
        $update->profession=$request->profession;
        $update->short_description=$request->short_description;
        $update->image=$request->image;
        if($request->hasFile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $myImage=time().'.'.$extension;
            $file->move('upload/user_images/',$myImage);
            $update->image=$myImage;
        }
        $update->save();
        Session::flash('success','Data Updated successfully');
       return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=DeveloperInfo::find($id);
        $data->delete();
       return redirect()->route('developerInfo.view');
    }
}
