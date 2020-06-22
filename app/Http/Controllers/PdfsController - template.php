<?php

namespace App\Http\Controllers;

use App\Pdf;
use App\Template;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

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
                    $btn = $btn . ' <a href="' . $pdfUrl . '" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="PDF" class="btn btn-primary btn-sm">PDF</a>';
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
        $imageName = $user_name . '.' . $request->invoice_image->getClientOriginalExtension();
        $request->invoice_image->move($path, $imageName);
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
        $pdf->invoice_image = $imageName;
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

    public function pdf_preview($id)
    {
        $data = pdf::find($id);
        $data = $data->toArray();
        $user_name = Auth::user();
        $username = $user_name->name;
        $pdf = new TCPDF();
        $html = '<html><head>';
        $html .= '<style>
                    .invoice {
  width: 700px;
  border: 1px solid #000000;
  margin: auto;
  padding: 30px;
}
.invoice-table p {
  padding: 0;
}
.invoice .invoice-logo {
  width: 100%;
}
.invoice .invoice-logo img {
  width: 100px;
}
.invoice .invoice-sec-1 {
  width: 100%;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}
.invoice .invoice-sec-1 .invoice-sec-1-ref {
  width: 50%;
}
.invoice .invoice-sec-1 .invoice-sec-1-date {
  width: 50%;
}
.invoice .invoice-sec-1 .invoice-sec-1-date p {
  position: relative;
  top: -107px;
  text-align: right;
}
.invoice .invoice-sec-1 .to-invoice {
  margin-top: 85px;
  padding-left: 42px;
}
.invoice-table {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  margin-top: 40px;
}
.invoice-table .invoice-table-container {
  width: 100%;
  margin: auto;
}
.invoice-table .invoice-table-container .invoice-table-data {
  display: flex;
  flex-direction: row;
}
.invoice-table .invoice-table-container .invoice-table-data .invoice-table-sl {
  text-align: center;
  width: 20%;
  border: .5px solid #000000;
  border-left: 1px solid #000000 !important;
}
.invoice-table .invoice-table-container .invoice-table-data .invoice-table-sl-h {
  border-left: 1px solid #000000!important;
  border-top: 1px solid #000000 !important;
}
.invoice-table .invoice-table-container .invoice-table-data .invoice-table-desc-h {
  border-top: 1px solid #000000 !important;
}
.invoice-table .invoice-table-container .invoice-table-data .invoice-table-desc {
  text-align: center;
  width: 60%;
  border: .5px solid #000000;
}
.invoice-table .invoice-table-container .invoice-table-data .invoice-table-amount-h {
  border-top: 1px solid #000000 !important;
  border-right: 1px solid #000000 !important;
}
.invoice-table .invoice-table-container .invoice-table-data .invoice-table-amount {
  text-align: center;
  width: 20%;
  border: .5px solid #000000;
  border-right: 1px solid #000000 !important;
}
.invoice-table .invoice-table-container .invoice-table-footer {
  border: 1px solid #000000;
  display: flex;
  flex-direction: row;
  border-top: .5px solid #000000 !important;
}
.invoice-table .invoice-table-container .invoice-table-footer .invoice-total {
  text-align: center;
  width: 80%;
}

.invoice-table .invoice-table-container .invoice-table-footer .invoice-total-amount {
  text-align: center;
  width: 20%;
}
.invoice .invoice-banner {
  margin: 5px;
  width: 100%;
}
.invoice .invoice-banner .banner-d {
  width: 200px;
  border: 2px solid #000000;
  border-radius: 5px;
  margin: auto;
  padding: 5px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.invoice .invoice-banner .banner-d p {
  font-weight: bold;
  margin: 0px;
}
.invoice .invoice-declaration {
  text-align: center;
}
.invoice .invoice-greeting {
  margin-top: 70px;
}
.invoice .invoice-greeting p {
  margin: 3px;
}
                </style>';
        $html .= '</head><body>';
        $html .=  view('pdftemplates.pdftemplate1', ['data' => $data, 'username' => $username])->render();
        $html .= '</body></html>';
        $html = (string) $html;
        /*$html = view('pdftemplates.pdftemplate1', ['data' => $data, 'username' => $username])->render();*/
        $pdf::SetTitle('Invoice Generator');
        $pdf::AddPage();
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('username.pdf');
        exit();
    }
}
