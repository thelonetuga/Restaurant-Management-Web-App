<?php

namespace App\Http\Controllers;

use App\Orders;
use Illuminate\Http\Request;
use App\Invoices;
use App\Http\Resources\InvoicesResource as InvoicesResource;
use App\Http\Resources\InvoiceItems;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoices::paginate(10);
        return InvoicesResource::collection($invoices);
    }

    public function getPendingInvoces()
    {
        $invoices = Invoices::where('state', 'pending')->get();
        return InvoicesResource::collection($invoices);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'meal_id' => 'required',
            'total_price' => 'required',
            'state' => 'required'
        ]);

        $invoice = new Invoices();
        $invoice->meal_id = $data['meal_id'];
        $invoice->total_price = $data['total_price'];
        $invoice->date = now();
        $invoice->save();

        $orders = Orders::where('meal_id', $data['meal_id'])->where('state', '=', 'delivered')->get();
        //1-6-1

        foreach ($orders as $order) {
            $invoiceItemaux = InvoiceItems::where('invoice_id', '=', $invoice->id)->where('item_id', '=', $order->item->id)->first();

            if ($invoiceItemaux == null) {

                $invoiceItem = new InvoiceItems();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->item_id = $order->item->id;
                $invoiceItem->quantity = 1;
                $invoiceItem->sub_total_price = $order->item->price;
                $invoiceItem->unit_price  = $order->item->price;
                $invoiceItem->save();

            } else {

                $invoice = InvoiceItems::findOrFail($invoiceItemaux->invoice_id)->first();
                $invoice->quantity += 1;
                $invoice->sub_total_price = $invoiceItemaux->sub_total_price + $order->item->price;
                $invoice->update();
            }
        }

        return response()->json(new InvoicesResource($invoice), 201);


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function allInvoices()
    {
        $allInvoices = Invoices::with(['items', 'meals'])->paginate(10);
        return InvoicesResource::collection($allInvoices);
    }

    public static function pendingInvoices()
    {

        $invoices = Invoices::where('state', '=', 'pending')->paginate(10);

        return InvoicesResource::collection($invoices);

        //return Meals::invoice()->where('state', '=', 'pending')->get();


       /* $invoices = array();
        $pending = Invoices::where('state', '=', 'pending')->get();
        foreach ($pending as $item) {
            $meal = Meals::findOrFail($item->meal_id);
            $merge = array();
            $merge += array("invoice" => $item);
            $merge += array("meal" => $meal);
            array_push($invoices, $merge);

        }

        return collect($invoices);*/
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nif' => 'required',
            'name' => 'required',
        ]);

        $invoice = Invoices::where('id', $id)->first();
        $invoice->nif = $data['nif'];
        $invoice->name = $data['name'];

        $invoice->update();
        return new InvoicesResource($invoice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
