<?php

namespace App\Http\Controllers;

use App\Pdf;
use App\Template;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class PdfsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;

        if (request()->ajax()) {
            return datatables()->of(Pdf::select([
                'title', 'description', 'table_content', 'table_description', 'invoice_title',
                'id', 'title', 'description', 'table_content', 'table_description', 'invoice_title',
                'invoice_number', 'template'])->where('user_id', '=', $user_id))
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $editUrl = url('/pdf/edit-pdf/' . $data->id);
                    $pdfUrl = url('/pdf/pdf_preview/' . $data->id);
                    $btn = '<a href="' . $editUrl . '" data-toggle="tooltip" data-original-title="Edit" class="edit btn btn-primary btn-sm">Edit</a>';
                    $btn = $btn . ' <a href="' . $pdfUrl . '" data-toggle="tooltip" target="_blank"  data-id="' . $data->id . '" data-original-title="PDF" class="btn btn-primary btn-sm">Preview PDF</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteTodo">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pdf.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $pdf = new Pdf;
        $templates = Template::pluck('template_title', 'id');

        return view('pdf.create-pdf', compact('pdf', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'title' => 'required',
            'description' => 'required',
            'table_content' => 'required',
            'table_description' => 'required',
            'invoice_title' => 'required',
            'invoice_number' => 'required',
            'invoice_image' => 'image',
            'invoice_description' => 'required',
            'template' => 'required',
            'sign' => 'required',
        ]);

        $user_name = Auth::user();
        $user_name = $user_name->name;
        $path = public_path() . "/uploadImage/" . $user_name;
        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }
        $imageName = $path . '.' . $request->invoice_image->getClientOriginalExtension();
        $request->invoice_image->move($path, $imageName);
        $publish_path = URL("/") . "/uploadImage/" . $user_name . "/" . $user_name . "." . $request->invoice_image->getClientOriginalExtension();
        $pdf = new Pdf();
        $user = Auth::user();
        $user_id = $user->id;
        $template_id = $request['template'];
        $pdf->title = $request['title'];
        $pdf->description = $request['description'];
        $pdf->table_content = $request['table_content'];
        $pdf->table_description = $request['table_description'];
        $pdf->invoice_title = $request['invoice_title'];
        $pdf->invoice_number = $request['invoice_number'];
        $pdf->invoice_image = $publish_path;
        $pdf->invoice_description = $request['invoice_description'];
        $pdf->user_id = $user_id;
        $pdf->template = Template::where('id', $template_id)->first()->template_title;
        $pdf->sign = $request['sign'];
        $pdf->save();
        return redirect('pdf/index')->withSuccess('Great! PDF has been inserted');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['pdf'] = Pdf::where('id', $id)->first();
        $pdf = new Pdf;
        $templates = Template::pluck('template_title', 'id');

        if (!$data['pdf']) {
            return redirect('/pdf.create-pdf');
        }
        return view('/pdf.edit-pdf', compact('pdf', 'data', 'templates', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $request = $request->all();

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'table_content' => 'required',
            'table_description' => 'required',
            'invoice_title' => 'required',
            'invoice_number' => 'required',
            'invoice_description' => 'required',
            'template' => 'required',
            'sign' => 'required',
        ]);
        $request = (object)$request;

        if (!$request->id) {
            return redirect('/pdf.create-pdf');
        }

        $check = Pdf::where('id', $request->id)->update($data);

        return Redirect::to("/pdf/index")->withSuccess('Great! PDF has been changed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $est
     * @return Response
     */
    public function destroy($id)
    {
        $check = Pdf::where('id', $id)->delete();
        return response()->json(['data' => $check]);
    }

    /*Preview the PDF created in PDF Viewer */
    public function pdf_preview($id)
    {
        $data = pdf::find($id);
        $data = $data->toArray();
        $user_name = Auth::user();
        $username = $user_name->name;
        $html = '<style>
            h1 {
                color: navy;
                font-family: times;
                font-size: 24pt;
                text-decoration: underline;
                display: inline-block;
            }
            img {
                width: 50px;
                height: auto;
            }
            p.first {
                color: #003300;
                font-family: helvetica;
                font-size: 12pt;
            }
            p.first span {
                color: #006600;
                font-style: italic;
            }
            p#second {
                color: rgb(00,63,127);
                font-family: times;
                font-size: 12pt;
                text-align: justify;
            }
            p#second > span {
                background-color: #FFFFAA;
            }
            table.first {
                color: #003300;
                font-family: helvetica;
                font-size: 8pt;
                border-left: 3px solid red;
                border-right: 3px solid #FF00FF;
                border-top: 3px solid green;
                border-bottom: 3px solid blue;
                background-color: #ccffcc;
            }
            td {
                border: 2px solid blue;
                background-color: #ffffee;
            }
            td.second {
                border: 2px dashed green;
            }
            div.test {
                color: #CC0000;
                background-color: #FFFF66;
                font-family: helvetica;
                font-size: 10pt;
                border-style: solid solid solid solid;
                border-width: 2px 2px 2px 2px;
                border-color: green #FF00FF blue red;
                text-align: center;
            }
            .lowercase {
                text-transform: lowercase;
            }
            .uppercase {
                text-transform: uppercase;
            }
            .capitalize {
                text-transform: capitalize;
            }
        </style>
        <h1 class="title">Invoice</h1>
        <img src="' . $data["invoice_image"] . '"/>
        <h1 class="title">' . $data["invoice_title"] . '</h1>

        <p class="first">Invoice content: <span>' . $data["invoice_description"] . '</span></p>

        <div class="test">' . $data["title"] . '
        <br /><span class="capitalize">' . $data["description"] . '</span>
        </div>

        <br />

        <table class="first" cellpadding="4" cellspacing="6">
         <tr>
          <td width="30" align="center"><b>No.</b></td>
          <td width="140" align="center" bgcolor="#FFFF00"><b>XXXX</b></td>
          <td width="140" align="center"><b>XXXX</b></td>
          <td width="80" align="center"> <b>XXXX</b></td>
          <td width="80" align="center"><b>XXXX</b></td>
          <td width="45" align="center"><b>XXXX</b></td>
         </tr>
         <tr>
          <td width="30" align="center">1.</td>
          <td width="140" rowspan="6" class="second">XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX</td>
          <td width="140">XXXX<br />XXXX</td>
          <td width="80">XXXX<br />XXXX</td>
          <td width="80">XXXX</td>
          <td align="center" width="45">XXXX<br />XXXX</td>
         </tr>
         <tr>
          <td width="30" align="center" rowspan="3">2.</td>
          <td width="140" rowspan="3">XXXX<br />XXXX</td>
          <td width="80">XXXX<br />XXXX</td>
          <td width="80">XXXX<br />XXXX</td>
          <td align="center" width="45">XXXX<br />XXXX</td>
         </tr>
         <tr>
          <td width="80">XXXX<br />XXXX<br />XXXX<br />XXXX</td>
          <td width="80">XXXX<br />XXXX</td>
          <td align="center" width="45">XXXX<br />XXXX</td>
         </tr>
         <tr>
          <td width="80" rowspan="2" >XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX</td>
          <td width="80">XXXX<br />XXXX</td>
          <td align="center" width="45">XXXX<br />XXXX</td>
         </tr>
         <tr>
          <td width="30" align="center">3.</td>
          <td width="140">XXXX<br />XXXX</td>
          <td width="80">XXXX<br />XXXX</td>
          <td align="center" width="45">XXXX<br />XXXX</td>
         </tr>
         <tr bgcolor="#FFFF80">
          <td width="30" align="center">4.</td>
          <td width="140" bgcolor="#00CC00" color="#FFFF00">XXXX<br />XXXX</td>
          <td width="80">XXXX<br />XXXX</td>
          <td width="80">XXXX<br />XXXX</td>
          <td align="center" width="45">XXXX<br />XXXX</td>
         </tr>
        </table>
        <p class="title">Amount :' . $data["table_content"] . 'USD</p>
        <table>
                  <thead>
                      <tr>
                        <th>Client 1</th>
                        <th>Client 2</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td><p class="first">User Name: <span>' . $user_name->name . '</span></p>
                <p class="first">Sign :' . $data["sign"] . '</p></td>
                        <td><p class="first">User Name: <span>XXX </span></p>
                <p class="first">Sign :XXX</p></td>
                      </tr>
                  </tbody>
                </table>';
        $pdf = new TCPDF();
        $pdf::SetTitle($username);
        $pdf::AddPage();
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output($username . $data["created_at"] . '.pdf');
    }

    public function upload()
    {
        return view('/pdf.upload-pdf');
    }

    /*Email the */
    public function email_send(request $request)
    {
        $user_name = Auth::user();
        $username = $user_name->name;
        $email = $request->email;
        $id = $user_name->id;
        $data = pdf::where("user_id", $id)->first();
        if ($data == null) {
            return redirect('pdf/index')->withErrors('Sorry! First, Please create your New PDF!');
        }
        $data = pdf::where("user_id", $id)->first()->toarray();
        Mail::send('emails.pdf_email', ['data' => $data, 'user_name', $user_name], function ($m) use ($email, $data) {
            $m->from('PDF@application.com', 'Amazing PDF Company');

            $m->to($email, $email)->subject('PDF Generator!');
        });
        return redirect('pdf/index')->withSuccess('Great! PDF has been email');
    }

    public function down()
    {
        return view('/pdf.down');
    }
}
