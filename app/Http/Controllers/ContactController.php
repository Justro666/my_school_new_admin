<?php

namespace App\Http\Controllers;

use App\Mail\ContactReplyMail;
use App\Models\MContact;
use App\Models\MSitemasterMyschool;
use App\Models\MSitemasterPublic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contact = MContact::paginate(10);
        // dd($contact);

        $unreadcontact = MContact::where('m_contacts.opened', "=", 0)
            ->paginate(10);

        $unreplycontact = MContact::where('m_contacts.reply', "=", null)
            ->paginate(10);

        //  dd($unreadcontact);

        return inertia(
            'Contact',
            [
                'contacts' => $contact,
                'unreadcontact' => $unreadcontact,
                'unreplycontact' => $unreplycontact
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MContact  $mContact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $contact = new MContact;
        $contactDetail = $contact->messageDetail($id);
        // dd($contactDetail);
        if ($contactDetail == null) {
            abort(404);
        }
        $openupdate = MContact::find($id);
        $openupdate->opened = 1;
        $openupdate->save();

        // dd($contactDetail);
        return inertia('ContactReply', ['contactdetail' => $contactDetail]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MContact  $mContact
     * @return \Illuminate\Http\Response
     */
    public function edit(MContact $mContact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MContact  $mContact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'replymessage' => 'required'
        ]);
        // dd($request);

        $dataForMails = MSitemasterMyschool::all();
        $dataForMailsAdditional = MSitemasterPublic::all();

        $logo = $dataForMails[0]->logo;
        $siteName = $dataForMails[0]->sitename;
        $facebook = $dataForMails[0]->facebook_link;
        $youtube1 = $dataForMails[0]->youtube_link1;
        $youtube2 = $dataForMails[0]->youtube_link2;
        $youtube3 = $dataForMails[0]->youtube_link3;
        $address = $dataForMails[0]->address;
        $email = $dataForMails[0]->email;
        $phone = $dataForMailsAdditional[0]->phones;
        $copyright = $dataForMailsAdditional[0]->copyright;

        $data = [
            'logo' => $logo,
            'sitename' => $siteName,
            'facebook' => $facebook,
            'youtube1' => $youtube1,
            'youtube2' => $youtube2,
            'youtube3' => $youtube3,
            'address' => $address,
            'email' => $email,
            'phone' => $phone,
            'copyright' => $copyright,
            'username' => $request->username,
            'emailreceiver' => $request->email,
            'message' => $request->message,
            'replymessage' => $request->replymessage
        ];

        // dd($data);
        try {
            Mail::to($request->email)->send(new ContactReplyMail($data));
            $update = MContact::find($id);
            $update->reply = $request->replymessage;
            $update->save();
            return redirect()->back()->with('mailsent', 'Mail Sent Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('message', 'Mail Could Not Sent Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MContact  $mContact
     * @return \Illuminate\Http\Response
     */
    public function destroy(MContact $mContact)
    {
        //
    }
}
