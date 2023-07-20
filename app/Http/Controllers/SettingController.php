<?php

namespace App\Http\Controllers;

use App\Models\MSetting;
use App\Models\MSitemasterMyschool;
use App\Models\MSitemasterPublic;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function upload(Request $request)
    {
        // dd($request);
        $new = new MSitemasterMyschool();
        $id = 1;

if ($request->logo == null && $request->favicon == null) {
        $request->validate([
            'sitename' => 'required',
            'facebook' => 'required|url',
            'youtube1' => 'required|url',
            'youtube2' => 'required|url',
            'youtube3' => 'required|url',
            'messenger' => 'required|url',
            'address' => 'required',
            'email' => 'required'
        ]);
    $mSiteMaster = MSitemasterMyschool::find($id);
    $sessionId = session()->get('adminId');
    $mSiteMaster->sitename = $request->sitename;
    $mSiteMaster->facebook_link = $request->facebook;
    $mSiteMaster->youtube_link1 = $request->youtube1;
    $mSiteMaster->youtube_link2 = $request->youtube2;
    $mSiteMaster->youtube_link3 = $request->youtube3;
    $mSiteMaster->messanger_link1 = $request->messenger;
    $mSiteMaster->address = $request->address;
    $mSiteMaster->email = $request->email;
    $mSiteMaster->updated_by = $sessionId;
    $mSiteMaster->save();
    return Redirect::route('setting.index')->with('message', 'success');
}

$request->validate([
    'logo' => 'mimes:png,jpg,jpeg,csv,svg',
    'favicon' => 'mimes:png,jpg,jpeg,csv,svg',
    'sitename' => 'required',
    'facebook' => 'required|url',
    'youtube1' => 'required|url',
    'youtube2' => 'required|url',
    'youtube3' => 'required|url',
    'messenger' => 'required|url',
    'address' => 'required',
    'email' => 'required'
]);

if ($request->hasFile('logo') && $request->hasFile('favicon')) {
            $logoFile = $request->file('logo');
            $faviconFile = $request->file('favicon');
            $image = $new->idUpdateOrgData();
            $old_photo = explode('/', $image[0]->logo);
            
            $oldfav_photo = explode('/', $image[0]->favicon);
            Storage::disk('digitalocean')->delete('settings/system/' . $old_photo[5]);
            Storage::disk('digitalocean')->delete('settings/system/' . $oldfav_photo[5]);
            $logo =  Storage::disk('digitalocean')->put('settings/system', $logoFile, 'public');
            $favicon =  Storage::disk('digitalocean')->put('settings/system', $faviconFile, 'public');
            if ($logo && $favicon) {
                $new->uploadSetting($request, $logo, $favicon, $id);
                return Redirect::route('setting.index')->with('message', 'success');
            }
            return Redirect::route('setting.index')->with('message', 'fail');
}
    }

    public function upload_public(Request $request)
    {
        $id = 1;
        // dd($request);   

        if ($request->logos == null && $request->favicons == null && $request->darklogo == null) {
            $request->validate([    
                'phone' => 'required|numeric|digits_between:9,13 ',
                'sitenames' => 'required',
                'facebook' => 'required|url',
                'youtube1' => 'required|url',
                'youtube2' => 'required|url',
                'copyright' => 'required'
            ]);
            $sessionId = session()->get('adminId');
            $mSiteMaster = MSitemasterPublic::find($id);
            $mSiteMaster->sitename =$request->sitenames;
            $mSiteMaster->facebook_link = $request->facebook;
            $mSiteMaster->youtube_link1 = $request->youtube1;
            $mSiteMaster->youtube_link2 = $request->youtube2;
            $mSiteMaster->copyright = $request->copyright;
            $mSiteMaster->phones = $request->phone;
            $mSiteMaster->updated_by =$sessionId;
            $mSiteMaster->save();
            return Redirect::route('setting.index')->with('message', 'success');
        }
        $request->validate([
            'logos' => 'mimes:png,jpg,jpeg,csv,svg',
            'favicons' => 'mimes:png,jpg,jpeg,csv,svg',
            'phone' => 'required|numeric|digits_between:9,13 ',
            'sitenames' => 'required',
            'facebook' => 'required|url',
            'youtube1' => 'required|url',
            'youtube2' => 'required|url',
            'copyright' => 'required'
        ]);
        $new = new MSitemasterPublic();

        if ($request->hasFile('logos') && $request->hasFile('favicons') && $request->hasFile('darklogo')) {
            $logoFile = $request->file('logos');
            $faviconsFile = $request->file('favicons');
            $darklogoFile = $request->file('darklogo');
            $image = $new->uplaod_public();
            dd($image);
            $old_photo = explode('/', $image[0]->logo);
            $oldfav_photo = explode('/', $image[0]->favicon);
            
            $olddark_photo = explode('/', $image[0]->dark_logo);

            Storage::disk('digitalocean')->delete('settings/public/darkmode/' . $olddark_photo[6]);
            Storage::disk('digitalocean')->delete('settings/public/lightmode/' . $old_photo[6]);
            Storage::disk('digitalocean')->delete('settings/public/' . $oldfav_photo[5]);
            $logos = Storage::disk('digitalocean')->put('settings/public/lightmode', $logoFile, 'public');
            $favicons = Storage::disk('digitalocean')->put('settings/public', $faviconsFile, 'public');
            $darklogo = Storage::disk('digitalocean')->put('settings/public/darkmode', $darklogoFile, 'public');
            if ($logos && $favicons && $darklogo) {
                $new->uploadpublicSetting($request, $logos, $favicons, $darklogo, $id);
                return Redirect::route('setting.index')->with('message', 'success');
            }
            return Redirect::route('setting.index')->with('message', 'fail');
        }
    }
    public function index()
    {
        $new = new MSitemasterMyschool();
        $uploadMysch  = $new->idUpdateOrgData();

        $s_public = new MSitemasterPublic();
        $upload_pub = $s_public->uplaod_public();

        $characterLength = MSitemasterMyschool::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 'm_sitemaster_myschools')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();

        $publicLength = MSitemasterPublic::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 'm_sitemaster_publics')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();

        $sec = session()->get('roleId');
        return inertia('SettingAdmin', ['mysch' => $uploadMysch, 'public' => $upload_pub, 'sessionId' => $sec, 'charLength' => $characterLength, 'pcharLength' => $publicLength]);
    }
}
