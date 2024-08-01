<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }
    public function myDocuments()
    {
        return view('user-sidebar.my-documents');
    }

    public function myDocuments_sub($type)
    {
        switch ($type) {
            case 'CertificateOfRegistration':
                return view('user-sidebar.my-documents.CertificateOfRegistration');
            case 'GalleryOfCoaches':
                return view('user-sidebar.my-documents.GalleryOfCoaches');
            case 'GalleryOfPlayers':
                return view('user-sidebar.my-documents.GalleryOfPlayers');
            case 'ParentalConsent':
                return view('user-sidebar.my-documents.ParentalConsent');
            case 'SummaryOfPlayers':
                return view('user-sidebar.my-documents.SummaryOfPlayers');
            case 'PhotocopyOfVaccineCard':
                return view('user-sidebar.my-documents.PhotocopyOfVaccineCard');
            case 'PhotocopyOfSchoolID':
                return view('user-sidebar.my-documents.PhotocopyOfSchoolID');
            default:
                return redirect()->route('my-documents');
        }
        //return view('user-sidebar.my-documents.sub',compact('type'));
    }

    public function myCalendar()
    {
        return view('user-sidebar.my-calendar');
    }

    public function myPlayers()
    {
        return view('user-sidebar.my-players');
    }
}
