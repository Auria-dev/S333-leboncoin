<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function privacy() { return view('legal.privacy'); }
    public function cookies() { return view('legal.cookies'); }
    public function mentions() { return view('legal.mentions'); }
    public function cgu() { return view('legal.cgu'); }
    public function about() { return view('legal.about'); }
    public function contact() { return view('legal.contact'); }
    public function regles() { return view('legal.regles'); }
    public function engagements() { return view('legal.engagements'); }
    public function securite() { return view('legal.securite'); }
     
}