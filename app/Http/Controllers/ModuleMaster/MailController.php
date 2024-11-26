<?php

namespace App\Http\Controllers\ModuleMaster;

use Illuminate\Routing\Controller;

use App\Mail\DownloadAttachementMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendCSV(Request $request)
    {
        $message = Mail::to($request->to);

        if (isset($request->cc))
            $message->cc($request->cc);

        if (isset($request->bcc))
            $message->bcc($request->bcc);

        $message->send(new DownloadAttachementMail($request->csvfile, $request->subject, $request->body));

        return redirect()->back();
    }
}
