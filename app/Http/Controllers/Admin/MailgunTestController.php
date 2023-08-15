<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\SendMailGunTemplate;
use Illuminate\Http\Request;

class MailgunTestController extends Controller
{
    private $mailer;

    public function __construct(SendMailGunTemplate $sendMailGunTemplate)
    {
        $this->mailer = $sendMailGunTemplate;
    }

    public function index()
    {
        return view('admin.mailgun-test.index');
    }

    public function send(Request $request)
    {
        $res = $this->mailer->sendMail($request->get('from'),$request->get('to'),$request->get('subject'),'showcase_mailgun_template',$request->get('vars'));

        return redirect()->back()->with('success','ID:' . $res->getId(). ' message:' . $res->getMessage());
    }

}
